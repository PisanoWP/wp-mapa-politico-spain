===  WP Mapa Politico España ===
Contributors: jcglp
Tags: wordpress, plugin, map, image, spain, svg
Donate link: https://www.paypal.me/jcglp/1.5
Requires at least: 3.9
Tested up to: 5.5
Requires PHP: 5.2.4
Stable tag: 3.5.1 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Inserta una imagen de un mapa político de España, con áreas definidas sobre las provincias sobre las que se pueden definir hipervínculos.


== Description ==

Este plugin permite insertar un mapa político de España en post o páginas.

En la página del plugin se pueden definir los titles e hipervínculos de cada una de las provincias.


== Installation ==

Para instalar el plugin.

1. Descarga el plugin, descomprime el archivo y súbelo a la carpeta /wp-content/plugins/
2. Activa el plugin a través del menu 'Plugins' en WordPress.
3. Para mostrar el mapa usar el shortcode   [wpmps-map]

O bien, desde el panel de control de WordPress

1. Ir a la sección Plugin
2. Haz click en el enlace Añadir nuevo
3. En el buscador, escribe "Mapa Politico", y ¡¡ ahi estará !!.
4. Haz click en instalar


== Frequently Asked Questions ==

= ¿Puedo tener más de un mapa diferente?

Pues no, el plugin solo acepta un mapa. Eso si donde pongas el shortcode saldrá una mapa.

= Obtengo el ERROR NO SE HA PODIDO LOCALIZAR MAPA A MOSTRAR a insertar el mapa

El plugin utiliza funciones CURL de php para recuperar la imagen. En algunas instalaciones puede no funcionar.
Desde la versión 3.1.5 En los ajustes del plugin puedes cambiar el método de recuperar la imagen, de CURL
a FILE, y en la mayoría de los casos problema solucionado.


= He actualizado y no muestra el mapa, solo el shortcode [wp-political-map-spain]

Desde la versión 2.0.0 el shortcode estaba obsoleto, y es a partir de la 3.0.0 cuando deja de existir.
Sustituye por el nuevo shortode  [wpmps-map] y listo.


= ¿Existe una versión Premium del plugin?

Pues no, lo que se ve es lo que hay, pero vamos si quieres me puedes invitar a un café desde el [botón de donación](https://www.paypal.me/jcglp/1.5).

Si tienes dudas, preguntas o te da errores ponte en contacto conmigo, estaré encantado de ayudarte jcglp@yahoo.es


= ¿Existe una versión del mapa solo de comunidades?

Te recomiendo que eches un vistazo a [Plugin Mapa Comunidades](https://mispinitoswp.wordpress.com/2020/04/29/mapa-por-comunidades/)


== Screenshots ==

1.  Página del plugin para definir los hipervinculos sobre cada provincia
2.  Configruación de colores
3.  Mapa en una página


== Changelog ==

= 3.5.1 =
* Arreglar cambio color fondo del mapa, que había dejado de funcionar en la ver. anterior.
* Tested 5.5

= 3.5.0 =
* Esta nueva versión del plugin utiliza una nueva imagen para el mapa de España, con los elementos más compactos, más visibilidad para las ciudades autónomas de Ceuta y Melilla, así como los países cercanos.

= 3.2.1 =
* Tested 5.4
* Prueba de vida. Aún hay alguien detrás del plugin

= 3.2.0 =
* Nueva opción, Rellenar las provincias que tienen enlace con otro color

= 3.1.6 =
* Nueva opción, Selección del método para recuperar la imagen SVG
* Limpieza de opciones al borrar el plugin

= 3.1.5 =
* Tested 5.1
* Personalizar nombre clases css

= 3.1.4 =
* Nueva opción para decidir si mostrar o no el borde
* Elección del color del borde
* css específico para IE, corrige mapa pequeño

= 3.1.3 =
* Compatiblidad con navegadores Safari, otra vez...

= 3.1.2 =
* Tested 4.9.6
* Prueba de vida. Aún hay alguien detrás del plugin

= 3.1.1 =
* Fichero de Internationalization
* Tested 4.9

= 3.1.0 =
* Compatiblidad con navegadores Safari

= 3.0.2 =
* Cambio carga imagen  para evitar linea "gris"
* Eliminacion shortcode antiguo [wp-political-map-spain]

= 3.0.1 =
* Valores por defecto en las opciones del plugin para nuevas instalaciones

= 3.0.0 =
* Cambio a imagenes SVG
* Selección color de fondo de la provincia
* Selección color provincia activada

= 2.0.3 =
* Correción enlances internos
* Validación en WP 4.7

= 2.0.2 =
* Fichero de idiomas actualizado

= 2.0.1 =
* Corrige el error 404 al grabar opciones

= 2.0.0 =
* Cambio estructural del plugin.
* Optimización para adaptarse a diferentes temas.
* Minificación de ficheros.

= 1.1.6 =
* Correción "redeclare mw_enqueue_color_picker()"

= 1.1.5 =
* Correción errores menores

= 1.1.4 =
* Compatibiliad WordPress conn themes twentytwelve, twentythirteen y twentyfourteen

= 1.1.3 =
* Correcion errores menores
* Imagenes optimizadas
* No borrar datos al desactivar el plugin

= 1.1.2 =
* Correción errores menores.

= 1.1.1 =
* Se corrige el error "headers already sent" al usar el plugin

= 1.1.0 =
* Cambio del mapa base. Color Azul Claro
* Se resalta las areas al pasar el raton por encima, Azul Oscuro


= 1.0.5 =
* Marco sobre la imagen del mapa con opcion para poner color de fondo.

= 1.0.4 =
* Upgrade del css para tamaño del mapa

= 1.0.3 =
* Cambio de mapa base. Color Azul
* Idioma Serbio añadido. Cortesia de http://firstsiteguide.com/

= 1.0.2 =
* Reset de atributos de la imagen

= 1.0.1 =
* Los title modificados no se muestran
* Al desactivar - activar el plugin duplica las entradas

= 1.0.0 =
* Primera versión liberada


== Upgrade Notice ==

= 3.5.1 =
* Arreglar cambio color fondo del mapa, que había dejado de funcionar en la ver. anterior.
* Tested 5.5
