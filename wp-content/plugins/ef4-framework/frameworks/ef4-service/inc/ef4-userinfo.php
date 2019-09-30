<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 12/8/2017
 * Time: 4:46 PM
 */
class EF4UserInfo
{
    const PREFIX = 'ef4u';
    protected static $instance;
    protected static $inited;
    protected static $extends_meta_field = array('extend_social');

    public static function instance()
    {
        if (!self::$instance instanceof self)
            self::$instance = new self();
        return self::$instance;
    }

    public function __construct()
    {
        if (!self::$inited) {
            self::$inited = true;
            add_action('show_user_profile', array($this, 'extra_user_profile_fields'));
            add_action('edit_user_profile', array($this, 'extra_user_profile_fields'));
            add_action('personal_options_update', array($this, 'save_extra_user_profile_fields'));
            add_action('edit_user_profile_update', array($this, 'save_extra_user_profile_fields'));
            add_filter('user_contactmethods', array($this, 'add_custom_contact'));
        }
    }

    public function add_custom_contact($methods)
    {
        return array_merge($methods, array(
            'phone_number' => esc_html__('Phone Number', 'ef4-framework'),
        ));
    }

    public static function add_meta_fields($field)
    {
        if (is_string($field) && !empty(trim($field)) && !in_array($field, self::$extends_meta_field))
            self::$extends_meta_field[] = $field;
    }

