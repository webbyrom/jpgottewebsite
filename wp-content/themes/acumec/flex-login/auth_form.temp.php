<?php
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 1/6/2017
	 * Time: 10:27 PM
	 */
	$refer         = isset( $_GET['redirect'] ) ? $_GET['redirect'] : '';
	$site_url      = get_site_url();
	$current_url   = ( ! empty( $refer ) ) ? $refer : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	$atts          = wp_parse_args( $atts, array(
		'title'    => esc_html__( 'Login Register', 'acumec' ),
		'style'    => 'fs-popup',
		'type'     => 'both',
		'num_link' => '1',
		'active'   => 'login'
	) );

	$can_register  = get_option( 'users_can_register' );
	$only_login    = ( $atts['type'] == 'login' || ! $can_register ) ? true : false;
	$only_register = ( $atts['type'] == 'register' ) ? true : false;
	$settings      = fs_get_option( array(
		'login_btn',
		'register_btn',
		'login_label',
		'register_label',
		'general_label'
	), array(
		esc_html__( 'Login', 'acumec' ),
		esc_html__( 'Register', 'acumec' ),
		esc_html__( 'Sign in', 'acumec' ),
		esc_html__( 'Register', 'acumec' ),
		esc_html__( 'Sign in or register', 'acumec' )
	) );
    
?>
<aside class="widget fs-widget widget_authenticate">
    <div class="fs-link">
            <span>
                <?php if ( ( ! $only_register && ! $only_login && $atts['type'] == 'both' && $atts['num_link'] == '1' ) ): ?>
                    <a href="#fs-general-form-<?php echo esc_attr( $atts['id'] ) ?>" data-active="<?php echo esc_attr( $atts['active'] ) ?>"><?php echo esc_attr( $settings['general_label'] ) ?></a>
                <?php elseif ( ( $atts['type'] == 'both' && $atts['num_link'] == '2' ) ): ?>
                    <a href="#fs-login-form-<?php echo esc_attr( $atts['id'] ) ?>" data-active="login"><?php echo esc_attr( $settings['login_label'] ) ?></a>
                    <a href="#fs-register-form-<?php echo esc_attr( $atts['id'] ) ?>" data-active="register"><?php echo esc_attr( $settings['register_label'] ) ?></a>
                <?php elseif ( $only_register ): ?>
                    <a href="#fs-register-form-<?php echo esc_attr( $atts['id'] ) ?>" data-active="register"><?php echo esc_attr( $settings['register_label'] ) ?></a>
                <?php elseif ( $only_login ): ?>
                    <a href="#fs-login-form-<?php echo esc_attr( $atts['id'] ) ?>" data-active="login"><?php echo esc_attr( $settings['login_label'] ) ?></a>
                <?php endif; ?>
            </span>
    </div>
    <div class="fs-form <?php echo esc_attr( $atts['style'] ) ?>">
        <div class="fs-card card">           
            <?php if(!empty($atts['title_login'])):?>
            <div class="fs-header login-header">
                <?php if ( $atts['style'] !== 'dropdown' ): ?>
                    <span class="fs-close">&times;</span>
                <?php endif; ?>
                <h4 class="fs-center"><?php echo esc_attr( $atts['title_login'] ) ?></h4>
                    <div class="fs-subtitle">
                        <?php echo esc_html__('Login Using any of these options....','acumec'); ?>
                    </div>        
            </div>
            <?php endif; ?>
            <?php if(!empty($atts['title_register'])):?>
                <div class="fs-header register-header">
                    <?php if ( $atts['style'] !== 'dropdown' ): ?>
                        <span class="fs-close">&times;</span>
                    <?php endif; ?>
                    <h4 class="fs-center"><?php echo esc_attr( $atts['title_register'] ) ?></h4>
                    <div class="fs-subtitle">
                        <?php echo esc_html__('Login Using any of these options....','acumec'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="fs-body">
				<?php if ( ! $only_register): ?>
                    <div class="form">
                        <div class="fs-login-form-wrap">
                            <form id="fs-login-form-<?php echo esc_attr( $atts['id'] ) ?>" class="fs-login-form" onsubmit="return false;">
								<?php wp_nonce_field( 'fs_login', 'fs_login' ); ?>
								<?php wp_get_referer() ?>
                                <div class="fs-login-notice"></div>
                                <input type="hidden" name="refer" value="<?php echo esc_url( $refer ) ?>">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control required fs-full" name="username" placeholder="<?php echo esc_html__('Username','acumec');?>" value="">
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control required fs-full" placeholder="<?php echo esc_html__('Password','acumec');?>" value="">
                                    </div> 
                                </div>     
                            
                                <div class="form-action">
                                    <input type="checkbox" name="remember" value="remember"><?php echo esc_html__('Remember me','acumec')?>
                                    <a class="forgot" href="<?php echo wp_lostpassword_url(); ?>"><?php echo esc_html__('Lost your Password?','acumec')?></a>
                                    <div class="fs-action">
                                        <button type="submit" class="btn btn-login btn-theme-primary"><?php echo esc_attr( $settings['login_btn'] ) ?></button>
                                    </div>
                                </div>

                                <div class="fs-created">
                                    <a href="#fs-register-form-<?php echo esc_attr( $atts['id'] ) ?>" data-active="register" class="fs-register"><?php echo esc_html__('Create an account','acumec')?></a>
                                </div>
                            </form>
                            <?php if ( $can_register ): ?>
                                <div class="fs-login-desc">
                                    <?php if ( ! empty( $atts['login_description'] ) )
                                        echo wpautop( $atts['login_description'] ) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
				<?php endif; ?>
                <?php if ( $can_register ): ?>
                    <div class="form">
                        <div class="fs-register-form-wrap">
                            <form id="fs-register-form-<?php echo esc_attr( $atts['id'] ) ?>" class="fs-register-form" onsubmit="return false;">
        								<?php wp_nonce_field( 'fs_register', 'fs_register' ); ?>
        								<?php wp_get_referer() ?>
                                        <input type="hidden" name="refer" value="<?php echo esc_url( $refer ) ?>"/>
                                        <div class="fs-register-notice"></div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control required fs-full" name="fs_first_name" placeholder="<?php echo esc_html__('First Name','acumec');?>" value="">
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control required fs-full" name="fs_last_name" placeholder="<?php echo esc_html__('Last Name','acumec');?>" value="">
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="email" class="form-control required fs-full" name="fs_email" placeholder="<?php echo esc_html__('Email','acumec');?>" value="">
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control required fs-full" name="fs_username" placeholder="<?php echo esc_html__('User name','acumec');?>" value="">
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control required fs-full" name="fs_password" placeholder="<?php echo esc_html__('Password','acumec');?>" value="">
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control required fs-full" name="fs_password_re" placeholder="<?php echo esc_html__('Confirm Password','acumec');?>" value="">
                                            </div> 
                                        </div>
                                        <div class="fs-action fs-center">
                                            <button type="submit" class="btn-register btn btn-theme-primary"><?php echo esc_attr( $settings['register_btn'] ) ?></button>
                                        </div>
                                        <div class="fs-reg-signin fs-center">
                                            <p>Already have an account? <a href="#" class="fs-login"><?php echo esc_html__('Sign in now','acumec');?></a></p>
                                        </div>
                                    </form>
                            <div class="fs-register-form-desc">
                                <?php if ( ! empty( $atts['register_description'] ) )
                                    echo wpautop( $atts['register_description'] ) ?>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</aside>