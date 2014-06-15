<?php

if ( !defined('WP_MEMORY_LIMIT') )
	define('WP_MEMORY_LIMIT', '500M');

/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


/**
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

define('QUICK_CACHE_ALLOWED', TRUE);

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'casatibe_2013');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'root');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'z{RqL7/j,DE7vqk@]Q5{F:Z@i0+|&D38sG3[)PgAr?gv;kLpaSK@;FG}}N4DI%%^'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_KEY', 'j?c!#f=?eBk@XHyP0,_aHlO9nGL0.]f$/%G wnSK}z{NyVQ;Z~ (<:}NpBz)%<IE'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_KEY', 'tn(Q/DD`pO^Y.6db*%GG|L8oGJd*oqiQ]%W(B651>Kme-glCSC/bEB`Z-m0Jg!uW'); // Cambia esto por tu frase aleatoria.
define('NONCE_KEY', 'SzD4Eudt#|j8:2e}o1TTR10eJ4&]<k,|Kn?#q)iR+:auZtj:i%shKbF{T}@TKRN&'); // Cambia esto por tu frase aleatoria.
define('AUTH_SALT', 'd8![*dW5AIKQlK~~H}pj.U`[NGJy^rx$*l~H<G|&sA9I.>H8oT,0NDy:v2n4%|hu'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_SALT', '{YXx|RVQ*q<6F8a>SUnF&l;MzK6>a-VW}lMXK3TQIkOf7y%=@^DI<cL9~j<4Q_MB'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_SALT', '2b*8|]Ic^N;+!s$mNPmZEZ=TMZ}vV`T|S}8q(<+~qTq&#xn<X}:3R2d:Z2o#B5Ul'); // Cambia esto por tu frase aleatoria.
define('NONCE_SALT', 'tLoc,Qtc)q~rV3x!F*hbxZo5+yTVB#Sl+ZD)D)UO@>dQdD5G)LCV6L<VR,]wu7V!'); // Cambia esto por tu frase aleatoria.

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';

/**
 * Idioma de WordPress.
 *
 * Cambia lo siguiente para tener WordPress en tu idioma. El correspondiente archivo MO
 * del lenguaje elegido debe encontrarse en wp-content/languages.
 * Por ejemplo, instala ca_ES.mo copiándolo a wp-content/languages y define WPLANG como 'ca_ES'
 * para traducir WordPress al catalán.
 */
define('WPLANG', 'es_ES');

/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

