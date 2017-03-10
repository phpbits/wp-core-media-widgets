<?php
/**
 * Widget API: WP_Widget_Image class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.8.0
 */

/**
 * Core class that implements an image widget.
 *
 * @since 4.8.0
 *
 * @see WP_Widget
 */
class WP_Widget_Image extends WP_Widget_Media {

	/**
	 * Constructor.
	 *
	 * @since  4.8.0
	 * @access public
	 */
	public function __construct() {
		parent::__construct( 'media_image', __( 'Image' ), array(
			'description' => __( 'Displays an image file.' ),
			'mime_type'   => 'image',
		) );
	}

	/**
	 * Renders a single media attachment
	 *
	 * @since  4.8.0
	 * @access public
	 *
	 * @param WP_Post $attachment Attachment object.
	 * @param string  $widget_id  Widget ID.
	 * @param array   $instance   Current widget instance arguments.
	 *
	 * @return void
	 */
	public function render_media( $attachment, $widget_id, $instance ) {
		$instance    = wp_parse_args( $instance, array( 'size' => 'thumbnail' ) );
		$has_caption = ! empty( $attachment->post_excerpt );

		$image_attributes = array(
			'data-id' => $widget_id,
			'title'   => $attachment->post_title,
			'class'   => 'image wp-image-' . $attachment->ID,
			'style'   => 'max-width: 100%; height: auto;',
		);

		if ( ! $has_caption ) {
			$image_attributes['class'] .= ' ' . $instance['align'];
		}

		$image = wp_get_attachment_image( $attachment->ID, $instance['size'], false, $image_attributes );

		if ( $has_caption ) {
			// [0] => width, [1] => height;
			$size = _wp_get_image_size_from_meta( $instance['size'], wp_get_attachment_metadata( $attachment->ID ) );
			$image = img_caption_shortcode( array(
				'id'      => $widget_id . '-caption',
				'width'   => isset( $size[0] ) ? $size[0] : false,
				'align'   => $instance['align'],
				'caption' => $attachment->post_excerpt,
			), $image );
		}

		echo $image;
	}
}
