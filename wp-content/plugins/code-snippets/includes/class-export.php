<?php

/**
 * This file handles exporting snippets in XML format
 *
 * It's better to call the $code_snippets->export_php()
 * method than directly using this class
 *
 * @since      1.9
 * @package    Code_Snippets
 * @subpackage Export
 */

if ( ! class_exists( 'Code_Snippets_Export' ) ) :

/**
 * Exports selected snippets to a XML or PHP file.
 *
 * @since  1.3
 * @param  array  $ids    The IDs of the snippets to export
 * @param  string $format The format of the export file
 * @return void
 */
class Code_Snippets_Export {

	/**
	 * The IDs
	 * @var array
	 */
	public $snippet_ids = array();

	/**
	 * The name of the table to fetch snippets from
	 * @var string
	 */
	protected $table_name = '';

	/**
	 * Constructor function
	 * @param array  $ids   The IDs of the snippets to export
	 * @param string $table The name of the table to fetch snippets from
	 */
	function __construct( $ids, $table ) {
		$this->snippet_ids = (array) $ids;
		$this->table_name = $table;
		$this->exclude_fields = apply_filters( 'code_snippets/export/exclude_from_export', array( 'id', 'active' ) );
	}

	/**
	 * Build the export file name
	 * @return string
	 */
	function get_filename() {
		global $code_snippets;

		if ( 1 == count( $this->snippet_ids ) ) {
			/* If there is only snippet to export, use its name instead of the site name */
			$snippet  = $code_snippets->get_snippet( $this->snippet_ids[0] );
			$sitename = strtolower( $snippet->name );
		} else {
			/* Otherwise, use the site name as set in Settings > General */
			$sitename = strtolower( get_bloginfo( 'name' ) );
		}

		/* Filter and sanitize the filename */
		$filename = sanitize_file_name( apply_filters(
			'code_snippets/export/filename',
			"{$sitename}.code-snippets.xml",
			$sitename
		) );

		return $filename;
	}

	/**
	 * Set HTTP headers and render the file header
	 */
	protected function do_header() {
		global $code_snippets;
		header( 'Content-Type: text/xml; charset=' . get_bloginfo('charset') );

		echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";

			?>
	<!-- This is a code snippets export file generated by the Code Snippets WordPress plugin. -->
	<!-- http://wordpress.org/plugins/code-snippets -->

	<!-- To import these snippets a WordPress site follow these steps: -->
	<!-- 1. Log in to that site as an administrator. -->
	<!-- 2. Install the Code Snippets plugin using the directions provided at the above link. -->
	<!-- 3. Go to 'Tools: Import' in the WordPress admin panel. -->
	<!-- 4. Click on the "Code Snippets" importer in the list -->
	<!-- 5. Upload this file using the form provided on that page. -->
	<!-- 6. Code Snippets will then import all of the snippets and associated information -->
	<!--    contained in this file into your site. -->
	<!-- 7. You will then have to visit the 'Snippets: Manage' admin menu and activate desired snippets -->

	<?php

		/* Run the generator line through the standard WordPress filter */
		$gen  = sprintf (
			'<!-- generator="Code Snippets/%s" created="%s" -->',
			$code_snippets->version,
			date('Y-m-d H:i')
		);
		$type = 'code_snippets_export';
		echo apply_filters( "get_the_generator_$type", $gen, $type );

		/* Begin the file */
		echo '<snippets>';

	}

	/**
	 * Render the items
	 */
	protected function do_items() {
		global $wpdb;

		/* Loop through the snippets */

		foreach ( $this->snippet_ids as $id ) {

			/* Grab the snippet from the database */
			$snippet = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE id = %d", $id ), ARRAY_A );

			/* Remove slashes */
			$snippet = stripslashes_deep( $snippet );

			/* Output the item */
			$this->do_item( $snippet );
			do_action( 'code_snippets/export/after_snippet', $id, $this->get_filename() );
		}
	}

	/**
	 * Render a single item
	 * @param array $snippet
	 */
	protected function do_item( $snippet ) {
		echo "\n\t" . '<snippet>';

		foreach ( $snippet as $field => $value ) {

			/*  Don't export certain fields */
			if ( in_array( $field, $this->exclude_fields ) )
				continue;

			/* Output the field and value as indented XML */
			if ( $value = apply_filters( "code_snippets/export/$field", $value ) ) {
				$value = htmlspecialchars( $value );
				echo "\n\t\t<$field>$value</$field>";
			}
		}
		echo "\n\t" . '</snippet>';
	}

	/**
	 * Render the file footer
	 */
	protected function do_footer() {
		echo "\n</snippets>";
	}

	/**
	 * Export the snippets
	 */
	public function do_export() {

		/* HTTP header */
		$filename = $this->get_filename();
		header( 'Content-Disposition: attachment; filename=' . $filename );

		/* File header */
		$this->do_header();
		do_action( 'code_snippets/export/after_header', $this->snippet_ids, $filename );

		/* Items */
		$this->do_items();
		do_action( 'code_snippets/export/after_snippets', $this->snippet_ids, $filename );

		/* Footer */
		$this->do_footer();
		do_action( 'code_snippets/export/after_footer', $this->snippet_ids, $filename );

		exit;
	}
}

endif; // function exists check
