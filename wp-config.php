<?php
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
define('DB_NAME', '2hcblog');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'drdoom1');

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
define('AUTH_KEY',         'wfgcPa-+@vx=>uwG7Lopkf2tR>lv.j]51^Sl ?@4:HvGG_f!ksSv> =_^k[3-{Op');
define('SECURE_AUTH_KEY',  ')MxLSh=^4BNQ@q-_f iT[&ti)vKVr+8!fB|;[zd:nYmzt:>{8@%4DB1JUDI+Eh}2');
define('LOGGED_IN_KEY',    'J}4|2=I6Vl^m#pt*KWJgnZHL$iDKN5(G4,||>p[X!d}rt2V_k aDy@0.7p/nK,/{');
define('NONCE_KEY',        'e<R[%[|!>V_o0n.wJ}P`b`u+yyWPpv}1wkMV|QO/nR!jit$T(9oOMu+J,sVw(-y2');
define('AUTH_SALT',        '}*f-OUC*`voN<!^e@Q@e-$(i/IgQsg5z|E[Mnt:),0S2u?M;mseD[st5GN}kxhuO');
define('SECURE_AUTH_SALT', '49eI}N>6[g+~&ssd5}w`@@HZ&e*u[La~$]y?/tRH|7Ay`>#Sa)&,:#FFJe~%PQwb');
define('LOGGED_IN_SALT',   '-B_=NdB*+X+^d&sI8hQ`54ND/M[4&qVVvXaX8@Y0L6M(BZIl.0Z=lhVc[<)@@WM&');
define('NONCE_SALT',       'kIZr{Lx=q,x(? -).x_KCYghy3d`+-5IqN!ypN6dvzfwdhXD^:98(ix:ftq0;H@~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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

define('FS_METHOD', 'ssh');
