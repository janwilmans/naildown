<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://twitter.com/janwilmans
 * @since             1.0.0
 * @package           Naildown_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       NailDown
 * Plugin URI:        https://github.com/janwilmans/naildown
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jan Wilmans
 * Author URI:        https://twitter.com/janwilmans
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       naildown-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// fix relative paths in remote markdown files using https://github.com/monkeysuffrage/phpuri 
//todo: add embedding anchors ? 
//todo: add filter on section/anchor in url

require_once 'Michelf/MarkdownExtra.inc.php';
require_once 'phpuri.php';

use \Michelf\MarkdownExtra;

add_shortcode('naildown', 'embed_markdown');

// https://github.com/janwilmans/depcharter/blob/master/README.md
// 
// 
// https://github.com/janwilmans/depcharter/raw/master/README.md

function embed_markdown($args, $content=null)
{ 
  if (!array_key_exists('url', $args)) {
    echo "naildown: url attribute missing from shortcode?";
	return;
  }	
  $url = $args['url'];	

  $prefix = '';
  if (array_key_exists('prefix', $args))
  {
      $prefix = $args['prefix'];	
	  echo "<a href='" . $url . "'>" . $prefix . "</a>";
  }	
	
  $page = file_get_contents($url);
  if ($page == null)
  {
	  //echo "mdurl error: [could not fetch: " . $url . "]";
  }
  else
  {
	  // code_class_prefix = "prettyprint lang-"
    return $prefix . MarkdownExtra::defaultTransform($page);	  
  }	
}



/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NAILDOWN_PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-naildown-plugin-activator.php
 */
function activate_naildown_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-naildown-plugin-activator.php';
	Naildown_Plugin_Activator::activate();
    
    update_option('naildown', 1);
    add_option('naildown', 0);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-naildown-plugin-deactivator.php
 */
function deactivate_naildown_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-naildown-plugin-deactivator.php';
	Naildown_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_naildown_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_naildown_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-naildown-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_naildown_plugin() {

	$plugin = new Naildown_Plugin();
	$plugin->run();

}
run_naildown_plugin();
