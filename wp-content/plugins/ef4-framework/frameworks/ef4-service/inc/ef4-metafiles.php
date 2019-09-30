<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 11/16/2017
 * Time: 2:15 PM
 */
class EF4MetaFiles
{
    const PREFIX = 'ef4_metafiles';
    protected $_cpt;
    protected $service;
    protected $settings = array();

    public function __construct(EF4Service $service, $custom_post_type)
    {
        $this->service = $service;
        $this->set_param('post_type', $custom_post_type);
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_post_value'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    public function enqueue_admin_assets()
    {
        if(get_current_screen()->id !== $this->get_param('post_type'))
            return;
        wp_localize_script('ef4_metafiles_admin_js','ef4_metafiles',array(
                'area_id'=>$this->service->merge_name(self::PREFIX, $this->get_param('post_type')),
        ));
    }
    public function save_post_value($post_id)
    {
        if (!isset($_POST['ef4_metafile_data_save']))
            return;
        if (!isset($_POST[$this->get_param('post_type') . '_nonce']) || !wp_verify_nonce($_POST[$this->get_param('post_type') . '_nonce'], $this->get_param('post_type') . '_metabox_nonce'))
            return;
        if (!current_user_can('edit_post', $post_id))
            return;
        $ef4_metafile_data_save = json_decode(stripslashes($_POST['ef4_metafile_data_save']), true);
        if(!is_array($ef4_metafile_data_save))
            return;
        $field_allow = array(
                'name','source','icon','access_type'
        );
        $field_required = array(
                'name','source'
        );
        $data_save = array();
        foreach ($ef4_metafile_data_save as $file_save)
        {
            $file_valid = array();
            $is_fail = false;
            foreach ($field_required as $required)
            {
                if(!isset($file_save[$required]) || empty(trim($file_save[$required])))
                {
                    $is_fail = true;
                    break;
                }
            }
            if($is_fail)
                continue;
            foreach ($field_allow as $field)
            {
                $file_valid[$field] = $file_save[$field];
            }
            $data_save[] = $file_valid;
        }
        $data_save = base64_encode(json_encode($data_save));
        update_post_meta($post_id,$this->get_meta_name(),$data_save);
    }
    public function get_files($post_id)
    {
        $files = get_post_meta($post_id,$this->get_meta_name(),true);
        if(is_string($files))
            $files = json_decode(base64_decode($files));
        return $files;
    }
    public function get_meta_name()
    {
        return $this->service->merge_name(self::PREFIX,$this->get_param('cpt'),'data');
    }
    public static function create_param_name($name)
    {
        return self::PREFIX . '_' . $name;
    }

    public function add_settings(array $settings)
    {
        $cr_settings = $this->settings;
        foreach ($settings as $name => $value) {
            if ($this->service->validate($name))
                $cr_settings[$name] = $value;

        }
        $this->settings = $cr_settings;
    }

    public function get_setting($name, $default = '')
    {
        $value = $default;
        if (isset($this->settings[$name]))
            $value = $this->settings[$name];
        return $value;
    }

    public function add_meta_box()
    {
        $id = $this->service->merge_name(self::PREFIX, $this->get_param('post_type'));
        $metabox_heading = apply_filters($this->service->merge_name(self::PREFIX, 'heading'), $this->get_setting('heading', 'Files Attach'), $this->get_param('post_type'));
        add_meta_box(
            $id,
            $metabox_heading,
            array($this, 'metabox_template'),
            $this->get_param('post_type')
        );
    }

    public function metabox_template($post)
    {
        $file_type_options = apply_filters('ef4_metafile_file_types_allow', array(
            'public'  => 'Public',
            'private' => 'Private'
        ));
        $files_attach = $this->get_files($post->ID);
        if(is_string($files_attach))
            $files_attach = maybe_unserialize($files_attach);
        wp_nonce_field($this->get_param('post_type') . '_metabox_nonce', $this->get_param('post_type') . '_nonce');
        ?>
        <textarea class="ef4_metafile_single_template hidden">
            <div class="postbox ef4_single_file_attach">
            <button type="button" class="handlediv" aria-expanded="true">
                <span class="screen-reader-text"></span><span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="ef4_metafile_file_title"><span
                        class="ef4_metafile_preview_file_name"><?php echo esc_html('New File') ?></span></h2>
            <div class="inside">
                <div class="rwmb-field">
                    <div class="rwmb-label">
                        <label for="ef4_metafile_input_name"><?php echo esc_html('Name') ?></label>
                    </div>
                    <div class="rwmb-input">
                        <input type="text" class="ef4_metafile_input_name" name="ef4_metafile_input_name" value=""
                               placeholder="File name">
                        <p class="description"></p>
                    </div>
                </div>
                <div class="rwmb-field">
                    <div class="rwmb-label">
                        <label for="ef4_metafile_input_icon"><?php echo esc_html('Icon') ?></label>
                    </div>
                    <div class="rwmb-input ef4_file_icon_attach">
                        <div class="ef4_icon_preview"></div>
                        <input type="hidden" class="ef4_metafile_input_icon" name="ef4_metafile_input_icon"
                               value="<?php echo esc_attr($this->service->assets('/images/file_icon_default.png')) ?>"
                               placeholder="">
                        <input type="button" name="image_upload" value="<?php echo esc_attr('Select icon') ?>"
                               class="button ef4_icon_select_btn">
                        <input type="button" value="<?php echo esc_attr('Remove icon') ?>"
                               class="button ef4_remove_icon_select_btn">
                        <p class="description"></p>
                    </div>
                </div>
                <div class="rwmb-field">
                    <div class="rwmb-label">
                        <label for="ef4_metafile_input_source"><?php echo esc_html('Url') ?></label>
                    </div>
                    <div class="rwmb-input">
                        <input type="text" class="ef4_metafile_input_source" name="ef4_metafile_input_source"
                               placeholder="">
                        <input type="button" name="image_upload" value="<?php echo esc_attr('Select File') ?>"
                               class="button ef4_attach_file_btn">
                        <p class="description"></p>
                    </div>
                </div>
                <div class="rwmb-field">
                    <div class="rwmb-label">
                        <label for="ef4_metafile_input_access_type"><?php echo esc_html('Access type') ?></label>
                    </div>
                    <div class="rwmb-input">
                        <select class="rwmb-select ef4_metafile_input_access_type"
                                name="ef4_metafile_input_access_type">
                            <?php foreach ($file_type_options as $value => $title): ?>
                                <option value="<?php echo esc_attr($value) ?>"><?php echo esc_html($title) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"></p>
                    </div>
                </div>
                    <div class="ef4_metafile_order_controller">
                      <span class="controller_up dashicons dashicons-arrow-up"></span>
                      <span class="controller_down dashicons dashicons-arrow-down"></span>
                </div>
            <div class="button button-large ef4_metafiles_remove_file"><?php echo esc_html('Remove This File') ?></div>
            </div>
        </div>
        </textarea>
        <div style="text-align: center;width: 100%" class="ef4_metafiles_add_more_file">
            <div class="button button-primary button-large"><?php echo esc_html('Add More') ?></div>
        </div>
        <textarea class="hidden" name="ef4_metafile_data_save"><?php echo esc_html(json_encode($files_attach)) ?></textarea>
        <?php
    }

    public function is_inited()
    {
        $required = array(
            'cpt'
        );
        foreach ($required as $item) {
            $var_name = '_' . $item;
            if (empty($this->$var_name))
                return false;
        }
        return true;
    }

    public function get_true_param_name($var_name)
    {
        $true_name = '_' . $var_name;
        $name_map = array(
            'cpt' => array(
                'post_type', 'pt', 'custom_post_type'
            ),
        );
        if (key_exists($var_name, $name_map))
            return $true_name;
        foreach ($name_map as $name => $wrap)
            if (in_array($var_name, $wrap))
                return '_' . $name;
        return false;
    }

    public function set_param($param_name, $value)
    {
        $true_param_name = $this->get_true_param_name($param_name);
        if (!empty($true_param_name))
            $this->$true_param_name = $value;
    }

    public function get_param($param_name)
    {
        $true_param_name = $this->get_true_param_name($param_name);
        if (!empty($true_param_name))
            return $this->$true_param_name;
        return '';
    }

    public function set_post_type($post_type)
    {
        if (ef4_service()->validate($post_type, 'string'))
            $this->_cpt = $post_type;
    }

}