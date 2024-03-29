# NailDown 101

## How I created this plugin

* I started by installed a LAMP ([https://sourceforge.net/projects/xampp/](https://sourceforge.net/projects/xampp/)) basically, giving me a local webserver + mysql server  to play around with the plugin 
* I generated plugin boilerplate using [https://wppb.me/](https://wppb.me/)
* I put the code in `xampp\htdocs\wordpress\wp-content\plugins\naildown-plugin`
* now it showed up wordpress under the admin->plugins menu.
* I activated the naildown plugin and and started fooling about with php code in get_foobar_content().
* enabled debugging mode in `xampp\htdocs\wordpress\wp-config.php` using:
```
// Enable WP_DEBUG mode
define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', true );
```
* Wordpress started writing to `xampp\htdocs\wordpress\wp-content\debug.log` and I tailed this file using `https://github.com/CobaltFusion/DebugViewPP` to see my errors
* I added this code to the main plugin.php file 
```
add_shortcode('foobar', 'get_foobar_content');

function get_foobar_content($args, $content=null)
{
    error_log("<div>FOOBAR!</div>");  // shows up in DebugView++
}
```

* looked around for a markdown libary in php and found [https://github.com/cebe/markdown](https://github.com/cebe/markdown). 
* installed [https://getcomposer.org/](https://getcomposer.org/) and used `composer require cebe/markdown "~1.2.0"` to add `cebe/markdown` to my plugin
* The I added this to naildown-plugin.php:
```
require __DIR__ . '/vendor/autoload.php';
```
to let `cebe/markdown` find and load its classes automagically.

* using the example code on [https://github.com/cebe/markdown/blob/master/README.md](https://github.com/cebe/markdown/blob/master/README.md) and [https://github.com/google/code-prettify/blob/master/README.md](https://github.com/google/code-prettify/blob/master/README.md) fused them together and voila... the plugin was working (modulo ~40 hours of tinkering, making mistakes etc.)
