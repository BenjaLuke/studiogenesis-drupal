# Modelo de datos

Este documento describe la estructura de contenidos definida para la prueba tecnica Drupal.

## Taxonomias

### Categorias de noticias

Vocabulario usado para clasificar las noticias publicadas.

Terminos iniciales:

- Actualidad
- Tecnologia
- Empresa

Configuracion exportada:

```text
config/sync/taxonomy.vocabulary.categorias_de_noticias.yml
```

### Categorias de productos

Vocabulario usado para clasificar los productos de la web.

Terminos iniciales:

- Software
- Consultoria
- Formacion

Configuracion exportada:

```text
config/sync/taxonomy.vocabulary.categorias_de_productos.yml
```

Los terminos de taxonomia son contenido, no configuracion. Por eso se conservaran tambien en la exportacion SQL final de la base de datos.

## Tipo de contenido Noticia

Tipo de contenido para la seccion publica de noticias.

Configuracion principal:

```text
config/sync/node.type.noticia.yml
```

Campos definidos:

| Etiqueta | Nombre maquina | Tipo | Uso |
| --- | --- | --- | --- |
| Fecha noticia | `field_fecha_noticia` | Fecha | Fecha editorial de la noticia. |
| Subtitulo | `field_subtitulo` | Texto corto | Texto de apoyo al titulo. |
| Categoria de noticia | `field_categoria_de_noticia` | Referencia a taxonomia | Clasificacion de la noticia. |
| Cuerpo noticia | `field_cuerpo_noticia` | Texto largo | Contenido principal de la noticia. |
| Autor | `field_autor` | Texto corto | Autor visible solicitado por la prueba. |
| Ficheros relacionados | `field_ficheros_relacionados` | Referencia a multimedia | Documentos asociados a la noticia. |

## Tipo de contenido Producto

Tipo de contenido para la seccion publica de productos.

Configuracion principal:

```text
config/sync/node.type.producto.yml
```

Campos definidos:

| Etiqueta | Nombre maquina | Tipo | Uso |
| --- | --- | --- | --- |
| Categoria de producto | `field_categoria_de_producto` | Referencia a taxonomia | Clasificacion comercial del producto. |
| Descripcion corta | `field_descripcion_corta` | Texto corto | Resumen para listados y tarjetas. |
| Descripcion larga | `field_descripcion_larga` | Texto largo | Contenido completo del producto. |
| Foto del producto | `field_foto_del_producto` | Referencia a multimedia | Imagenes del producto mediante biblioteca de medios. |
| Tarifa del producto | `field_tarifa_del_producto` | Decimal | Precio o tarifa del producto con decimales. |

## Criterios aplicados

- Las categorias se modelan con taxonomia para poder filtrar listados con Views.
- Las fotos y ficheros se modelan con Multimedia para usar Media Library.
- La tarifa se guarda como numero decimal, no como texto, para permitir ordenacion o filtros futuros.
- Se crearon campos explicitos para cumplir el enunciado aunque Drupal tenga metadatos internos similares.
