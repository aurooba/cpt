<?php

namespace Aurooba;

/**
 * A small helper class for registering Custom Post Types and their taxonomies.
 */
class CPT {

	/**
	 * Cleaned up Post Type Name
	 *
	 * @var string
	 */
	public $post_type_name;

	/**
	 * Provided post type arguments
	 *
	 * @var array
	 */
	public $post_type_args;

	/**
	 * Provided Post Type Labels
	 *
	 * @var array
	 */
	public $post_type_labels;

	/**
	 * Associative array of regex rules for creating plurals
	 *
	 * @var array
	 */
	public static $plural_rules = array(
		'/^(ox)$/i'                      => '$1en',
		'/([m|l])ouse$/i'                => '$1ice',
		'/(matr|vert|ind)ix|ex$/i'       => '$1ices',
		'/(x|ch|ss|sh)$/i'               => '$1es',
		'/([^aeiouy]|qu)y$/i'            => '$1ies',
		'/(hive)$/i'                     => '$1s',
		'/(?:([^f])fe|([lr])f)$/i'       => '$1$2ves',
		'/(shea|lea|loa|thie)f$/i'       => '$1ves',
		'/sis$/i'                        => 'ses',
		'/([ti])um$/i'                   => '$1a',
		'/(tomat|potat|ech|her|vet)o$/i' => '$1oes',
		'/(bu)s$/i'                      => '$1ses',
		'/(alias)$/i'                    => '$1es',
		'/(octop)us$/i'                  => '$1i',
		'/(ax|test)is$/i'                => '$1es',
		'/(us)$/i'                       => '$1es',
		'/s$/i'                          => 's',
		'/$/'                            => 's',
	);

	/**
	 * Initializes class, cleans up the provided name, and sets variables
	 *
	 * @param string $name
	 * @param array $args
	 * @param array $labels
	 */
	public function __construct( string $name, $args = array(), $labels = array() ) {

		$this->post_type_name   = strtolower( str_replace( ' ', '_', $name ) );
		$this->post_type_args   = $args;
		$this->post_type_labels = $labels;

		/**
		 * Register the Post Type if it doesn't exist
		 */
		if ( ! post_type_exists( $this->post_type_name ) ) {
			add_action( 'init', array( &$this, 'register_post_type' ) );
		}

	}

	/**
	 * Register a post type by merging provided arguments with defaults
	 *
	 * @return void
	 */
	public function register_post_type() {
		//Capitalize and create plural term
		$name      = ucwords( str_replace( '_', ' ', $this->post_type_name ) );
		$plural    = self::smart_plural( $name );
		$lc_plural = strtolower( $plural );

		// Merge provided labels with defaults, preferring the provided labels
		$labels = array_merge(
			array(
				'name'               => $plural,
				'name_admin_bar'     => $name,
				// translators: this is the single name of the post type
				'singular_name'      => $name,
				// translators: add new
				'add_new'            => sprintf( _x( 'Add New', 'add new' ) ),
				// translators: single post type name, add new
				'add_new_iem'        => sprintf( _x( 'Add New %s', 'add new' ), $name ),
				'edit_item'          => __( 'Edit ' . $name ),
				'update_item'        => __( 'View ' . $name ),
				'new_item'           => __( 'New ' . $name ),
				'all_items'          => __( 'All ' . $plural ),
				'view_item'          => __( 'View ' . $name ),
				'search_items'       => __( 'Search ' . $plural ),
				'not_found'          => __( 'No ' . $lc_plural . ' found' ),
				'not_found_in_trash' => __( 'No ' . $lc_plural . ' found in Trash' ),
				'parent_item_colon'  => '',
				'menu_name'          => $plural,
			),
			// Provided labels
			$this->post_type_labels
		);

		// Merge provided arguments with defaults, preferring the provided arguments
		$args = array_merge(
			array(
				'label'               => $plural,
				'labels'              => $labels,
				'public'              => true,
				'show_ui'             => true,
				'menu_icon'           => 'dashicons-screenoptions',
				'menu_position'       => 21,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'show_in_rest'        => true,
				'publicly_queryable'  => true,
				'supports'            => array( 'title', 'editor', 'author' ),
				'show_in_nav_menus'   => true,
				'capability_type'     => 'post',
			),
			// Provided args
			$this->post_type_args
		);

		// Register the post type
		register_post_type( $this->post_type_name, $args );
	}

