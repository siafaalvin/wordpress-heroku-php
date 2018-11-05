<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME','hyroglf9_CSS5455_st2');


define('FORCE_SSL_LOGIN', true);

define('FORCE_SSL_ADMIN', true);

/** MySQL database username */
//define('DB_USER','hyroglf9_st2');
define('DB_USER','hyroglf9_st2');

/** MySQL database password */
//define('DB_PASSWORD','M5yaEdx_i60CKZrWP5_Q');
define('DB_PASSWORD','M5yaEdx_i60CKZrWP5_Q');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '1gH,-bPusJ9{2yQ8uZ(HQ>Bq&HAt:nVfg1qI<35W[~JLC.#96!>%uU4|Oxh*kK+x');
define('SECURE_AUTH_KEY',  ':cFp-J@Og/.S}bNK ZRd{{u>h#|p;TSkSx;pO2c`gbJjEjH#eWmgqfM=dkx@aA8.');
define('LOGGED_IN_KEY',    '?(PyKmx9rTBJTtyeq`C_G=w:R{M__7_-O|(fx8%#N-IMMHuWb>apk@rn1]>=; a>');
define('NONCE_KEY',        '4oE=|MY);e&2nR~6B3,bl^k?k3PTby3&mOeC$(yAD,Q+oA*89WA+>a|AWveo9RK4');
define('AUTH_SALT',        'gLR<%v &D}Kc-0`+GC#E.@@JJz|XM ma,vc5>ua/&9*G1`SDQMIQY_}2`.#U ,]c');
define('SECURE_AUTH_SALT', 'HoW9JQYk9@8+I@@ESQk,i]>Fx)P,2/0aE>oI2G6agc, a^#TZ6H5&l5-B2M8}BUx');
define('LOGGED_IN_SALT',   't[Dw}^b=-Vj|uN#}-swA_MN{M/1YU/1*GxTs;DLeeb7F|A#A(j1eb[Jh%P*7K=RD');
define('NONCE_SALT',       '#fr6<y0F,mkoZgr18IcWRe5#@Q^oA7@HW9ajv>>4@W%Tm452]lMri7t3Lx+BIi&J');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('WP_CACHE_KEY_SALT', 'ywiCCaKp8Tmoe5QzdSoCeA');
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

# Disables all core updates. Added by SiteGround Autoupdate:
define( 'WP_AUTO_UPDATE_CORE', false );
