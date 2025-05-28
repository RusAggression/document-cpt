<?php

namespace RusAggression\DocumentCPT;

use WP_Post;

final class Admin {
	/** @var self|null */
	private static $instance;

	public static function get_instance(): self {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->init();
	}

	public function init(): void {
		add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	public function admin_init(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_filter( 'wp_insert_post_data', [ $this, 'wp_insert_post_data' ] );
		add_action( 'save_post_document', [ $this, 'save_post_document' ] );
	}

	public function admin_enqueue_scripts( string $hook ): void {
		/** @var WP_Post */
		global $post;

		if ( 'document' === $post->post_type && ( 'post-new.php' === $hook || 'post.php' === $hook ) ) {
			wp_enqueue_media();
		}
	}

	public function document_meta_box_callback( WP_Post $post ): void {
		$doc_id = (string) get_post_meta( $post->ID, '_document_doc_id', true );
		if ( $doc_id ) {
			$url = get_attachment_link( (int) $doc_id );
		} else {
			$doc_id = 0;
			$url    = '';
		}

		$params = [
			'id'          => $post->ID,
			'description' => get_post_field( 'post_content', $post->ID, 'edit' ),
			'doc_id'      => $doc_id,
			'doc_url'     => $url,
			'doc_author'  => (string) get_post_meta( $post->ID, '_document_author', true ),
			'doc_date'    => (string) get_post_meta( $post->ID, '_document_date', true ),
		];

		self::render( 'document-metabox', $params );
	}

	public function wp_insert_post_data( array $data ): array {
		if ( ! isset( $_POST['document_meta_box_nonce'] ) ||
			! is_string( $_POST['document_meta_box_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( $_POST['document_meta_box_nonce'] ), 'document_meta_box' )
		) {
			return $data;
		}

		if ( isset( $_POST['document_description'] ) && is_string( $_POST['document_description'] ) ) {
			$data['post_content'] = wp_kses_post( $_POST['document_description'] );
		}

		return $data;
	}

	public function save_post_document( int $post_id ): void {
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
			! current_user_can( 'edit_post', $post_id ) ||
			! isset( $_POST['document_meta_box_nonce'] ) ||
			! is_string( $_POST['document_meta_box_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( $_POST['document_meta_box_nonce'] ), 'document_meta_box' )
		) {
			return;
		}

		if ( isset( $_POST['document_doc_id'] ) && is_numeric( $_POST['document_doc_id'] ) ) {
			$doc_id = (int) $_POST['document_doc_id'];
			/** @var WP_Post|null */
			$attachment = get_post( $doc_id );
			if ( $attachment && 'attachment' === $attachment->post_type ) {
				update_post_meta( $post_id, '_document_doc_id', $doc_id );
			} else {
				delete_post_meta( $post_id, '_document_doc_id' );
			}
		}

		if ( isset( $_POST['document_author'] ) && is_string( $_POST['document_author'] ) ) {
			$author = sanitize_text_field( $_POST['document_author'] );
			update_post_meta( $post_id, '_document_author', $author );
		}

		if ( isset( $_POST['document_date'] ) && is_string( $_POST['document_date'] ) ) {
			$date = sanitize_text_field( $_POST['document_date'] );
			if ( false === strtotime( $date ) ) {
				$date = '';
			}

			update_post_meta( $post_id, '_document_date', $date );
		}
	}

	/**
	 * @psalm-suppress UnusedParam
	 */
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	private static function render( string $template, array $params = [] ): void /* NOSONAR */ {
		/** @psalm-suppress UnresolvableInclude */
		require __DIR__ . '/../views/' . basename( $template ) . '.php'; // NOSONAR
	}
}
