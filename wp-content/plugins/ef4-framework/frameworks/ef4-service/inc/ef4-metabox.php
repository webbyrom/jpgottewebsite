<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 11/8/2017
 * Time: 3:21 PM
 */
class EF4Metabox
{
    const PREFIX = 'ef4_metabox';
    protected $_cpt;
    protected $_default;
    protected $_absolute_atts;
    protected $_add_atts;
    protected $_atts;
    protected $service;
    protected $settings = array();

    public function __construct(EF4Service $service, $custom_post_type)
    {
        $this->service = $service;
        $this->set_param('post_type', $custom_post_type);
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_post_value'));
    }
    public function save_post_value($post_id)
    {
        if (!isset($_POST[$this->get_param('post_type') . '_nonce']) || !wp_verify_nonce($_POST[$this->get_param('post_type') . '_nonce'], $this->get_param('post_type') . '_metabox_nonce'))
            return;
        if (!current_user_can('edit_post', $post_id))
            return;
        $atts = $this->get_atts();
        foreach ($atts as $att) {
            $param_name = EF4MetaTemplate::create_param_name($att['param_name']);
            $value = (isset($att['std'])) ? $att['std'] : '';
            if (isset($_POST[$param_name]))
                $value = $_POST[$param_name];
            $param_save_name = self::create_param_name($att['param_name']);
            update_post_meta($post_id, $param_save_name, $value);
        }
    }
    public static function recover_param_name($name)
    {
        $prefix = self::PREFIX.'_';
        if(strpos($name,$prefix) === 0)
            return substr($name,strlen($prefix));
        return $name;
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
        $metabox_heading = apply_filters($this->service->merge_name(self::PREFIX, 'heading'), $this->get_setting('heading', 'Attributes'), $this->get_param('post_type'));
        add_meta_box(
            $id,
            $metabox_heading,
            array($this, 'metabox_template'),
            $this->get_param('post_type')
        );
    }