	/**
	 * Attach taxonomy to Post Type.
	 *
	 * If the taxonomy exists, simply attach it, if it doesn't exist,
	 * create it by merging provided parameters with defaults, and then attach it.
	 *
	 * @param string $name
	 * @param array  $args
	 * @param array  $labels
	 * @return void
	 */
	public function add_taxonomy( string $name, $args = array(), $labels = array() ) {
		if ( ! empty( $name ) ) {

			$post_type_name = $this->post_type_name;

			// Taxonomy parameters
			$taxonomy_name   = strtolower( str_replace( ' ', '_', $name ) );
			$taxonomy_labels = $labels;
			$taxonomy_args   = $args;

			if ( ! taxonomy_exists( $taxonomy_name ) ) {
				//Capitalize the words and create plural term
				$name   = ucwords( str_replace( '_', ' ', $name ) );
				$plural = self::smart_plural( $name );

				// Merge provided labels with defaults, preferring the provided labels
				$labels = array_merge(
					array(
						'name'              => _x( $plural, 'taxonomy general name' ),
						'singular_name'     => _x( $name, 'taxonomy singular name' ),
						'search_items'      => __( 'Search ' . $plural ),
						'all_items'         => __( 'All ' . $plural ),
						'parent_item'       => __( 'Parent ' . $name ),
						'parent_item_colon' => __( 'Parent ' . $name . ':' ),
						'edit_item'         => __( 'Edit ' . $name ),
						'update_item'       => __( 'Update ' . $name ),
						'add_new_item'      => __( 'Add New ' . $name ),
						'new_item_name'     => __( 'New ' . $name . ' Name' ),
						'menu_name'         => __( $plural ),
					),
					// Given labels
					$taxonomy_labels
				);

				// Merge provided arguements with defaults, preferring the provided arguments
				$args = array_merge(
					array(
						'label'             => $plural,
						'labels'            => $labels,
						'public'            => true,
						'hierarchical'      => true,
						'query_var'         => true,
						'show_ui'           => true,
						'show_in_menu'      => true,
						'show_admin_column' => true,
						'show_in_rest'      => true,
					),
					// Provided args
					$taxonomy_args
				);

				// Add the taxonomy to the post type
				add_action(
					'init',
					function() use ( $taxonomy_name, $post_type_name, $args ) {
						register_taxonomy( $taxonomy_name, $post_type_name, $args );
					}
				);
			} else {
				add_action(
					'init',
					function() use ( $taxonomy_name, $post_type_name ) {
						register_taxonomy_for_object_type( $taxonomy_name, $post_type_name );
					}
				);
			}
		}
	}

	/**
	 * Intelligently return plural of the single provided term
	 *
	 * @param string $name
	 * @return void
	 */
	protected function smart_plural( string $name ) {
		$trimmed_name = trim( $name );

		$exceptions = self::exceptions();

		if ( array_key_exists( $trimmed_name, $exceptions ) ) {
			return $exceptions[ $trimmed_name ];
		}

		foreach ( self::$plural_rules as $pattern => $rule ) {
			if ( preg_match( $pattern, $trimmed_name ) ) {
				return preg_replace( $pattern, $rule, $trimmed_name );
			}
		}

		return $trimmed_name;
	}

	/**
	 * Return an associative array of irregular plurals
	 *
	 * @return array
	 */
	protected function exceptions() {
		return array(
			'addendum' => 'addenda',
			'analysis' => 'analyses',
			'child'    => 'children',
			'goose'    => 'geese',
			'locus'    => 'loci',
			'oasis'    => 'oases',
			'ovum'     => 'ova',
			'man'      => 'men',
			'tooth'    => 'teeth',
			'woman'    => 'women',
			'quiz'     => 'quizzes',
			'move'     => 'moves',
			'foot'     => 'feet',
			'sex'      => 'sexes',
			'person'   => 'people',
			'valve'    => 'valves',
		);
	}

}