    public function get_vc_font_support()
    {
        global $wp_filter;
        $filter_names = array_keys($wp_filter);
        $name_check = 'vc_iconpicker-type-';
        $font_supports = array();
        foreach ($filter_names as $name) {
            if (strpos($name, $name_check) === 0)
                $font_supports[] = substr($name, strlen($name_check));
        }
        $font_supports = apply_filters('ef4_iconpicker_fonts', $font_supports);
        $font_lib = array();
        foreach ($font_supports as $font) {
            $temp_font_lib = apply_filters($name_check . $font, array());
            $temp2 = array();
            if ($font === 'awesome' && empty($temp_font_lib))
                $temp_font_lib = apply_filters($name_check . 'font' . $font, array());
            foreach ($temp_font_lib as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key2 => $value2)
                        if (is_array($value2))
                            foreach ($value2 as $key3 => $value3)
                                $temp2[$key3] = $value3;
                        else
                            $temp2[$key2] = $value2;
                } else
                    $temp2[$key] = $value;
            }
            $font_lib[$font] = $temp2;
        }
        return $font_lib;
    }

    public function save_extra_user_profile_fields($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        foreach (self::$extends_meta_field as $field) {
            if (isset($_POST[$name = self::create_param_name($field)])) {
                $extends_meta = json_decode(stripslashes($_POST[$name]), true);
                update_user_meta($user_id, $name, $extends_meta);
            }
        }
    }

    public static function create_param_name($name)
    {
        return self::PREFIX . '_' . $name;
    }

    public static function recover_param_name($name)
    {
        $prefix = self::PREFIX . '_';
        if (strpos($name, $prefix) === 0)
            return substr($name, strlen($prefix));
        return $name;
    }

    public function extra_user_profile_fields($user)
    {
        $font_supports = $this->get_vc_font_support();
        if (empty($font_supports))
            $font_supports = array(
                'awesome' => array(
                    'fa fa-facebook'        => 'Facebook(social network)(facebook-f)',
                    'fa fa-youtube-play'    => 'YouTube Play(start, playing)',
                    'fa fa-google-plus'     => 'Google Plus(social network)',
                    'fa fa-instagram'       => 'Instagram',
                    'fa fa-twitter'         => 'Twitter(tweet, social network)',
                    'fa fa-user-circle-o'   => 'User Circle Outlined',
                    'fa fa-skype'           => 'Skype',
                    'fa fa-linkedin-square' => 'LinkedIn Square',
                    'fa fa-github'          => 'GitHub(octocat)'
                )
            );
        wp_enqueue_style('font-awesome');
        foreach ($font_supports as $font => $data) {
            if($font == 'monosocial')
            {
                wp_enqueue_style('vc_monosocialiconsfont');
                continue;
            }
            wp_enqueue_style('font-' . $font);
            wp_enqueue_style('vc_' . $font);
        }
        wp_enqueue_style('vc-iconpicker', EF4Functions::asset('js/vcIconPicker/css/jquery.fonticonpicker.min.css'));
        wp_enqueue_style('vc-iconpicker-grey-theme', EF4Functions::asset('js/vcIconPicker/themes/grey-theme/jquery.fonticonpicker.vcgrey.min.css'));
        wp_enqueue_script('ef4u_admin', EF4Functions::asset('js/ef4u_admin.js'));
        $autocomplete = array(
            'facebook'  => array(
                'class'=> 'fa fa-facebook',
                'font'=>'awesome'
            ),
            'google'  => array(
                'class'=> 'fa fa-google-plus',
                'font'=>'awesome'
            ),
            'twitter'  => array(
                'class'=> 'fa fa-twitter',
                'font'=>'awesome'
            ),
            'youtube'  => array(
                'class'=> 'fa fa-youtube-play',
                'font'=>'awesome'
            ),
            'instagram'  => array(
                'class'=> 'fa fa-instagram',
                'font'=>'awesome'
            ),
            'skype'  => array(
                'class'=> 'fa fa-skype',
                'font'=>'awesome'
            ),
            'linkedin'  => array(
                'class'=> 'fa fa-linkedin-square',
                'font'=>'awesome'
            ),
            'github'  => array(
                'class'=> 'fa fa-github',
                'font'=>'awesome'
            ),
        );
        $autocomplete = apply_filters('ef4_autocomplete_social_icon', $autocomplete);
        wp_localize_script('ef4u_admin', 'ef4_iconpicker',
            array(
                'fonts'        => $font_supports,
                'settings'     => array(
                    'limit' => 500,
                ),
                'autocomplete' => $autocomplete
            ));
        $user_info = $this->get_info($user);
        $extend_social = $user_info['extend_social'];
        if(is_string($extend_social))
            $extend_social = maybe_unserialize($extend_social);
        ?>
        <div class="ef4u_field_group" data-field="extend_social">
            <h3><?php _e("Extend Social", "ef4-framework"); ?></h3>
            <input type="hidden" name="ef4u_group_data" value="">
            <input type="hidden" name="ef4u_extend_social"
                   value="<?php echo esc_attr(json_encode($extend_social)) ?>">
            <textarea style="display: none" class="single_row_template"><tr class="ef4_single_social">
                    <th><label><?php _e("Url ({_index_})", 'ef4-framework'); ?><p
                                    class="description"><?php _e("Add your social Url"); ?></p></label></th>
                    <td style="width:400px;vertical-align:top">
                        <input type="text"
                               value=""
                               class="regular-text social_url_field"/>
                        <br/>
                        <span class="description"></span>
                    </td>
                        <td>
                    <div class="vc-iconpicker-wrapper ef4_iconpicker_field">
                            <div class="vc-icons-selector fip-vc-theme-grey" style="position: relative;">
                                <div class="selector">
                                    <span class="selected-icon">
                                        <i class="">
                                        </i>
                                    </span>
                                    <span class="selector-button">
                                        <i class="fip-fa fa fa-arrow-down"></i>
                                    </span>
                                    <span class="remove-button">
                                        <i class="fip-fa fa fa fa-trash-o"></i>
                                    </span>
                                </div>
                                <div class="selector-popup" style="display: none;">
                                    <div class="selector-search">
                                        <input type="text" value="" placeholder="Search Icon"
                                               class="icons-search-input"><i
                                                class="fip-fa fa fa-search"></i></div>
                                    <div class="selector-font selector-category">
                                        <select class="icon-font-select icon-category-select">
                                            <?php foreach (array_keys($font_supports) as $font): ?>
                                                <option value="<?php echo esc_attr($font) ?>"><?php esc_html_e('Font: ' . $font, 'ef4-framework') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="fip-icons-container"></div>
                                </div>
                            </div>
                    </div>
                    </td>
                </tr></textarea>
            <table class="form-table" id="ef4-user-extral-information">
                <tr>
                    <th></th>
                    <td>
                        <a href="#" class="button ef4u_add_more_button"
                           class="button"><?php esc_html_e('Add more social url (+)', 'ef4-framework') ?></a>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }

    public function get_info($user_check, $field = '')
    {
        if ($user_check instanceof WP_User)
            $user = $user_check;
        elseif (is_email($user_check))
            $user = get_user_by('email', $user_check);
        elseif (is_numeric($user_check))
            $user = get_user_by('id', $user_check);
        else
            $user = get_user_by('login', $user_check);
        if (!$user instanceof WP_User)
            return false;
        $use_meta = get_user_meta($user->ID);
        $info = array(
            'user'    => $user,
            'contact' => array(
                'email'=>$user->user_email
            )
        );
        $contact_method = wp_get_user_contact_methods();
        foreach ($contact_method as $key => $label) {
            $info['contact'][$key] = (isset($use_meta[$key])) ? $use_meta[$key] : '';
        }
        if (!empty($field)) {
            if (array_key_exists($field, $info))
                $info = $info[$field];
            else
                $info = (array_key_exists($name = self::create_param_name($field), $use_meta) && isset($use_meta[$name][0])) ? maybe_unserialize($use_meta[$name][0]) : array();
        } else {
            foreach (self::$extends_meta_field as $field)
                $info[$field] = (array_key_exists($name = self::create_param_name($field), $use_meta) && isset($use_meta[$name][0])) ? maybe_unserialize($use_meta[$name][0]) : array();
        }
        if(is_string($info))
            $info = maybe_unserialize($info);
        return $info;
    }
}

add_action('ef4_service_loaded', 'ef4_user_info');
function ef4_user_info($user = '', $field = '')
{
    global $ef4_user_info;
    if (!$ef4_user_info instanceof EF4UserInfo)
        $ef4_user_info = EF4UserInfo::instance();
    if (empty($user))
        return $ef4_user_info;
    else
        return $ef4_user_info->get_info($user, $field);
}