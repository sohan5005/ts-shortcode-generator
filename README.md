# TS Shortcode Generator
In full meaning, ThemeStones shortcode generator. A Lightweight and handy framework to give your users ease to use your shortcodes. Made only for WordPress developers!!

## Basic usage
Require the bootstrap file, construct the class with proper template and BOOM! Check out the sample files included.

```
require_once( dirname(__FILE__) . '/ts-shortcode-generator/bootstrap.php' );

$template = require_once( dirname(__FILE__) . '/shortcode-template.php' );

new TS_Shortcode_Generator(array(
    'name' => 'ts_sample_shortcode',    // Unique ID of the instance
    'title' => __( 'Sample shortcode generator', 'textdomain' ),    // Title of the popup window
    'author' => 'Theme Stones',     // TinyMCE plugin author
    'website' => 'http://themestones.net/',     // TinyMCE plugin author website
    'icon' => plugins_url( 'assets/img/icon.png', __FILE__ ),     // TinyMCE plugin icon. Must use a file path on server not a URI
    'version' => 1.0,     // TinyMCE plugin version. Not so necessary
    'template' => $template,     // Shortcode template array
));
```
To see a sample, just include the sample file in your project:
```
require_once( dirname(__FILE__) . '/ts-shortcode-generator/sample-init.php' );
```
## Template structure
The template is little strictly formatted but easy to understand. It's just a simple multidimensional PHP array. Like this:

```
array( // the main array
    array( // array of a tab ( shortcode group )
        array(
            // shortcode
        ),
        array(
            // shortcode
        ),
        array(
            // shortcode
        ),
    ),
)
```
The code shown above is NOT A VALID template but to give you a basic idea of how it works. A sample valid template is included with the package so that you can play with it. All things are commented properly.

## Features
- Awesome designed UI
- 13 useful built-in field types
- Fully extendable, you can create your own field types
- Supports nesting/repeating/sortable shortcodes with WP editor
- Multiple instances
- Unlimited level of nesting inside WP Editor

## Field types
Currently there are 13 available filed types:

1. checkbox
2. color
3. date
4. icon
5. input
6. multiselect
7. radio
8. richedit
9. select
10. slider
11. textarea
12. toggle
13. upload

## Custom field types
You can have your own field type. To do that, add a new php file in "\views\fields" directory and add proper markup for the view you want. A few things to remember:

1. Arguments are passed to a field view with $args variable
2. You can only modify the field view, not the title area
3. There must be an input field with "ts-scg-value-collector" class. It can be hidden or visible, but must present inside your view file. If you use other fields there, you must bind the value to this field at the end. This is where the value is collected.

## Update for theme integration
Please define 2 constants before you call the `bootstrap.php` from core. One is relative path on server, the other one is url for js, css & images. Both of the constants must be targeted to the directory containing the `bootstrap.php` file without any backslash at the end. For example:

```
define( 'TS_SCG_PATH', get_template_directory() . '/inc/ts-shortcode-generator' );
define( 'TS_SCG_URL', get_template_directory_uri() . '/inc/ts-shortcode-generator' );
require_once get_template_directory() . '/inc/ts-shortcode-generator/sample-init.php'; // This file loads the bootrap.php automatically.
```
## Where is the documentation?
I'm working on the documentation. I will put a full documentation soon :)

### Your contributions are welcome
If you have idea to improve or add features, you are welcome
