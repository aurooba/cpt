# Custom Post Type and Taxonomies Generator

Quickly register custom post types and associated taxonomies with intelligent defaults in your WordPress projects.

For complete documentation of Custom Post Type registration, see [https://developer.wordpress.org/reference/functions/register_post_type/](https://developer.wordpress.org/reference/functions/register_post_type/)

For complete documentation of Taxonomy registration, see [https://developer.wordpress.org/reference/functions/register_taxonomy/](https://developer.wordpress.org/reference/functions/register_taxonomy/)

Requires PHP 7 or later.

# Installation

The easiest way is to nstall this through Composer with:
`composer require aurooba/cpt`

Make sure you have composer autoload set up where you are adding package.

For themes, in your `functions.php` file:

```php
require require get_template_directory() . '/vendor/autoload.php';
```

For plugins, in your main plugin file, near the top:

```php
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
```

# Usage

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

add_action( 'init', 'initialize_cpts_and_taxonomies' );
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

add_action( 'init', 'initialize_cpts' );

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

# License

Created by Aurooba Ahmed. Licensed under the terms of the GPL v2 or later.
