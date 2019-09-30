<?php
/**
 * Plugin Name: EF4-Framework
 * Description: EF4-Framework add Redux Framework, Redux Meta Framework, SCSS Framework, feature of cmssupperhero and breadcrumb-navxt for themes developer.
 * Version: 2.1.0
 * Author: VHieu
 * License: GPLv2 or later
 * Text Domain: ef4-framework
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
//Do a PHP version check, require 5.6 or newer
define('EF4_REQUIRE_PHP','5.6.0');

if(version_compare(phpversion(), EF4_REQUIRE_PHP, '<'))
{
    //Only purpose of this function is to echo out the PHP version error
    function ef4_phpold()
    {
        printf('<div class="error"><p>' . __('Your PHP version is too old, please upgrade to a newer version. Your version is %1$s, EF4Framework requires %2$s') . '</p></div>', phpversion(), EF4_REQUIRE_PHP);
    }
    //If we are in the admin, let's print a warning then return
    if(is_admin())
    {
        add_action('admin_notices', 'ef4_phpold');
    }
    return;
}
if (!class_exists('EF4Framework') && !function_exists('ef4_phpold')) :
    require_once 'inc/core.php';
    final class EF4Framework
    {
        /**
         * EF4Framework version.
         *
         * @var string
         */
        public $version = '2.0.0';

        /**
         * The single instance of the class.
         *
         * @var EF4Framework
         * @since 1.0.0
         */
        protected static $_instance = null;

        protected $_data;
        public $error_message = array();

        /**
         * EF4Framework Constructor.
         */
        public function __construct()
        {
            //base
            $this->define_constants();
            $this->includes();
            //include framework
            $this->load_framework_data();
            $this->define_framework_constants();
            $this->framework_includes();
            $this->init_hooks();
            do_action('ef4_framework_loaded');
        }

        private function load_framework_data()
        {
            $data = array(
                'plugins_loaded'=>array(),
                'instance'=> array()
            );
            $options = get_option('ef4_frames_use',[]);
            $options = wp_parse_args($options,[
                'scss'=>'old',
                'ReduxCore'=>'old',
                'Metacore'=>'old',
                'Taxonomy'=>'old',
                'cmssuperheroes'=>'old',
                'breadcrumb-navxt'=>'old',
                'ef4-service'=>'old',
                'VCModify'=>'old'
            ]);
            // SCSS framework
            if(!empty($options['scss']))
            {
                $file =  'frameworks/SCSS/scss.inc.php';
                if($options['scss'] == 'new')
                    $file =  'frameworks/SCSS/scss_new.inc.php';
                $data['plugins_loaded']['SCSS'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'scssc',
                            'scss_formatter',
                            'scss_formatter_nested',
                            'scss_formatter_compressed',
                            'scss_server',
                        ),
                    ),
                    'require'      => array(
                        $file
                    )
                );
            }

            //  Redux
            if(!empty($options['ReduxCore'])) {
                $data['plugins_loaded']['ReduxCore'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'ReduxFramework'
                        )
                    ),
                    'require'      => array(
                        'frameworks/ReduxCore/framework.php'
                    )
                );
            }
            // Metacore
            if(!empty($options['Metacore'])) {
                $data['plugins_loaded']['Metacore'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'MetaFramework',
                        ),
                    ),
                    'require'      => array(
                        'frameworks/Metacore/framework.php'
                    )
                );
            }
            //EF3 Tax
            if(!empty($options['Taxonomy'])) {
                $data['plugins_loaded']['Taxonomy'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'EF3Taxonomy_meta'
                        )
                    ),
                    'require'      => array(
                        'frameworks/Taxonomy/framework.php'
                    )
                );
            }
            // CMS Supperheroes
            $cms_path = 'frameworks/cmssuperheroes/';
            if(!empty($options['cmssuperheroes'])) {
                $data['instance']['cmssuperheroes'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'CmssuperheroesCore'
                        )
                    ),
                    'require'      => array(
                        $cms_path . 'cmssuperheroes.php'
                    ),
                    'define'       => array(
                        'CMS_NAME'      => 'cmssuperheroes',
                        'CMS_DIR'       => trailingslashit($cms_dir = EF4_ABSPATH . $cms_path),
                        'CMS_URL'       => trailingslashit($cms_url = EF4_URL . $cms_path),
                        'CMS_LIBRARIES' => trailingslashit($cms_dir . "libraries"),
                        'CMS_LANGUAGES' => trailingslashit($cms_dir . "languages"),
                        'CMS_TEMPLATES' => trailingslashit($cms_dir . "templates"),
                        'CMS_INCLUDES'  => trailingslashit($cms_dir . "includes"),
                        'CMS_CSS'       => trailingslashit($cms_url . "assets/css"),
                        'CMS_JS'        => trailingslashit($cms_url . "assets/js"),
                        'CMS_IMAGES'    => trailingslashit($cms_url . "assets/images"),
                    )
                );
            }
            //breadcrumb-navxt
            if(!empty($options['breadcrumb-navxt'])) {
                $data['plugins_loaded']['breadcrumb-navxt'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'breadcrumb_navxt'
                        )
                    ),
                    'require'      => array(
                        'frameworks/breadcrumb-navxt/breadcrumb-navxt.php'
                    ),
                );
            }
            //EF4 service
            if(!empty($options['ef4-service'])) {
                $data['plugins_loaded']['ef4-service'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'EF4Service'
                        )
                    ),
                    'require'      => array(
                        'frameworks/ef4-service/ef4-service.php'
                    ),
                );
            }
            // VCModify
            if(!empty($options['VCModify'])) {
                $data['instance']['VCModify'] = array(
                    'check_exists' => array(
                        'class' => array(
                            'EF4VCGrid',
                            'EF4VCGridBuilder',
                            'EF4VCGridBuilderAutoLoad'
                        )
                    ),
                    'require'      => array(
                        'frameworks/VCModify/include/classes/EF4VCGrid.php',
                        'frameworks/VCModify/include/classes/EF4VCGridBuilder.php',
                        'frameworks/VCModify/include/classes/EF4VCGridBuilderAutoLoad.php',
                        'frameworks/VCModify/include/helper/helper_api.php',
                        'frameworks/VCModify/include/modify/vc_grid.php',
                        'frameworks/VCModify/vc_custom.php',
                    )
                );
            }
            $data['instance']['cf7']=[
                'require'      => array(
                    'frameworks/cf7/cf7.php',
                )
            ];
            $data['instance']['social'] = array(
                'check_exists' => array(
                    'class' => array(
                        'EF4_Twitter_API',
                    )
                ),
                'require'      => array(
                    'frameworks/ef4-social/twitter.php',
                )
            );
            $this->_data = $data;
            return true;
        }

        /**
         * Include framework required core files used in admin and on the frontend.
         */
        private function framework_includes()
        {
            add_action('plugins_loaded',array($this,'framework_includes_plugin_loaded'));
            $this->framework_includes_instance();
        }
        public function framework_includes_plugin_loaded()
        {
            if(empty($this->_data['plugins_loaded']))
                return;
            $element_data =  $this->_data['plugins_loaded'];
            foreach ($element_data as $feature => $data) {
                if ($this->check_exists($data) && $require = $this->get_key_array($data, 'require')) {
                    foreach ($require as $file) {
                        $file_require = EF4_ABSPATH . $file;
                        if (file_exists($file_require))
                            include_once $file_require;
                        else
                            $this->error_message[] = 'File ' . $file_require . ' is missing';
                    }
                }
            }
        }
        public function framework_includes_instance()
        {
            if(empty($this->_data['instance']))
                return;
            $element_data =  $this->_data['instance'];
            foreach ($element_data as $feature => $data) {
                if ($this->check_exists($data) && $require = $this->get_key_array($data, 'require')) {
                    foreach ($require as $file) {
                        $file_require = EF4_ABSPATH . $file;
                        if (file_exists($file_require))
                            include_once $file_require;
                        else
                            $this->error_message[] = 'File ' . $file_require . ' is missing';
                    }
                }
            }
        }
        /**
         * Include required core files used in admin and on the frontend.
         */
        private function includes()
        {
            $class = array(
                'EF4Functions',
                'EF4Templates'
            );
            foreach ($class as $inc)
            {
                if(!class_exists($inc))
                    include_once EF4_ABSPATH . 'includes/'.$inc.'.php';
                else
                    $this->error_message[] = 'Class ' . $inc . ' existed';
            }
        }

        private function get_key_array(array $arr, $key)
        {
            if (!empty($arr[$key]) && is_array($arr[$key]))
                return $arr[$key];
            return false;
        }

        private function check_exists($data)
        {
            $is_pass = true;
            if (!($need_check = $this->get_key_array($data, 'check_exists'))) {
                return $is_pass;
            }
            $class = ($temp = $this->get_key_array($need_check, 'class')) ? $temp : array();
            foreach ($class as $check) {
                if (class_exists($check)) {
                    $is_pass = false;
                    $this->error_message[] = 'Class ' . $check . ' is exists in other source';
                }
            }
            $functions = ($temp = $this->get_key_array($need_check, 'function')) ? $temp : array();
            foreach ($functions as $check) {
                if (function_exists($check)) {
                    $is_pass = false;
                    $this->error_message[] = 'Function ' . $check . ' is exists in other source';
                }
            }
            return $is_pass;
        }

        /**
         * Hook into actions and filters.
         * @since  1.0
         */
        private function init_hooks()
        {

        }

        /**
         * Define EF4Framework Constants.
         */
        private function define_constants()
        {
            $upload_dir = wp_upload_dir();
            //main define
            $this->define('EF4_ABSPATH', trailingslashit(dirname(__FILE__)));
            $this->define('EF4_URL', plugin_dir_url(__FILE__));
        }

        private function define_framework_constants()
        {
            $raw_data = $this->_data;
            $data = array();
            foreach ($raw_data as $hook => $data_include)
            {
                $data = array_merge($data,$data_include);
            }
            foreach ($data as $framework) {
                $define = ($temp = $this->get_key_array($framework, 'define')) ? $temp : array();
                foreach ($define as $key => $value) {
                    $this->define($key, $value);
                }
            }
//            $this->define( 'WC_PLUGIN_FILE', __FILE__ );

        }

        /**
         * Main EF4Framework Instance.
         *
         * Ensures only one instance of EF4Framework is loaded or can be loaded.
         *
         * @since 1.0
         * @static
         * @see EF4Framework()
         * @return EF4Framework - Main instance.
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define($name, $value)
        {
            if (!defined($name)) {
                define($name, $value);
            }
        }
    }
endif;
/**
 * Main instance of EF4Framework.
 *
 * Returns the main instance of WC to prevent the need to use globals.
 *
 * @since  1.0
 * @return EF4Framework
 */
function EF4Framework()
{
    return EF4Framework::instance();
}

//add_action('plugins_loaded','ef4_init');
//function ef4_init()
//{
    // Global for backwards compatibility.
    $GLOBALS['EF4'] = EF4Framework();
//}