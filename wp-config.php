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
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '(:gZJn4py{E1`!/vt6(LtUG jq6AGqx~[^BsSyx]-ab<t.ym{bCVLWgp(X6y1Vv!' );
define( 'SECURE_AUTH_KEY',  '>3X:yR?>.G|=x8$@EN8Q.3R|:nNmz=B/{QBQm.>vPxdXz]x&.CEd4=4dbQ]ViKGO' );
define( 'LOGGED_IN_KEY',    '$ B- ;{d%;iikVKLG5Eo>gP/5l<QH&!OqS 3],S(NDP=yxEk6?A&M#$ZZctZvH*F' );
define( 'NONCE_KEY',        'sH:Dx)!^[.)^gs;Ao]U#N~5x4 |1?{+UgJ[xC|[y?e[oxk!u9)-C6yjf^F&Y[:+Z' );
define( 'AUTH_SALT',        '^&0)%Zl<C*M+khSDDw]:T&Xpjzv1n&5:zG<.)E<UZQ]&hGX1ZG/u@Gkxs-sJ)L5o' );
define( 'SECURE_AUTH_SALT', '(Wv3tM?WrG.{]c5j&5tLOiX9)]eVThb Vleu)dPL)|`?(g+_%OrqGk,bM+:La.0D' );
define( 'LOGGED_IN_SALT',   '0+-!A]Hy:y2H>E~%z/^n~ukd#2rL8krfl^:xRoWz|~{v+H:]V!`ja9E52U/Dm.y{' );
define( 'NONCE_SALT',       'BB#+2p:_npST<>[k`k/`qR)q-B.3!Y(]tiGz?FT[,!xuhuI`ng`Tb4x#G4 <k2N^' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