    public function metabox_template($post)
    {
        $atts = $this->get_atts($post->ID);
        wp_nonce_field($this->get_param('post_type') . '_metabox_nonce', $this->get_param('post_type') . '_nonce');
        foreach ($atts as $att) {
            EF4MetaTemplate::generate_field($att);
        }
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
            'cpt'           => array(
                'post_type', 'pt', 'custom_post_type'
            ),
            'default'       => array(),
            'absolute_atts' => array(
                'required', 'required_attr', 'required_attrs', 'abs_attrs', 'abs_attr', 'absolute_attr', 'absolute_atts', 'abs_atts'
            ),
            'add_atts'      => array(
                'new_atts'
            ),
            'atts'          => array()
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

    public function set_required_attrs(array $atts)
    {
        foreach ($atts as $att)
            $this->set_required_attr($att);
    }

    public function set_required_attr($att)
    {
        if (!$this->service->validate($att, 'array') || !$this->service->validate($att['param_name'], 'string'))
            return false;
        $required = $this->get_param('required');
        if (!ef4_service()->validate($required, 'array'))
            $required = array();
        $required[$att['param_name']] = $att;
        $this->set_param('required', $required);
        return true;
    }

    public function add_atts(array $atts, $autosave = true)
    {
        foreach ($atts as $att)
            $this->add_att($att, false);
        if ($autosave)
            $this->save();
    }

    public function add_att(array $att_data, $autosave = true)
    {
        $att_data = wp_parse_args($att_data, array(
            'group'      => 'default',
            'type'       => 'textfield',
            'heading'    => '',
            'param_name' => '',
            'weight'     => '99'
        ));
        if (empty($att_data['param_name']))
            return;
        if (empty($att_data['group']))
            $att_data['group'] = 'default';
        if (empty($att_data['heading']))
            $att_data['heading'] = $att_data['param_name'];
        if ($autosave)
            $this->save();
    }

    public function save()
    {
        $required = $this->get_param('required');
        $add_atts = $this->get_param('add_atts');
        $atts = $this->get_atts();
        if (!empty($add_atts))
            foreach ($add_atts as $name => $args) {
                $atts[$name] = $args;
            }
        if (!empty($required))
            foreach ($required as $name => $args) {
                $atts[$name] = $args;
            }
        $this->set_param('atts', false);
        $this->set_param('add_atts', array());
        update_option($this->get_option_name('map'), $atts);
    }

    public function get_atts($post_id = '')
    {
        $atts = $this->get_param('atts');
        if (!is_array($atts))
            $atts = get_option($this->get_option_name('map'), array());
        $this->set_param('atts', $atts);
        $required = $this->get_param('required');
        if (!empty($required))
            foreach ($required as $name => $args)
            {
                if(isset($atts[$name]) && isset($atts[$name]['weight'])  )
                    $temp_weight = $atts[$name]['weight'];
                $atts[$name] =  $args;
                if(!empty($temp_weight) && (empty($atts[$name]['force_weight']) || !$atts[$name]['force_weight']))
                    $atts[$name]['weight'] = $temp_weight;
            }

        usort($atts, array($this, 'sort_atts_with_weight'));
        if(is_numeric($post_id))
            foreach ($atts as $key => $att)
            {
                $atts[$key]['value'] = get_post_meta($post_id, self::create_param_name($att['param_name']), true);
            }
        return $atts;
    }

    public function sort_atts_with_weight($a, $b)
    {
        if (is_array($a) && isset($a['weight']) && is_array($b) && isset($b['weight'])) {
            return intval($a['weight']) > intval($b['weight']);
        }
        return true;
    }

    protected function get_option_name($type)
    {
        if ($this->is_inited())
            switch ($type) {
                case 'map':
                    return $this->service->merge_name('ef4_metabox', $this->get_param('post_type'), 'map');
                    break;
            }
        return false;
    }

    public function editor_attribute()
    {
        $fields_available = EF4MetaTemplate::get_field_types_support();
        $field_editor_attr_global = array(
            array(
                'type'       => 'select',
                'options'    => $fields_available,
                'param_name' => 'type',
                'heading'    => 'Type',
            ),
            array(
                'type'        => 'textfield',
                'heading'     => 'ID (*)',
                'placeholder' => 'Field ID',
                'param_name'  => 'param_name'
            ),
            array(
                'type'        => 'textfield',
                'heading'     => 'Default value',
                'placeholder' => 'Default value',
                'param_name'  => 'std',
            ),
            array(
                'type'        => 'textfield',
                'heading'     => 'Heading',
                'placeholder' => 'Field heading',
                'param_name'  => 'heading',
            ),
            array(
                'type'        => 'textfield',
                'heading'     => 'Desciption',
                'placeholder' => 'Field description',
                'param_name'  => 'description'
            ),
        );
        $required = $this->get_param('required');
        $atts = $this->get_atts();
        foreach ($atts as $key => $att) {
            if (array_key_exists($att['param_name'], $required))
                $atts[$key]['is_static'] = 'true';
        }
        ?>
        <div class="hidden" id="ef4mt_raw_attribute_template">
            <textarea class="ef4mt_raw_single_field">
                <div class="postbox ef4mt_single_field">
                    <button type="button" class="handlediv" aria-expanded="true">
                        <span class="screen-reader-text"></span><span class="toggle-indicator"
                                                                      aria-hidden="true"></span>
                    </button>
                    <h2 class="ef4mt_field_title">
                        <span class=""><?php echo esc_html('New Attribute') ?></span></h2>
                    <div class="inside">
                        <?php EF4MetaTemplate::generate_fields($field_editor_attr_global); ?>

                        <div class="ef4mt_local_field_setting">
                        </div>
                        <div class="ef4_metafile_order_controller">
                            <span class="controller_up dashicons dashicons-arrow-up"></span>
                            <span class="controller_down dashicons dashicons-arrow-down"></span>
                        </div>
                        <div class="button button-large ef4_metafiles_remove_file"><?php echo esc_html('Remove this Attribute') ?></div>
                    </div>
                </div>
                </textarea>
            <?php foreach ($fields_available as $field_type => $field_title): ?>
                <div class="ef4mt_local_field_setting hidden" data-field="<?php echo esc_attr($field_type) ?>">
                    <?php EF4MetaTemplate::generate_editor_field($field_type) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center;width: 100%" class="ef4_metafiles_add_more_attribute">
            <div class="button button-primary button-large"><?php echo esc_html('Add More') ?></div>
        </div>
        <textarea id="ef4mt_atts_data" style="width: 100%"><?php echo esc_html(json_encode($atts)) ?></textarea>
        <?php
    }
}