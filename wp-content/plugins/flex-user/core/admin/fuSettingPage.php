<?php
	
	/**
	 * Created by PhpStorm.
	 * User: Nic
	 * Date: 24/6/2017
	 * Time: 8:43 AM
	 */
	class fuSettingPage extends fs_boot {
		
		private $setting_slug, $domain;
		
		/**
		 * fuSettingPage constructor.
		 */
		public function __construct() {
			$this->init( fsUser()->plugin_folder_name );
			$this->setting_slug = fsUser()->setting_slug;
			$this->domain       = fsUser()->domain;
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
			add_filter( "fs_filter_tabs/{$this->setting_slug}", array( $this, 'facebook_tab' ) );
			add_filter( "fs_filter_tabs/{$this->setting_slug}", array( $this, 'twitter_tab' ) );
		}
		
		public function add_menu() {
			add_menu_page( 'Flex Login', 'Flex Login', 'manage_options', "{$this->setting_slug}", array(
				$this,
				'create_menu'
			), 'dashicons-admin-users', 50 );
		}
		
		public function create_menu() {
			$option = array(
				'page_slug'   => $this->setting_slug,
				'title'       => 'Flex Login Setting',
				'description' => '',
				'tab_class'   => 'tab-col-teal',
				'tabs'        => array(
					'general' => array(
						'title'   => esc_attr__( 'General', $this->domain ),
						'actived' => true,
						'fields'  => array(
							array(
								'type'    => 'text',
								'name'    => 'login_btn',
								'label'   => 'Login Button',
								'layout'  => 'horizontal',
								'default' => 'Login'
							),
							array(
								'type'    => 'text',
								'name'    => 'register_btn',
								'label'   => 'Register Button',
								'layout'  => 'horizontal',
								'default' => 'Register'
							),
							array(
								'type'    => 'text',
								'name'    => 'logout_btn',
								'label'   => 'Logout Button',
								'layout'  => 'horizontal',
								'default' => 'Logout'
							),
							array(
								'type'    => 'text',
								'name'    => 'login_label',
								'label'   => 'Login Label',
								'layout'  => 'horizontal',
								'default' => 'Login'
							),
							array(
								'type'    => 'text',
								'name'    => 'register_label',
								'label'   => 'Register Label',
								'layout'  => 'horizontal',
								'default' => 'Register'
							),
							array(
								'type'    => 'text',
								'name'    => 'general_label',
								'label'   => 'General Label',
								'layout'  => 'horizontal',
								'default' => 'Login Or Register'
							),
						),
					),
				),
			);
			$this->generatePageSettings( $option );
		}
		
		public function facebook_tab( $tabs ) {
			$tabs['facebook'] = array(
				'title'  => 'Facebook',
				'note'   => 'Click <a href="https://developers.facebook.com/apps" target="_blank">here</a> to get key',
				'fields' => array(
					array(
						'type'   => 'text',
						'name'   => 'fb_app_id',
						'label'  => 'App ID',
						'layout' => 'horizontal',
					),
					array(
						'type'   => 'text',
						'name'   => 'fb_app_secret',
						'label'  => 'App Secret',
						'layout' => 'horizontal',
					),
					array(
						'type'   => 'checkbox',
						'name'   => 'enable_facebook',
						'id'     => 'enable_facebook',
						'label'  => 'Enable this feature',
						'layout' => 'horizontal',
					),
				
				),
			
			);
			
			return $tabs;
		}
		
		public function twitter_tab( $tabs ) {
			$tabs['twitter'] = array(
				'title'  => 'Twitter',
				'note'   => 'Click <a href="https://apps.twitter.com/app/new" target="_blank">here</a> to get key',
				'fields' => array(
					array(
						'type'   => 'text',
						'name'   => 'tw_app_id',
						'label'  => 'App ID',
						'layout' => 'horizontal',
					),
					array(
						'type'   => 'text',
						'name'   => 'tw_app_secret',
						'label'  => 'App Secret',
						'layout' => 'horizontal',
					),
					array(
						'type'   => 'checkbox',
						'name'   => 'enable_twitter',
						'id'     => 'enable_twitter',
						'label'  => 'Enable this feature',
						'layout' => 'horizontal',
					),
				),
			);
			
			return $tabs;
		}
		
	}