<?php

declare(strict_types=1);

namespace Drupal\studiogenesis_newsletter\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Formulario de confirmacion para eliminar suscripciones.
 */
final class NewsletterSubscriptionDeleteForm extends ConfirmFormBase {

  /**
   * ID del suscriptor que se va a eliminar.
   */
  private int $subscriptionId;

  /**
   * Email mostrado en el mensaje de confirmacion.
   */
  private string $email;

  /**
   * Crea el formulario con la conexion a base de datos.
   */
  public function __construct(
    private readonly Connection $database,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('database'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'studiogenesis_newsletter_subscription_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('¿Seguro que quieres eliminar la suscripcion de @email?', [
      '@email' => $this->email,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl(): Url {
    return Url::fromRoute('studiogenesis_newsletter.admin_list');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Eliminar');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string|int|null $subscription = NULL): array {
    $record = $this->loadSubscription((int) $subscription);

    if (!$record) {
      throw new NotFoundHttpException();
    }

    // Estos datos se usan despues en la pregunta y en el borrado definitivo.
    $this->subscriptionId = (int) $record->id;
    $this->email = (string) $record->email;

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // El borrado es definitivo porque es una accion administrativa confirmada.
    $this->database
      ->delete('studiogenesis_newsletter_subscription')
      ->condition('id', $this->subscriptionId)
      ->execute();

    $this->messenger()->addStatus($this->t('La suscripcion se ha eliminado correctamente.'));
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
