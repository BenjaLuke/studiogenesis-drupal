<?php

declare(strict_types=1);

namespace Drupal\studiogenesis_newsletter\Form;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Datetime\TimeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Formulario publico para registrar suscripciones a la newsletter.
 */
final class NewsletterSubscriptionForm extends FormBase {

  /**
   * Crea el formulario con los servicios que necesita.
   *
   * La conexion a base de datos se usa para guardar la suscripcion, el validador
   * de email evita direcciones mal formadas y el servicio de tiempo centraliza
   * los timestamps de alta y modificacion.
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
    return 'studiogenesis_newsletter_subscription_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Datos personales basicos de la persona que se suscribe.
    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
      '#maxlength' => 100,
    ];

    $form['apellidos'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Apellidos'),
      '#required' => TRUE,
      '#maxlength' => 150,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
      '#maxlength' => 254,
    ];

    // Interes principal para poder segmentar futuras comunicaciones.
    $form['interes'] = [
      '#type' => 'select',
      '#title' => $this->t('Interes'),
      '#required' => TRUE,
      '#options' => [
        'noticias' => $this->t('Noticias'),
        'productos' => $this->t('Productos'),
        'ambos' => $this->t('Noticias y productos'),
      ],
      '#default_value' => 'ambos',
    ];

    $form['acepta_privacidad'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Acepto la politica de privacidad'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Suscribirme'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $email = mb_strtolower(trim((string) $form_state->getValue('email')));

    // Aunque el campo sea de tipo email, validamos tambien en servidor.
    if (!$this->emailValidator->isValid($email)) {
      $form_state->setErrorByName('email', $this->t('Introduce un email valido.'));
      return;
    }

    // Evitamos duplicados antes de insertar en la tabla.
    $exists = (bool) $this->database
      ->select('studiogenesis_newsletter_subscription', 's')
      ->condition('email', $email)
      ->countQuery()
      ->execute()
      ->fetchField();

    if ($exists) {
      $form_state->setErrorByName('email', $this->t('Este email ya esta suscrito a la newsletter.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $now = $this->time->getRequestTime();

    // Guardamos los datos normalizados en la tabla propia del modulo.
    $this->database->insert('studiogenesis_newsletter_subscription')
      ->fields([
        'nombre' => trim((string) $form_state->getValue('nombre')),
        'apellidos' => trim((string) $form_state->getValue('apellidos')),
        'email' => mb_strtolower(trim((string) $form_state->getValue('email'))),
        'interes' => (string) $form_state->getValue('interes'),
        'acepta_privacidad' => (int) (bool) $form_state->getValue('acepta_privacidad'),
        'activo' => 1,
        'created' => $now,
        'updated' => $now,
      ])
      ->execute();

    $this->messenger()->addStatus($this->t('Gracias por suscribirte a la newsletter.'));
    $form_state->setRedirect('<front>');
  }

}
