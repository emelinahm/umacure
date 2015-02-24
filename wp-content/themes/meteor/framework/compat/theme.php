<?php

function get_term_parents( $id, $taxonomy, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
	$chain = '';
	$parent = &get_term( $id, $taxonomy );
	if ( is_wp_error( $parent ) )
		return $parent;

	if ( $nicename )
		$name = $parent->slug;
	else
		$name = $parent->name;

	if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
		$visited[] = $parent->parent;
		$chain .= get_term_parents( $parent->parent, $parent->taxonomy, $link, $separator, $nicename, $visited );
	}

	if ( $link )
		$chain .= '<a href="' . esc_url( get_term_link( $parent ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", "theme"), $parent->name ) ) . '">'.$name.'</a>' . $separator;
	else
		$chain .= $name.$separator;

	return $chain;
}

function __meteor_menu_page() {
  $args = func_get_args();
  return call_user_func_array("add_menu_page", $args);
}

function __meteor_submenu_page() {
  $args = func_get_args();
  return call_user_func_array("add_submenu_page", $args);
}

if (false) {
  
  paginate_links();
  the_post_thumbnail();
  add_theme_support('custom-header', $args);
  add_theme_support('custom-background', $args);
  paginate_comments_links();
  language_attributes();
  comment_form();
  wp_link_pages();

}

?>