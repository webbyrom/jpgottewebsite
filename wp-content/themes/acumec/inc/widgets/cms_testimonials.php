<?php defined( 'ABSPATH' ) or exit();
/**
 * 
 * This widget allows you to pick image and show at the front-end with some options
 *
 * @author knightdev
 * @version 1.0
 */
if (!function_exists('register_ef4_widget')) return;
class Cms_Testimonials_Widget extends WP_Widget {
    /**
     * Constructor
     *
     * @return void
     **/
    function __construct() {
        parent::__construct(
            'cms_testimonials', // Base ID
            esc_html__( 'Cms Testimonials', 'acumec' ), // Name
            array(
                'customize_selective_refresh' => true
            ) // Args
        );
    }


    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance )
    {
        extract( $args, EXTR_SKIP );
        
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'image' => '',
                'link' => '',
                'name'  => '',
                'position'  => '',
                'description' => '',
            )
        );

        echo wp_kses_post($before_widget);

        if ( ! empty( $instance['image'] ) ) {
            $image = ( is_numeric( $instance['image'] ) && $instance['image'] > 0 ) ? intval( $instance['image'] ) : 0;
            if ( $image > 0 ) {     
                $alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
                $alt = empty( $alt ) ? '' : strval( $alt );
                $image_src = wp_get_attachment_image_src( $image, 'thumbnail' );
            }
        }
        echo '<div class= "content">';
        if ( ! empty( $instance['title'] ) || !empty($instance['link']) || !empty($instance['description'])){
            echo '<div class = "text">'; 
            if ( ! empty( $title ) ) {
                echo wp_kses_post($before_title . $title . $after_title);
            } 
            if ( ! empty( $instance['title'] ) ) {
                echo '<h3 class="wg-title">'.$instance['title'].'</h3>';
            }

            if ( ! empty( $instance['description'] ) )
            {
                echo '<div class="description">'.$instance['description'].'</div>';
            }
            if(!empty($instance['image']) || !empty($instance['name']) || !empty($instance['position'])) {
                echo '<div class="content-wrap">';
                    if (!empty($instance['image']) ){
                        echo '<div class="content-left">';
                            echo '<img src="'.$image_src[0].'" />';
                        echo '</div>';
                    }               
                    echo '<div class="content-right">';
                        if (!empty($instance['name'])) {
                            echo '<div class="name">'.$instance['name'].'</div>';
                        }
                        if (!empty($instance['position'])) {
                            echo '<div class="position">'.$instance['position'].'</div>';
                        }
                    echo '</div>';
                echo '</div>';
            }
        }

        echo '</div></div>';

        echo wp_kses_post($after_widget);
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['image'] = strip_tags( $new_instance['image'] );
        $instance['link'] = esc_url( strip_tags( $new_instance['link'] ) );
        $instance['description'] = sanitize_textarea_field( $new_instance['description'] );
        $instance['name'] = strip_tags( $new_instance['name'] );
        $instance['position'] = strip_tags( $new_instance['position'] );
        return $instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'image' => 0,
                'link' => '',
                'name' => '',
                'position' => '',
                'description' => '',
            )
        );

        $image_holder = $this->get_field_id( 'images' ) . '-' . acumec_generate_uiqueid();
        $title      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $image      = intval( $instance['image'] );
        $name = isset( $instance['name'] ) ? esc_attr( $instance['name'] ) : '';
        $position = isset( $instance['position'] ) ? esc_attr( $instance['position'] ) : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <div class="redexp-widget-image">
            <label><?php esc_html_e( 'Image', 'acumec' ); ?></label>
            <ul class="redexp-mu-images" id="<?php echo esc_attr( $image_holder ); ?>" data-img-mu-field="<?php echo esc_attr($this->get_field_id( 'image' )); ?>"><?php
            
                $attachment_image = wp_get_attachment_image_src( $image, 'thumbnail' );
                if ( ! empty( $attachment_image ) )
                {
                    printf(
                        '<li data-id="%1$s" style="background-image:url(%2$s);">',
                        esc_attr( $image ),
                        esc_url( $attachment_image[0] )
                    );

                    printf(
                        '<a class="image-edit" href="#" onclick="RedExpMedia.Image.edit(event,%s);"><i class="dashicons dashicons-edit"></i></a>',
                        esc_attr( '"' . $image_holder . '"' )
                    );

                    printf(
                        '<a class="image-delete" href="#" onclick="RedExpMedia.Image.remove(event,%s);"><i class="dashicons dashicons-trash"></i></a>',
                        esc_attr( '"' . $image_holder . '"' )
                    );

                    echo '</li>';
                }

                printf(
                    '<li data-id="0"><a class="image-add" href="#" onclick="RedExpMedia.Image.add(event,%s);"><i class="dashicons dashicons-plus-alt"></i></a></li>',
                    esc_attr( '"' . $image_holder . '"' )
                );

            ?></ul>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php echo esc_html__( 'Link', 'acumec' ); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo esc_url( $instance['link'] ); ?>" />

            <p class="howto"><?php echo esc_html__( 'Add link for image', 'acumec' ); ?></p>
            <input type="hidden" name="<?php echo esc_attr($this->get_field_name( 'image' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'image' )); ?>" value="<?php echo esc_attr( $instance['image'] ); ?>"/>
        </div>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'acumec' ); ?></label>
            <textarea  class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_textarea( $instance['description'] ); ?></textarea>
        </p>
        <p>
             <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr($name); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>"><?php esc_html_e( 'Position:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'position' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'position' ) ); ?>" type="text" value="<?php echo esc_attr($position); ?>" />
        </p>
        <?php
    }
}

add_action( 'widgets_init', 'acumec_register_testimonial_widget' );
function acumec_register_testimonial_widget(){
    register_ef4_widget('Cms_Testimonials_Widget');
}