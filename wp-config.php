<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
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
define('DB_NAME', 'rezon8_pro');

/** MySQL database username */
define('DB_USER', 'rezon8_pro');

/** MySQL database password */
define('DB_PASSWORD', '5P8pS80.O.');

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
define('AUTH_KEY',         'ajl1tifcnxwcrxjrpmxyhlcoeo1qtbdkkn3yxjem0rqagtghjwzzmbmafsrligwp');
define('SECURE_AUTH_KEY',  'h8enb8gjefas4sz7dfsoeqizoepul5hnup9pu5exlwpsue1kvzsuo8yj6786beup');
define('LOGGED_IN_KEY',    'zw2d4fxq0ytdspzxphrmoepfz8ujrrkw4heikuol7ot2qgbmwfnc4c5xeqyc1vjw');
define('NONCE_KEY',        '5jiccn0n5qt84uxfuzsjukzqec2yt4t29uvtu0qzpge3jqyutklg3nfkvplzbhfl');
define('AUTH_SALT',        'xbqmrd9uyinc5yskjn0syboojzhoskpd4kwtanuavsldfnnwgen4ogamnqvgz6hu');
define('SECURE_AUTH_SALT', 'wsmct90rdp6esue9wlqpfafqhedykcd3uzwo0bhahsfzhgvlatoxowzbey9c3zs5');
define('LOGGED_IN_SALT',   'yibvr0gf8j7k3tcyuueloathmh9d30idtznvs2f0exuweuekdhduo5i27yirjpr5');
define('NONCE_SALT',       'cpsiuldmnfyvvc2coolx3vnglf9tsjjksmygr5vzumzf7zgbyirf5txwcjjtw1yr');

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

/* Memory Settings */
define('WP_MEMORY_LIMIT', '128M');


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
