# Custom Post Type and Taxonomies Generator

Quickly register custom post types and associated taxonomies with intelligent defaults in your WordPress projects.

For complete documentation of Custom Post Type registration, see [https://developer.wordpress.org/reference/functions/register_post_type/](https://developer.wordpress.org/reference/functions/register_post_type/)

For complete documentation of Taxonomy registration, see [https://developer.wordpress.org/reference/functions/register_taxonomy/](https://developer.wordpress.org/reference/functions/register_taxonomy/)

Requires PHP 7 or later.

# Installation

The easiest way is to install this through Composer with:

```bash
composer require aurooba/cpt
```

Make sure you have composer autoload set up where you are adding the package.

For themes, in your `functions.php` file:

```php
require get_template_directory() . '/vendor/autoload.php';
```

For plugins, in your main plugin file, near the top:

```php
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
```

# Usage

Don't see an option you use often and would love for it to be easier too? Open an issue or pop it into [the Discussion area](https://github.com/aurooba/cpt/discussions), let's make it happen. :)

Have a question about your specific use case? Ask in a [support discussion](https://github.com/aurooba/cpt/discussions).

## Basic Usage

```php
$custom_post_type = new Aurooba\CPT( 'custom post type' );
```

The class, by default, takes in just one parameter: a singular name, like `resource`. It's smart enough to convert `resource` into `resources`, `country` into `countries`, `potato` into `potatoes`, `goose` into `geese`, and handles some exceptions as well.

At this time, it is _not_ smart enough to handle uncountable nouns such as `rice`, `tea`, `knowledge`, etc. This is a planned addition, coming later.

## Customized Usage

```php
$custom_post_type = new Aurooba\CPT(
	'singular name', // singular taxonomy name, human readable
	$args, // an array of custom parameters for the custom post type
	$labels // an array of custom labels for the custom post type
);

$custom_post_type->add_taxonomy(
	'Taxonomy', // singular taxonomy name
	// array of custom parameters for the taxonomy
	array(
		'hierarchical' => false,
	),
	$labels, // array of custom labels for the taxonomy
)
```

## Icons

The default icon, set up in the class is the [`screenoptions` Dashicon](https://developer.wordpress.org/resource/dashicons/#screenoptions), purely because I like it.

Feel free to pass a different Dashicon to your class as shown in this [different icon example](#Register-a-custom-post-type-with-a-different-icon).

You can also pass custom SVG icons, as shown in this [custom svg icon example](#Register-a-custom-post-type-with-a-custom-icon).

## Taxonomies

The class can also [attach existing taxonomies](#Attach-an-existing-taxonomy) to your CPT or [generate a new taxonomy to attach to the CPT](#Register-and-associate-a-basic-taxonomy-with-the-Custom-Post-Type).

# Examples

## Register a basic Custom Post Type:

```php
/**
 * Initialize a Resource Custom Post Type
 * @return void
 */
function initialize_cpts() {

	$resource = new Aurooba\CPT( 'resource');

}

add_action( 'init', 'initialize_cpts' );
```

## Register and associate a basic taxonomy with the Custom Post Type:

```php
/**
 * Initialize a Resource Custom Post Type
 * @return void
 */
function initialize_cpts_and_taxonomies() {

	// initialize cpt
	$resource = new Aurooba\CPT( 'resource' );

	// add Resource Type taxonomy
	$resource->add_taxonomy( 'Resource Type' );

}

add_action( 'after_setup_theme', 'initialize_cpts_and_taxonomies' );
```

## Register a Custom Post Type with a different icon

```php
$resource = new Aurooba\CPT(
		'resource',
		array( 'menu_icon' => 'dashicons-share-alt' ),
	);
```

## Register a Custom Post Type with a custom icon

```php

// Your icon SVG Code. Alternatively, you can pass the path to an SVG file.
$resource_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="#ffffff" d="M608 0H160c-17.67 0-32 13.13-32 29.33V112h48V48h48v64h48V48h224v304h112c17.67 0 32-13.13 32-29.33V29.33C640 13.13 625.67 0 608 0zm-16 304h-48v-56h48zm0-104h-48v-48h48zm0-96h-48V48h48zM128 320a32 32 0 1 0-32-32 32 32 0 0 0 32 32zm288-160H32a32 32 0 0 0-32 32v288a32 32 0 0 0 32 32h384a32 32 0 0 0 32-32V192a32 32 0 0 0-32-32zm-16 240L299.31 299.31a16 16 0 0 0-22.62 0L176 400l-36.69-36.69a16 16 0 0 0-22.62 0L48 432V208h352z"/></svg>';

$resource = new Aurooba\CPT(
		'resource',
		array(
			'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode( $resource_icon ) ),
	);
```

## Create a customized Custom Post Type:

```php
/**
 * Initialize a Resource Custom Post Type
 * @return void
 */
function initialize_cpts() {

	// create a block template to include in the custom post type
	$block_template = array(
		array(
			'core/heading',
			array(
				'level'       => 2,
				'placeholder' => __( 'Resource Type', 'textdomain' ),
			),
		),
		array(
			'core/paragraph',
			array(
				'content' => __( 'Places where this resource can be helpful are:>', 'textdomain' ),
			),
		),
	);

	$resource = new Aurooba\CPT(
		'resource',
		array(
			'menu_position' => 26,
			'menu_icon'     => 'dashicons-plus-alt',
			'template'      => $block_template,
			'rewrite'       => true,
			'capability_type' => 'resource',
			'map_meta_cap' => true,
			'supports'      => array(
				'title',
				'editor',
				'thumbnail',
				'author',
				'custom-fields',
			),
		),
	);

}

add_action( 'after_setup_theme', 'initialize_cpts' );

```

## Create a customized taxonomy:

```php
	$resource->add_taxonomy(
		'Resource Type',
		array(
			'capabilities' => array(
				'manage_terms' => 'manage_resource_type',
				'edit_terms'   => 'edit_resource_type',
				'delete_terms' => 'delete_resource_type',
				'assign_terms' => 'assign_resource_type',
			),
		),
	);

```

## Attach an existing taxonomy:

```php
$resource->add_taxonomy( 'Post Tag' );
$resource->add_taxonomy( 'Category' );
```

# License

Created by Aurooba Ahmed. Licensed under the terms of the GPL v2 or later.
