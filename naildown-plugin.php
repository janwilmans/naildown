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
if (!defined('WPINC')) {
    die;
}

/*
    Features:
        - transforms relative paths from linked documents to absolute paths 
        - GitHub flavored markdown 
        - syntax highlighting of fenced blocks
    
 * add embedding anchors to headings (H2?) so you can link directly to a sub-chapter ?
 * add filter on section/anchor in url, so you can embed a chapter of a remote markdown file ? or all chapters starting from a heading?
   This can be used to have a different intro on github vs wordpress but share the rest of the content, for example to have a different excerpt ?
 * add/embed https://github.com/google/code-prettify so its hosted locally.
 * add support for 
 *
 
(empty line)
[comment]: # (naildown@section-start)
[comment]: # (naildown@section-end)

 */

require __DIR__ . '/vendor/autoload.php';
require_once 'phpuri.php';

add_shortcode('naildown-pretty', 'embed_prettify');
add_shortcode('naildown', 'embed_markdown');

// https://github.com/janwilmans/depcharter/blob/master/README.md
// https://github.com/janwilmans/depcharter/raw/master/README.md

/*
use DOMDocument;
$doc = new DOMDocument();
@$doc->loadHTML($html, LIBXML_HTML_NODEFDTD);
foreach (['img','a'] as $tag) {
    $nodes = $doc->getElementsByTagName($tag);
    foreach ($nodes as $node) {
       ... do stuff here
    }
}
*/

/*

This can be added to Apperence->Customize->Customize CSS to make the code block render more compact:

pre {
	padding: 0px;
	padding-top: 5px;
	border-radius: 5px;
	font-size: 80%;
}

code.prettyprint {
	line-height: 1.2;
}

li.L0, li.L1, li.L2, li.L3, li.L4,
li.L5, li.L6, li.L7, li.L8, li.L9{
  list-style-type: decimal !important;
	color: #ababab;
}

*/

// test the regular expression at https://regex101.com/r/AwnWuS/12
function absolutify($url, $page)
{
    $pattern = '/(?:src|action|href)=[\'"]\K\/(?!\/)[^\'"]*/';
    $dirname = dirname($url);
    $out =  preg_replace($pattern, "${dirname}/$0", $page);
    return $out;
}

class NaildownParser extends \cebe\markdown\GithubMarkdown
{
    protected function consumeFencedCode($lines, $current)
    {
        // create block array
        $block = [
            'fencedCode',
            'content' => [],
        ];
        $line = rtrim($lines[$current]);

        // detect language and fence length (can be more than 3 backticks)
        $fence = substr($line, 0, $pos = strrpos($line, '`') + 1);
        $language = substr($line, $pos);
        if (!empty($language)) {
            $block['language'] = $language;
        }

        // consume all lines until ```
        for ($i = $current + 1, $count = count($lines); $i < $count; $i++) {
            if (rtrim($line = $lines[$i]) !== $fence) {
                $block['content'][] = $line;
            } else {
                // stop consuming when code block is over
                break;
            }
        }
        return [$block, $i];
    }

    // assign prettyprint class to <code> blocks
    protected function renderFencedCode($block)
    {
        $class = isset($block['language']) ? ' class="prettyprint linenums lang-' . $block['language'] . '"' : '';
        return "<pre><code$class>" . htmlspecialchars(implode("\n", $block['content']) . "\n", ENT_NOQUOTES, 'UTF-8') . '</code></pre>';
    }
}

function embed_prettify($args, $content = null)
{
    return '<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js"></script>';
}


function transform_markdown_to_html($page)
{
    $parser = new NaildownParser();
    return $parser->parse($page);
}


function get_lines($string)
{
    return preg_split("/(\r\n|\n|\r)/", $string);
}


function contains($haystack, $needle)
{
    return strpos($haystack, $needle) !== false;
}


function crop_to_naildown_section($page)
{
    $result = array();
    $search_for_begin = true;
    $section_begin = 0;

    $lines = get_lines($page);
    foreach ($lines as $i=>$line) 
    {
        if (contains($line, 'naildown@section'))
        {
            if ($search_for_begin)
            {
                $section_begin = $i + 1;
                $search_for_begin = false;
            }
            else
            {
                $section_end = $i;
                $length = $section_end - $section_begin;
                $result = array_merge($result, array_slice($lines, $section_begin, $length));
                $search_for_begin = true;
            }
        }
    }
    
    if ($search_for_begin == false)
    {
        $result = array_merge($result, array_slice($lines, $section_begin));
    }
    return implode("\n", $result);
}

function embed_markdown($args, $content = null)
{
    if (!array_key_exists('url', $args)) {
        echo "naildown: url attribute missing from shortcode?";
        return;
    }
    $url = $args['url'];

    $page = file_get_contents($url);
    if ($page == null) {
        echo "naildown error: [could not fetch: " . $url . "]";
        return;
    }
    return absolutify($url, transform_markdown_to_html(crop_to_naildown_section($page)));
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('NAILDOWN_PLUGIN_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-naildown-plugin-activator.php
 */
function activate_naildown_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-naildown-plugin-activator.php';
    Naildown_Plugin_Activator::activate();

    update_option('naildown', 1);
    add_option('naildown', 0);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-naildown-plugin-deactivator.php
 */
function deactivate_naildown_plugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-naildown-plugin-deactivator.php';
    Naildown_Plugin_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_naildown_plugin');
register_deactivation_hook(__FILE__, 'deactivate_naildown_plugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-naildown-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_naildown_plugin()
{

    $plugin = new Naildown_Plugin();
    $plugin->run();

}
run_naildown_plugin();
