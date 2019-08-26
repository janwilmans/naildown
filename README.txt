=== Plugin Name ===
Contributors: @janwilmans
Donate link: https://twitter.com/janwilmans
Tags: markdown
Requires at least: 3.0.1
Tested up to: 5.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds the [naildown url="/some.md"] shortcode that enabled you to embed markdown into existing wordpress pages

== Description ==

Usage: 

[naildown-pretty]                   // this adds prettify syntax highlighting for fenced code-blocks 

for example, if the markdown contains:
    ```cpp
         void foo() {}
    ```

it will render the code block with c++ syntax highlighting                                            `

[naildown url="/some.md"] 
[naildown url="/someother.md"]

== More details

see https://github.com/janwilmans/naildown/blob/master/documentation/usage.md

== Installation ==

1. Upload the `naildown-plugin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[naildown url="/some.md"]` and optionall `[naildown-pretty] ` before it, in your posts or pages.

== Frequently Asked Questions ==

What is the matrix?

Unfortunately, no one can be told what the matrix is, you have to see it for yourself.

