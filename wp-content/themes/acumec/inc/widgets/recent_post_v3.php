<?php
if (!function_exists('register_ef4_widget')) return;
function acumec_register_recent_post_widget_v3(){
    register_ef4_widget('CS_Recent_Post_Widget_V3');
}
add_action( 'widgets_init', 'acumec_register_recent_post_widget_v3' );

class CS_Recent_Post_Widget_V3 extends WP_Widget {
 
    function __construct() {
        parent::__construct(
            'cs_recent_post_v3',esc_html__( 'CS Recent Posts V3', 'acumec' ),array('description' => esc_html__( 'Recent Posts Widget 3.', 'acumec' )) 
        );
        
    }

    function widget($args, $instance) {
        extract($args);
        
        $title = apply_filters('widget_title', empty($instance['title']) ? esc_html__('Recent Posts', 'acumec' ) : $instance['title'], $instance, $this->id_base);
        $show_date = (int) $instance['show_date'];        
        $show_decs = (int) $instance['show_decs'];
        $show_image = (int) $instance['show_image'];
        $show_author = (int) $instance['show_author'];
        $link = $instance['link'];
        $number = (int) $instance['number'];

        echo wp_kses_post($before_widget);

        if($title) {
            echo wp_kses_post($before_title.$title.$after_title);
        }

        $sticky = get_option('sticky_posts');
        $args = array(
            'posts_per_page' => $number,
            'post_type' => 'post',
            'post_status' => 'publish',
            'post__not_in'  => $sticky,
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => 1
        );

        $wp_query = new WP_Query($args);
        $extra_class = !empty($instance['extra_class']) ? $instance['extra_class'] : "";

        // no 'class' attribute - add one with the value of width
        if( strpos($before_widget, 'class') === false ) {
            $before_widget = str_replace('>', 'class="'. $extra_class . '"', $before_widget);
        }
        // there is 'class' attribute - append width value to it
        else {
            $before_widget = str_replace('class="', 'class="'. $extra_class . ' ', $before_widget);
        }
        ?>
        <?php if ($wp_query->have_posts()){ 
            ?>
                <div class="cms-recent-post">
                    <div class="cms-recent-post-wrapper">

                        <?php while ($wp_query->have_posts()): $wp_query->the_post(); ?>

                        <div class="widget-recent-item clearfix">
                            <div class="entry-top clearfix">
                            <?php if ( has_post_thumbnail() && $show_image ){
                            $thumbnail = get_the_post_thumbnail(get_the_ID(),'acumec-770-645');
                            ?>
                                <div class="entry-thumbnail"> 
                                    <a href="<?php the_permalink(); ?>" class="img"> 
                                      <?php echo acumec_html($thumbnail); ?> 
                                    </a> 
                                </div>
                               <?php
                                }else {
                                     $thumbnail = null;
                                 } 
                                ?>                               
                                <div class="entry-main">
                                    <?php if ($show_date) { ?>
                                    <span class="date"><?php echo get_the_date('d F');?></span>
                                    <?php }?>
                                    <h5 class="title-recent"><a class="entry-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>  
                                    <?php if ($show_author) { ?>
                                    <span class="meta-recent"><?php the_author_posts_link(); ?></span>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="entry-bottom">
                                <?php if ($show_decs) { ?>
                                    <p class="description"><?php echo acumec_grid_limit_words( strip_tags( get_the_excerpt() ),15); ?></p>
                                <?php  } ?>
                            </div>  
                        </div> 
                        <?php endwhile; ?>
                    </div>
                    <?php if ($link): ?>
                        <a class= "btn-recent btn btn-theme-default btn-round" href=" <?php echo esc_html($link); ?> " ><?php echo esc_html__('All News','acumec'); ?></a>
                    <?php endif ?>
                </div>
            <?php 
             //wp_reset_postdata(); 
            } else { ?>
                <span class="notfound">No post found!</span>
            <?php
            }
            echo wp_kses_post($after_widget);
            wp_reset_postdata();
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = $new_instance['title'];
        $instance['show_date'] = $new_instance['show_date'];
        $instance['show_decs'] = $new_instance['show_decs'];
        $instance['show_image'] = $new_instance['show_image'];
        $instance['show_author'] = $new_instance['show_author'];
        $instance['number'] = (int) $new_instance['number'];
        $instance['link'] = $new_instance['link'];
        $instance['extra_class'] = $new_instance['extra_class'];

        return $instance;
    }

    function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $show_date = isset($instance['show_date']) ? esc_attr($instance['show_date']) : '';
        $show_decs = isset($instance['show_decs']) ? esc_attr($instance['show_decs']) : '';
        $show_image = isset($instance['show_image']) ? esc_attr($instance['show_image']) : '';
        $link = isset($instance['link']) ? esc_attr($instance['link']) : '';
        $show_author = isset($instance['show_author']) ? esc_attr($instance['show_author']) : '';
        if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
                     $number = 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e( 'Show date:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_date') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_date') ); ?>" <?php if($show_date!='') echo 'checked="checked";' ?> type="checkbox" value="1"  />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_decs')); ?>"><?php esc_html_e( 'Show Description:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_decs') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_decs') ); ?>" <?php if($show_decs!='') echo 'checked="checked";' ?> type="checkbox" value="1" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>"><?php esc_html_e( 'Show Image:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_image') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_image') ); ?>" <?php if($show_image!='') echo 'checked="checked";' ?> type="checkbox" value="1" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>"><?php esc_html_e( 'Show Author:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('show_author') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_author') ); ?>" <?php if($show_author!='') echo 'checked="checked";' ?> type="checkbox" value="1" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e( 'Number of posts to show:', 'acumec' ); ?></label>
            <input id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('link')); ?>"><?php esc_html_e( 'Link:', 'acumec' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('link') ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('extra_class')); ?>">Extra Class:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('extra_class')); ?>" name="<?php echo esc_attr($this->get_field_name('extra_class')); ?>" value="<?php if(isset($instance['extra_class'])){echo esc_attr($instance['extra_class']);} ?>" />
        </p>
        <?php
    }
}
?>