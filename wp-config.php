<?php
define( 'WP_CACHE', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u141005733_oAaAb' );

/** Database username */
define( 'DB_USER', 'u141005733_Pjpyk' );

/** Database password */
define( 'DB_PASSWORD', '8fOFmF5EGY' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '5HJUln:~D2I`h_s=T4Q)V(-F+?$U*aK%!E8`kT]6O!}q8tb:fdB8.{U<[y0hF{r/' );
define( 'SECURE_AUTH_KEY',   '264GV*8A5e3[ge<eTNme}kapWhDJi[|x@/BkhpPe.VIi7/|k]EGv^S/|/ty?c{$H' );
define( 'LOGGED_IN_KEY',     '`s08_uGd4~m7Q|bZL6(iMxggxl0GAF(5a!cj%x{MG/}9SE( )Q.SV?RJIA,ohps.' );
define( 'NONCE_KEY',         '8^;h9.D])w7X_Ug^BkKt)B&eM*kbb}B4K3T6Nb<Q6V7@xI-]S8Iupt/w&FR88j[n' );
define( 'AUTH_SALT',         'Af(;?3ENwiPr-=?H1*02,:9H*w/,<X*hT[Xg|d).; QS.5%gxWN2Btlu]^1UtA+h' );
define( 'SECURE_AUTH_SALT',  '#{Y/!@B@H+UC9@!rbZ[h?+%_=,+Tdk{th+p<~/qmbppF3xoF5yB=W@^y4n&%:H,V' );
define( 'LOGGED_IN_SALT',    'R0#&07>pb&2Nkhdj~=rZ)2/)o4k0VFy6WKxy.wj?f2pP+hz$70NiEFDBQkD-6[pq' );
define( 'NONCE_SALT',        '9g09k}P1}E(p+7R^aINK6w//S+H3`WG>{pSf/U?@<]ScFWN <af`A@..VMSIApSq' );
define( 'WP_CACHE_KEY_SALT', '*M~De*GK3ko${ape6X10LNx4r@3 D~~!B<vxNtJ;*o,Rt.l}k.4XkPEw.|Z$85nu' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
