<?php

namespace RusAggression\DocumentCPT;

final class DocumentPostType {
	public static function register(): void {
		if ( post_type_exists( 'document' ) ) {
			return;
		}

		$labels = [
			'name'               => _x( 'Documents', 'post type general name', 'doc-cpt' ),
			'singular_name'      => _x( 'Document', 'post type singular name', 'doc-cpt' ),
			'menu_name'          => _x( 'Documents', 'admin menu', 'doc-cpt' ),
			'add_new'            => _x( 'Add New', 'database', 'doc-cpt' ),
			'add_new_item'       => __( 'Add New Document', 'doc-cpt' ),
			'edit_item'          => __( 'Edit Document', 'doc-cpt' ),
			'new_item'           => __( 'New Document', 'doc-cpt' ),
			'view_item'          => __( 'View Document', 'doc-cpt' ),
			'search_items'       => __( 'Search Documents', 'doc-cpt' ),
			'not_found'          => __( 'No documents found', 'doc-cpt' ),
			'not_found_in_trash' => __( 'No documents found in trash', 'doc-cpt' ),
		];

		$args = [
			'labels'               => $labels,
			'public'               => true,
			'has_archive'          => true,
			'publicly_queryable'   => true,
			'query_var'            => true,
			'rewrite'              => [ 'slug' => 'document' ],
			'capability_type'      => 'post',
			'hierarchical'         => false,
			'supports'             => [ 'title' ],
			'menu_position'        => 5,
			'menu_icon'            => 'dashicons-media-document',
			'show_in_rest'         => true,
			'rest_base'            => 'documents',
			'taxonomies'           => [ 'post_tag' ],
			'register_meta_box_cb' => function () {
				add_meta_box(
					'document_details',
					__( 'Document Details', 'doc-cpt' ),
					[ Admin::get_instance(), 'document_meta_box_callback' ],
					'document',
					'normal',
					'high'
				);
			},
		];

		register_post_type( 'document', $args );
	}

	public static function unregister(): void {
		unregister_post_type( 'document' );
	}
}
