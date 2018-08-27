<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'istilist_staging');

/** MySQL database username */
define('DB_USER', 'istilist_wpadmin');

/** MySQL database password */
define('DB_PASSWORD', 'N2Db&R]fXM#[');

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
define('AUTH_KEY',         'd{+e% U%lkS!R nO,Qzb>ce2;cT/7!>.-x^>TU))5M(M*[$9nf<7ym5>UMl}iR!-');
define('SECURE_AUTH_KEY',  ')@E?x[Ku2ss`8wc5]mL-4Ls%P;O},=cp!NfId:+7_mnc:F<QBQ<-ZU /|XO*_JMV');
define('LOGGED_IN_KEY',    'boQ+XTB_gjLQ|~{6*W.K$e0K9YS#tfzzaU9kW(lYKGC u}C+7B9*8<C0|835;n-z');
define('NONCE_KEY',        '0SwDC;oq@n=oiXMExB TW7B3ycc?V*_4~c1}R0`|8dutR6msZ6jG/KsCCpyM+KQh');
define('AUTH_SALT',        'S4X0HkHhN}m__(s;(J*Kj!@#.}46toR/7|X:$-W;S7tE0+ 06rAM6AkF%D-B3o?+');
define('SECURE_AUTH_SALT', '|Dvoz23|[y9u)a(5{cQ6!I-JF,=B2UzSLi|V9Z=EJuu)>e1Q-_6.Fr)}=I+$<wdH');
define('LOGGED_IN_SALT',   '2U5zdGV6^_gIk h&^5@mT-M/$#ZM$&ib}u=8YD9D+{hv9Ci%00-Va{]2!G.^1wRk');
define('NONCE_SALT',       '}X+9gvDHsz)E@&hM#dgo6BL.,V5k+RwQ?R2)r@IpZez+GcnTTEC-8-umnW`maa*a');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'shoppers_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define( 'AUTOMATIC_UPDATER_DISABLED', false );
define( 'WP_AUTO_UPDATE_CORE', true );
define('WP_MEMORY_LIMIT', '64M');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');