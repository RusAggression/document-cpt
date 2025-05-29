<?php

namespace RusAggression\DocumentCPT;

final class DocumentTaxonomy {
	public static function register(): void {
		if ( taxonomy_exists( 'doc' ) ) {
			return;
		}

		$labels = [
			'name'                       => _x( 'Documents', 'Taxonomy General Name', 'doc-cpt' ),
			'singular_name'              => _x( 'Document', 'Taxonomy Singular Name', 'doc-cpt' ),
			'menu_name'                  => __( 'Documents', 'doc-cpt' ),
			'all_items'                  => __( 'All Documents', 'doc-cpt' ),
			'new_item_name'              => __( 'New Document Name', 'doc-cpt' ),
			'add_new_item'               => __( 'Add Document', 'doc-cpt' ),
			'edit_item'                  => __( 'Edit Document', 'doc-cpt' ),
			'update_item'                => __( 'Update Document', 'doc-cpt' ),
			'view_item'                  => __( 'View Document', 'doc-cpt' ),
			'separate_items_with_commas' => __( 'Separate documents with commas', 'doc-cpt' ),
			'add_or_remove_items'        => __( 'Add or remove documents', 'doc-cpt' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'doc-cpt' ),
			'popular_items'              => __( 'Popular Documents', 'doc-cpt' ),
			'search_items'               => __( 'Search Documents', 'doc-cpt' ),
			'not_found'                  => __( 'Not Found', 'doc-cpt' ),
			'no_terms'                   => __( 'No documents', 'doc-cpt' ),
			'items_list'                 => __( 'Documents list', 'doc-cpt' ),
			'items_list_navigation'      => __( 'Documents list navigation', 'doc-cpt' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => false,
			'show_in_rest'      => true,
			'show_tagcloud'     => false,
		];

		register_taxonomy( 'doc', [ 'event' ], $args );
	}

	public static function unregister(): void {
		unregister_taxonomy( 'doc' );
	}
}
