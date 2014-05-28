<?php 
/**
 * Metabox Functions
 *
 * @package     FFW
 * @subpackage  Functions/Slides
 * @copyright   Copyright (c) 2014, Bryan Monzon
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/** All Downloads *****************************************************************/

/**
 * Register all the meta boxes for the Download custom post type
 *
 * @since 1.0
 * @return void
 */
function ffw_slides_add_meta_box() {

    $post_types = apply_filters( 'ffw_slides_metabox_post_types' , array( 'slides' ) );

    foreach ( $post_types as $post_type ) {

        add_meta_box( 'slidesinfo', __( 'Slides Control', 'ffw_slides' ),  'ffw_slides_render_meta_box', $post_type, 'normal', 'high' );

    }
}
add_action( 'add_meta_boxes', 'ffw_slides_add_meta_box' );


/**
 * Sabe post meta when the save_post action is called
 *
 * @since 1.0
 * @param int $post_id Download (Post) ID
 * @global array $post All the data of the the current post
 * @return void
 */
function ffw_slides_meta_box_save( $post_id) {
    global $post;


    if ( ! isset( $_POST['ffw_slides_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['ffw_slides_meta_box_nonce'], basename( __FILE__ ) ) )
        return $post_id;

    if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) )
        return $post_id;

    if ( isset( $post->post_type ) && $post->post_type == 'revision' )
        return $post_id;


    // The default fields that get saved
    $fields = apply_filters( 'ffw_slides_metabox_fields_save', array(
            '_FFW_slide_class',
            '_FFW_slide_remove_text',
            '_FFW_slide_text_alignment'
        )
    );


    foreach ( $fields as $field ) {
        if ( ! empty( $_POST[ $field ] ) ) {
            $new = apply_filters( 'ffw_slides_save_' . $field, $_POST[ $field ] );
            update_post_meta( $post_id, $field, $new );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post', 'ffw_slides_meta_box_save' );



/** Campaign Configuration *****************************************************************/

/**
 * Campaign Metabox
 *
 * Extensions (as well as the core plugin) can add items to the main download
 * configuration metabox via the `ffw_slides_meta_box_fields` action.
 *
 * @since 1.0
 * @return void
 */
function ffw_slides_render_meta_box() {
    global $post;

    do_action( 'ffw_slides_meta_box_fields', $post->ID );
    wp_nonce_field( basename( __FILE__ ), 'ffw_slides_meta_box_nonce' );
}



function ffw_slides_render_fields( $post )
{
    global $post;

    $slide_meta = get_post_meta($post->ID);

    $slides_class       = get_post_meta( $post->ID, '_FFW_slide_class', true );
    $slides_remove_text = get_post_meta( $post->ID, '_FFW_slide_remove_text', true );
    $slides_text_align  = get_post_meta( $post->ID, '_FFW_slide_text_alignment', true );


?>
    <div class="ffw_slides-wrapper">
        <?php do_action( 'ffw_before_slides_meta' ); ?>
        <div id="classDetails" class="classForm">
            <table cellspacing="0" cellpadding="0" id="etm_class_info">
                <tbody>
                    <?php do_action( 'ffw_before_slides_row' ); ?>
                    <tr>
                        <td style="width:30%;"><p><strong>Custom Class</strong></p></td>
                        <td>
                            <label for="_FFW_slide_class">
                            <input tabindex="2001" type="text" name="_FFW_slide_class" id="_FFW_slide_class" value="<?php echo $slides_class; ?>">
                            Enter a custom class
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:30%;"><p><strong>Text Alignment</strong></p></td>
                        <td>
                            <label for="_FFW_slide_class">
                                <select name="_FFW_slide_text_alignment" id="_FFW_slide_text_alignment">
                                    <option value="center" <?php selected( $slides_text_align, 'center' ); ?>>Center</option>
                                    <option value="left" <?php selected( $slides_text_align, 'left' ); ?>>Left</option>
                                    <option value="right" <?php selected( $slides_text_align, 'right' ); ?>>Right</option>
                                </select>

                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:30%;"><p><strong>Hide Text</strong></p></td>
                        <td>
                            <label for="_FFW_slide_remove_text">
                                <input type="checkbox" id="_FFW_slide_remove_text" name="_FFW_slide_remove_text" value="1" <?php checked( 1, $slides_remove_text ); ?>>
                                Check to hide text from the slide
                            </label>
                        </td>
                    </tr>
                    <?php do_action( 'ffw_after_slides_row' ); ?>
                </tbody>
            </table>
        </div>
        <?php do_action( 'ffw_after_slides_meta' ); ?>
    </div>
<?php

}
add_action( 'ffw_slides_meta_box_fields', 'ffw_slides_render_fields', 10 );