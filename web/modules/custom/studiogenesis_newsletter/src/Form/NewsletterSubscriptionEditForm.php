<?php

declare(strict_types=1);

namespace Drupal\studiogenesis_newsletter\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Formulario administrativo para editar suscriptores existentes.
 */
final class NewsletterSubscriptionEditForm extends FormBase {

  /**
   * Crea el formulario con los servicios necesarios.
   *
   * La conexion permite cargar y actualizar el registro, el validador revisa el
   * email en servidor y el servicio de tiempo actualiza la fecha de modificacion.
   */
  public function __construct(
    private readonly Connection $database,
    private readonly EmailValidatorInterface $emailValidator,
    private readonly TimeInterface $time,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('database'),
      $container->get('email.validator'),
      $container->get('datetime.time'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'studiogenesis_newsletter_subscription_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string|int|null $subscription = NULL): array {
    $subscription_id = (int) $subscription;
    $record = $this->loadSubscription($subscription_id);

    if (!$record) {
      throw new NotFoundHttpException();
    }

    // Guardamos el ID en el estado del formulario para usarlo en validar y guardar.
    $form_state->set('subscription_id', $subscription_id);

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
      '#maxlength' => 100,
      '#default_value' => $record->nombre,
    ];

    $form['apellidos'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Apellidos'),
      '#required' => TRUE,
      '#maxlength' => 150,
      '#default_value' => $record->apellidos,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
      '#maxlength' => 254,
      '#default_value' => $record->email,
    ];

    $form['interes'] = [
      '#type' => 'select',
      '#title' => $this->t('Interes'),
      '#required' => TRUE,
      '#options' => [
        'noticias' => $this->t('Noticias'),
        'productos' => $this->t('Productos'),
        'ambos' => $this->t('Noticias y productos'),
      ],
      '#default_value' => $record->interes,
    ];

    // El estado permite desactivar una suscripcion sin borrar su historial.
    $form['activo'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Suscripcion activa'),
      '#default_value' => (int) $record->activo,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Guardar cambios'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $subscription_id = (int) $form_state->get('subscription_id');
    $email = mb_strtolower(trim((string) $form_state->getValue('email')));

    if (!$this->emailValidator->isValid($email)) {
      $form_state->setErrorByName('email', $this->t('Introduce un email valido.'));
      return;
    }

    // En edicion permitimos conservar el email actual, pero no duplicar otro.
    $exists = (bool) $this->database
      ->select('studiogenesis_newsletter_subscription', 's')
      ->condition('email', $email)
      ->condition('id', $subscription_id, '<>')
      ->countQuery()
      ->execute()
      ->fetchField();

    if ($exists) {
      $form_state->setErrorByName('email', $this->t('Este email ya pertenece a otra suscripcion.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $subscription_id = (int) $form_state->get('subscription_id');

    // Actualizamos solo los campos editables desde administracion.
    $this->database->update('studiogenesis_newsletter_subscription')
      ->fields([
        'nombre' => trim((string) $form_state->getValue('nombre')),
        'apellidos' => trim((string) $form_state->getValue('apellidos')),
        'email' => mb_strtolower(trim((string) $form_state->getValue('email'))),
        'interes' => (string) $form_state->getValue('interes'),
        'activo' => (int) (bool) $form_state->getValue('activo'),
        'updated' => $this->time->getRequestTime(),
      ])
      ->condition('id', $subscription_id)
      ->execute();

    $this->messenger()->addStatus($this->t('La suscripcion se ha actualizado correctamente.'));
    $form_state->setRedirect('studiogenesis_newsletter.admin_list');
  }

  /**
   * Carga un suscriptor por ID desde la tabla propia del modulo.
   */
  private function loadSubscription(int $subscription_id): object|false {
    return $this->database
      ->select('studiogenesis_newsletter_subscription', 's')
      ->fields('s')
      ->condition('id', $subscription_id)
      ->execute()
      ->fetchObject();
  }

}
