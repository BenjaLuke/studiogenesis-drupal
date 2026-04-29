<?php

declare(strict_types=1);

namespace Drupal\studiogenesis_newsletter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controlador de administracion para las suscripciones de newsletter.
 */
final class NewsletterAdminController extends ControllerBase {

  /**
   * Crea el controlador con los servicios necesarios.
   *
   * La conexion lee los registros de la tabla propia y el formateador de fechas
   * convierte los timestamps en fechas legibles para el administrador.
   */
  public function __construct(
    private readonly Connection $database,
    private readonly DateFormatterInterface $dateFormatter,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('database'),
      $container->get('date.formatter'),
    );
  }

  /**
   * Muestra el listado administrativo de suscriptores.
   */
  public function list(): array {
    // Consultamos la tabla propia creada por el modulo al activarse.
    $rows = $this->database
      ->select('studiogenesis_newsletter_subscription', 's')
      ->fields('s', [
        'id',
        'nombre',
        'apellidos',
        'email',
        'interes',
        'activo',
        'created',
      ])
      ->orderBy('created', 'DESC')
      ->execute()
      ->fetchAll();

    $table_rows = [];

    // Transformamos cada registro de base de datos en una fila renderizable.
    foreach ($rows as $row) {
      $table_rows[] = [
        'data' => [
          $row->id,
          $row->nombre,
          $row->apellidos,
          $row->email,
          $this->getInterestLabel((string) $row->interes),
          ((int) $row->activo === 1) ? $this->t('Activo') : $this->t('Inactivo'),
          $this->dateFormatter->format((int) $row->created, 'short'),
          [
            'data' => [
              '#type' => 'operations',
              '#links' => [
                'edit' => [
                  'title' => $this->t('Editar'),
                  'url' => Url::fromRoute('studiogenesis_newsletter.edit', [
                    'subscription' => $row->id,
                  ]),
                ],
                'delete' => [
                  'title' => $this->t('Eliminar'),
                  'url' => Url::fromRoute('studiogenesis_newsletter.delete', [
                    'subscription' => $row->id,
                  ]),
                ],
              ],
            ],
          ],
        ],
      ];
    }

    return [
      '#type' => 'table',
      '#header' => [
        $this->t('ID'),
        $this->t('Nombre'),
        $this->t('Apellidos'),
        $this->t('Email'),
        $this->t('Interes'),
        $this->t('Estado'),
        $this->t('Fecha de alta'),
        $this->t('Operaciones'),
      ],
      '#rows' => $table_rows,
      '#empty' => $this->t('Todavia no hay suscripciones registradas.'),
    ];
  }

  /**
   * Exporta los suscriptores en un archivo CSV descargable.
   */
  public function exportCsv(): StreamedResponse {
    $filename = 'suscriptores-newsletter-' . date('Y-m-d') . '.csv';

    $response = new StreamedResponse(function (): void {
      $handle = fopen('php://output', 'w');

      if ($handle === FALSE) {
        throw new \RuntimeException('No se pudo abrir la salida CSV.');
      }

      // La marca UTF-8 ayuda a que Excel abra correctamente caracteres espanoles.
      fwrite($handle, "\xEF\xBB\xBF");

      $this->writeCsvRow($handle, [
        'ID',
        'Nombre',
        'Apellidos',
        'Email',
        'Interes',
        'Acepta privacidad',
        'Estado',
        'Fecha de alta',
        'Ultima modificacion',
      ]);

      // Leemos todos los registros en el momento de descargar el archivo.
      $rows = $this->database
        ->select('studiogenesis_newsletter_subscription', 's')
        ->fields('s', [
          'id',
          'nombre',
          'apellidos',
          'email',
          'interes',
          'acepta_privacidad',
          'activo',
          'created',
          'updated',
        ])
        ->orderBy('created', 'DESC')
        ->execute();

      foreach ($rows as $row) {
        $this->writeCsvRow($handle, [
          $row->id,
          $row->nombre,
          $row->apellidos,
          $row->email,
          $this->getInterestLabel((string) $row->interes),
          ((int) $row->acepta_privacidad === 1) ? 'Si' : 'No',
          ((int) $row->activo === 1) ? 'Activo' : 'Inactivo',
          $this->dateFormatter->format((int) $row->created, 'short'),
          $this->dateFormatter->format((int) $row->updated, 'short'),
        ]);
      }

      fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
    $response->headers->set('Cache-Control', 'private, no-store');

    return $response;
  }

  /**
   * Devuelve la etiqueta visible del interes guardado en base de datos.
   */
  private function getInterestLabel(string $interest): string {
    $labels = [
      'noticias' => $this->t('Noticias'),
      'productos' => $this->t('Productos'),
      'ambos' => $this->t('Noticias y productos'),
    ];

    return (string) ($labels[$interest] ?? $interest);
  }

  /**
   * Escribe una fila CSV usando separador de punto y coma.
   *
   * En configuraciones espanolas de Excel el punto y coma suele abrir mejor que
   * la coma porque la coma se reserva para decimales.
   *
   * @param resource $handle
   *   Recurso de escritura abierto contra la salida del navegador.
   * @param array<int, string|int> $row
   *   Valores de la fila que se quiere escribir.
   */
  private function writeCsvRow($handle, array $row): void {
    fputcsv($handle, $row, ';', '"', '');
  }

}
