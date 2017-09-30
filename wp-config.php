<?php
ob_start();
/**

 * The base configurations of the WordPress.

 *

 * This file has the following configurations: MySQL settings, Table Prefix,

 * Secret Keys, WordPress Language, and ABSPATH. You can find more information

 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing

 * wp-config.php} Codex page. You can get the MySQL settings from your web host.

 *

 * This file is used by the wp-config.php creation script during the

 * installation. You don't have to use the web site, you can just copy this file

 * to "wp-config.php" and fill in the values.

 *

 * @package WordPress

 */



// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define('DB_NAME', 'digishop4_stayfab');



/** MySQL database username */

define('DB_USER', 'digishop4');



/** MySQL database password */

define('DB_PASSWORD', '123456789digi123shop');



/** MySQL hostname */

define('DB_HOST', 'mysql11.gigahost.dk');



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

define('AUTH_KEY',         '|S7XzY`L$-K%0iPK9BK$Zo_P$)}uyigPM|N(OPmG6S!W]k]07po~8s@6KCS+-E/)');

define('SECURE_AUTH_KEY',  'J]&8XxUr,!]SNk-mP|6hew;Jb^Q4KW}~:1F5%@|JTV,X3pDT8!/<|x.sAW+|.+S ');

define('LOGGED_IN_KEY',    'Xm#5%&OADb,)EJ/8hr]DNtUtF8@tUZ!v}$|uKR]fldJqM#0U|w&RstSO-^{c%jpr');

define('NONCE_KEY',        '-N$9|$#-A_nW*b=2+hkBr`1ULh(Y!}OQ|<=c]4:^/x ?,p9hvX0@BFow,y)R5{!R');

define('AUTH_SALT',        '$HM|9T&*=dUv.([s(dze-KaH5<_Zdn&Sl{QzV5(oG-|},O8XK-z+pD)p~[dw5DhA');

define('SECURE_AUTH_SALT', 'JHLU9Ym&-BY%e(7&q8Ce!$h1kR8|VWN&~?<N%$H=H<Sivq07~BdtT^l!9a3B2gdf');

define('LOGGED_IN_SALT',   ':2-e ?h:BgY_f]KO:;,uSllNbd|?I)M~dPagUBfi]nGN7FyJXGHD-f|6+~/]@vtV');

define('NONCE_SALT',       '~^=RgR-#2$a,3S.E*q% -mX|6~1w9_jhit99|`eJ+C?Dc%ap)}T;?.`+=;982_2=');



/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each a unique

 * prefix. Only numbers, letters, and underscores please!

 */

$table_prefix  = 'wp_';



/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 */

define('WP_DEBUG', false);



/* That's all, stop editing! Happy blogging. */



/** Absolute path to the WordPress directory. */

if ( !defined('ABSPATH') )

	define('ABSPATH', dirname(__FILE__) . '/');



/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');

