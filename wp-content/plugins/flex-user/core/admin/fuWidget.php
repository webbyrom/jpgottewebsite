<?php
	
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 24/6/2017
	 * Time: 8:43 AM
	 */
	class fuWidget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'fs-login-widget',
				__( 'Flex Login', fsUser()->domain ),
				array( 'description' => __( 'Support Register, Login', fsUser()->domain ), )
			);
		}
		
		public function form( $instance ) {
			$instance     = array_merge( array(
				'title'    => esc_html__( 'Login Register', fsUser()->domain ),
				'type'     => 'both',
				'style'    => 'fs-popup',
				'num_link' => '1',
				'active'   => 'all',	
				'login_description'=>'',
				'register_description'=>''
			), $instance );
			$can_register = get_option( 'users_can_register' );
			wp_enqueue_script( 'fs-user-widget.js', fsUser()->plugin_url . 'assets/js/fs-user-widget.js', array(), '', true );
			wp_enqueue_style( 'fs-user-widget.css', fsUser()->plugin_url . 'assets/css/fs-user-widget.css', array(), '', 'all' );
			$params = array(
				'title',
				'style',
				'type',
				'num_link',
				'active_form',
				'login_description',
				'register_description'
			);
			$params = apply_filters( 'fs-user-widget-params', $params );
			?>
			<?php if ( in_array( 'title', $params ) ): ?>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
						<?php esc_html_e( 'Title:', fsUser()->domain ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ) ?>">
                </p>
			<?php endif; ?>
			
			<?php if ( in_array( 'style', $params ) ): ?>
                <p class="fs-style">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
						<?php esc_html_e( 'Style:', fsUser()->domain ); ?></label>
                    <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                        <option value="fs-popup" <?php echo esc_attr( ( $instance['style'] == 'fs-popup' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'Popup', fsUser()->domain ) ?></option>
                        <option value="fs-dropdown"<?php echo esc_attr( ( $instance['style'] == 'fs-dropdown' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'DropDown', fsUser()->domain ) ?></option>
                    </select>
                </p>
			<?php endif; ?>
			<?php if ( $can_register ): ?>
				<?php if ( in_array( 'type', $params ) ): ?>
                    <p class="fs-type">
                        <label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>">
							<?php esc_html_e( 'Type:', fsUser()->domain ); ?></label>
                        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
                            <option value="both" <?php echo esc_attr( ( $instance['type'] == 'both' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'Both login and register', fsUser()->domain ); ?></option>
                            <option value="login" <?php echo esc_attr( ( $instance['type'] == 'login' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'Only login', fsUser()->domain ); ?></option>
                            <option value="register" <?php echo esc_attr( ( $instance['type'] == 'register' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'Only register', fsUser()->domain ); ?></option>
                        </select>
                    </p>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php if ( $can_register ): ?>
				<?php if ( in_array( 'num_link', $params ) ): ?>
                    <p class="fs-num-link <?php echo esc_attr( ( ( $instance['style'] == 'page' ) || ( $instance['style'] == 'popup' && $instance['type'] !== 'both' ) ) ? 'hidden' : '' ) ?>">
                        <label><?php esc_html_e( 'Number link:', fsUser()->domain ); ?></label>
                        <input type="radio" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_link' ) ); ?>1" name="<?php echo esc_attr( $this->get_field_name( 'num_link' ) ); ?>" value="1" <?php echo esc_attr( $instance['num_link'] !== '2' ? 'checked' : '' ) ?>>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'num_link' ) ); ?>1"><?php esc_html_e( 'One', fsUser()->domain ) ?></label>
                        <input type="radio" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'num_link' ) ); ?>2" name="<?php echo esc_attr( $this->get_field_name( 'num_link' ) ); ?>" value="2" <?php echo esc_attr( $instance['num_link'] == '2' ? 'checked' : '' ) ?>>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'num_link' ) ); ?>2"><?php esc_html_e( 'Two', fsUser()->domain ) ?></label>
                    </p>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( $can_register ): ?>
				<?php if ( in_array( 'active_form', $params ) ): ?>
                    <p class="fs-btn-active <?php echo esc_attr( ( ( $instance['type'] !== 'both' ) || ( $instance['style'] == 'popup' && $instance['type'] === 'both' && $instance['num_link'] !== '1' ) ) ? 'hidden' : '' ) ?>">
                        <label for="<?php echo esc_attr( $this->get_field_id( 'active' ) ); ?>">
							<?php esc_html_e( 'Active Form:', fsUser()->domain ); ?></label>
                        <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'active' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'active' ) ); ?>">
                            <option value="all" <?php echo esc_attr( ( $instance['active'] == 'all' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'Both login and register', fsUser()->domain ); ?></option>
                            <option value="login" <?php echo esc_attr( ( $instance['active'] == 'login' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'Only login', fsUser()->domain ); ?></option>
                            <option value="register"<?php echo esc_attr( ( $instance['active'] == 'register' ) ? 'selected' : '' ) ?>><?php esc_html_e( 'Only register', fsUser()->domain ); ?></option>
                        </select>
                    </p>
				<?php endif ?>
			
			<?php endif; ?>
			<?php if ( in_array( 'login_description', $params ) ): ?>
                <p class="fs-login_description">
                    <label for="<?php echo esc_attr( $this->get_field_id( 'login_description' ) ); ?>">
						<?php esc_html_e( 'Login Description:', fsUser()->domain ); ?></label>
	                <?php //wp_editor($instance['login_description'],'login_description',array('media_buttons'=>false,'quicktags'=>true))?>
                    <textarea class="widefat" rows="4" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'login_description' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'login_description' ) ); ?>"><?php echo esc_textarea( $instance['login_description'] ) ?></textarea>
                </p>
			<?php endif; ?>
			<?php if ( $can_register ): ?>
				<?php if ( in_array( 'register_description', $params ) ): ?>
                    <p class="fs-register_description">
                        <label for="<?php echo esc_attr( $this->get_field_id( 'register_description' ) ); ?>">
							<?php esc_html_e( 'Register Description:', fsUser()->domain ); ?></label>
                        <?php //wp_editor($instance['register_description'] ,'register_description',array('media_buttons'=>false))?>
                        <textarea class="widefat" rows="4" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'register_description' ) ) ?>" name="<?php echo esc_attr( $this->get_field_name( 'register_description' ) ); ?>"><?php echo esc_textarea( $instance['register_description'] ) ?></textarea>
                    </p>
				<?php endif; ?>
			<?php endif; ?>
			<?php
		}
		
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			
			$instance['id']                   = mktime();
			$instance['title']                = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['type']                 = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : $old_instance['type'];
			$instance['style']                = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : $old_instance['style'];
			$instance['active']               = ( ! empty( $new_instance['active'] ) ) ? strip_tags( $new_instance['active'] ) : $old_instance['active'];
			$instance['num_link']             = ( ! empty( $new_instance['num_link'] ) ) ? strip_tags( $new_instance['num_link'] ) : $old_instance['num_link'];
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['login_description']    =  $new_instance['login_description'] ;
				$instance['register_description'] = $new_instance['register_description'];

			} else {
				$instance['login_description'] = wp_kses_post( $new_instance['login_description'] );
				$instance['register_description'] = wp_kses_post( $new_instance['register_description']);
			}
			  
			return $instance;
		}
		
		public function widget( $args, $instance ) {
			$atts = wp_parse_args( $instance, array(
				'title'                => 'Login Register',
				'style'                => 'fs-popup',
				'active'               => 'all',
				'num_link'             => '1',
				'login_description'    => '',
				'register_description' => '',
			) );

			if ( is_user_logged_in() ) {
				
				echo fsUser()->get_template_file__( 'logout', array( 'atts' => $atts ), '', 'flex-login' );
				
				return;
			}
			
			wp_enqueue_style( 'fs-user-form.css', fsUser()->plugin_url . 'assets/css/fs-user-form.css', array(), '', 'all' );
			wp_enqueue_script( 'jquery.validate.js', fsUser()->plugin_url . 'assets/vendor/jquery.validate.js', array(), '', true );
			wp_enqueue_script( 'fs-login.js', fsUser()->plugin_url . 'assets/js/fs-login.js', array(), '', true );
			wp_localize_script( 'fs-login.js', 'fs_login', array(
				'action' => 'fs_login',
				'url'    => admin_url( 'admin-ajax.php' ),
			) );
			wp_enqueue_script( 'fs-register.js', fsUser()->plugin_url . 'assets/js/fs-register.js', array(), '', true );
			wp_localize_script( 'fs-register.js', 'fs_register', array(
				'action' => 'fs_register',
				'url'    => admin_url( 'admin-ajax.php' ),
			) );
			
			echo fsUser()->get_template_file__( 'auth_form', array( 'atts' => $atts ), '', 'flex-login' );
		}
		
	}
	
	add_action( 'widgets_init', function () {
		register_widget( 'fuWidget' );
	} );