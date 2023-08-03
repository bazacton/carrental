<?php
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
define('DB_NAME', 'u312518386_carrental');

/** MySQL database username */
define('DB_USER', 'u312518386_carrental');

/** MySQL database password */
define( 'DB_PASSWORD', 'Ah2o@[dDzTG=' );

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD','direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Z#v-ILmCXhtB|~cTrh4`4I-KE5-b-rP08(kH(R[9Ed%NmU+:BX0szZr}0&=5,|f[');
define('SECURE_AUTH_KEY',  'w%TI!lbKYK5Xy@1kh9rq*+G(&^yHdQ|NXf(:C()*~:pYqN^%h21#4ja~Rxy|I)ZG');
define('LOGGED_IN_KEY',    '|F>QkZUB,Y,u/LJvkN;j]lv{J-R+{8a*(e#:VHb$YTm!k^~n_.0S?8?me@+%MBA!');
define('NONCE_KEY',        'wOUx!_`;X2S@4kXIRC~[X+|.]fkKv|}o?D/IYzj[WDoxB7Zs]J=xzlfl#+* eFh&');
define('AUTH_SALT',        ';k[ICl5YEL2v!$.QY$Z4YcnqLn==CDZP B!T=+IhO/[}-oj i]In)]gyBjjx;TV?');
define('SECURE_AUTH_SALT', 'bY%sp`~pehZ6J_u^?nsVWx MC^[g6cv4^>d`@z%rRVV5jRZO1z(|TPpip~U`MyI@');
define('LOGGED_IN_SALT',   '-:?gssc>ho !>-u^N6N$]imc]%6S0Ia92y`mY>;cR+Rf<*[|]NbpeE%x/ZLy+h;,');
define('NONCE_SALT',       '8HS*1t08q_=`#.}InQqE0!HQbzH[uea^?p1b!T8S3opQ98Msy5WJlLf|@m/FEhy$');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpcarrental_';

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
