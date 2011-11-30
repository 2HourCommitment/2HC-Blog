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
define('DB_PASSWORD', 'RN8folding');

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
define('AUTH_KEY',         ')L-h9&ZDMouvJ%:nTM`{B[@E&GrxMYe-nsv`s`[,EFbxqCtW11H2ZT@75t>QsTQT');
define('SECURE_AUTH_KEY',  '}Y^HDihrOp:F(<<q))bUTh(M:H[u^(uH+V.^3~xbmA|)emPmP[v,mGE.7:ki<_|z');
define('LOGGED_IN_KEY',    '>*vl:725vd~%ORQXpquq]j///#`PM|+n.5%yS8% a*=zi@8`6OMn)4X98%6Ugy^X');
define('NONCE_KEY',        '$t],AR5F[_9la^7rI<G}B@{:}27SI;v/B1:$UCpOO{8/Q:0-wjZ7)J*Me1mZn#ZX');
define('AUTH_SALT',        ' M_KGlO BF8-W>LP-0IF-,U>Ee 5T+-*,K8~H7^Z%Y-(^T[w>$8[cqK7=PkkGO4G');
define('SECURE_AUTH_SALT', 'W7&Qpl:6JG~@2^7.+ajz-C{f{8,7p&2Qu7B/b/fWPR|T9eIu(x#jV[+nY#=5M+oN');
define('LOGGED_IN_SALT',   '-~k=f/A6<}m/5+t-B#YaPyLzXZ43PU5l+H@to=/gHjU6BpL5<-%;KZ@7YFv?,SqM');
define('NONCE_SALT',       'Wx}{^AB2-.QUDsm|)26(e5N,{P*YxKB!VB%c1C)ooiRoyu-&Lu}m<>Km-qFSh/n~');

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
