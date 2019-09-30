<?php
if (!function_exists('register_ef4_widget')) return;
function acumec_register_social_widget(){
    register_ef4_widget('Cms_Socials_Widget');
}

add_action( 'widgets_init', 'acumec_register_social_widget' );

class Cms_Socials_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'cms_socials_widget', // Base ID
            esc_html__('Cms Socials', 'acumec'), // Name
            array('description' => esc_html__('Socials Widget', 'acumec')) // Args
        );
    }

    function widget($args, $instance) {
        extract($args);
        if (!empty($instance['title'])) {
        $title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Be Social', 'acumec' ) : $instance['title'], $instance, $this->id_base);
        }
         
        $extra_class = !empty($instance['extra_class']) ? $instance['extra_class'] : "";

        // no 'class' attribute - add one with the value of width
        if( strpos($before_widget, 'class') === false ) {
            $before_widget = str_replace('>', 'class="'. $extra_class . '"', $before_widget);
        }
        // there is 'class' attribute - append width value to it
        else {
            $before_widget = str_replace('class="', 'class="'. $extra_class . ' ', $before_widget);
        }
        
        echo wp_kses_post($before_widget);
         
        if (!empty($title))
        echo wp_kses_post($before_title . $title . $after_title);

        echo "<ul class='wg-socials'>";
        
        for($i=1; $i<=10; $i++){
            $icon_class_i = (isset($instance['icon_class_'.$i]) && !empty($instance['icon_class_'.$i])) ? $instance['icon_class_'.$i] : '';
            $link_i = (isset($instance['link_'.$i]) && !empty($instance['link_'.$i])) ? $instance['link_'.$i] : '';
            if(!empty($icon_class_i) && !empty($link_i))
                echo '<li><a target="_blank" href="'.esc_url($link_i).'"  data-placement="top" title="'.str_replace('fa fa-', "", $icon_class_i).'"><i class="'.esc_attr($icon_class_i).'"></i></a></li>';
        }
        
        echo "</ul>";


        echo wp_kses_post($after_widget);
    }

    function update( $new_instance, $old_instance ) {
         $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
        
         for($i=1; $i<=10; $i++){
            $instance['icon_class_'.$i] = strip_tags($new_instance['icon_class_'.$i]);
            $instance['link_'.$i] = strip_tags($new_instance['link_'.$i]);
         }
           
         $instance['extra_class'] = $new_instance['extra_class'];

         return $instance;
    }

    function form( $instance ) {
         $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
  
         $extra_class = isset($instance['extra_class']) ? esc_attr($instance['extra_class']) : '';
         ?>
         <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title:', 'acumec' ); ?></label>
         <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
         <?php
         for($i=1; $i<=10; $i++){
            $icon_class_i = isset($instance['icon_class_'.$i]) ? esc_attr($instance['icon_class_'.$i]) : '';
            $link_i = isset($instance['link_'.$i]) ? esc_attr($instance['link_'.$i]) : '';
         ?>
             <p>
             <label for="<?php echo esc_attr($this->get_field_id('icon_class_'.$i)); ?>"><?php esc_html_e( 'Icon class', 'acumec' ); ?> <?php echo esc_attr($i);?></label>
             <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('icon_class_'.$i) ); ?>"  name="<?php echo esc_attr( $this->get_field_name('icon_class_'.$i) ); ?>" type="text" value="<?php echo esc_attr( $icon_class_i ); ?>" />
             </p>
             <p>
             <label for="<?php echo esc_attr($this->get_field_id('link_'.$i)); ?>"><?php esc_html_e( 'Link', 'acumec' ); ?> <?php echo esc_attr($i);?></label>
             <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('link_'.$i) ); ?>" name="<?php echo esc_attr( $this->get_field_name('link_'.$i) ); ?>" type="text" value="<?php echo esc_attr( $link_i ); ?>" /></p>
         <?php   
         }
         ?>   
         <p><label for="<?php echo esc_attr($this->get_field_id('extra_class')); ?>"> <?php esc_html_e( 'Extra Class:', 'acumec' ); ?></label>
         <input class="widefat" id="<?php echo esc_attr($this->get_field_id('extra_class')); ?>" name="<?php echo esc_attr($this->get_field_name('extra_class')); ?>" value="<?php echo esc_attr( $extra_class ); ?>" /></p>

    <?php
    }

}

 
?>