<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/8/2018
 * Time: 2:26 PM
 */

$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
if (empty($_GET['post_type']) && isset($_GET['page']) && $_GET['page'] == 'ef4-settings_main') {
    $post_type_allow = apply_filters('ef4_custom_menu_settings', []);
    array_filter($post_type_allow, function($value) { return $value !== ''; });
    if (!empty($_GET['settings_of']))
        $post_type = $_GET['settings_of'];
    else
        $post_type = $post_type_allow[0];
}
$post_type_mask = empty($post_type) ? '' : "({$post_type})";
$current_template_slug = 'cpt-settings';
$tabs = apply_filters('ef4_custom_settings_tabs', [], $post_type);
if (empty($tabs)) {
    ?><h1><?php esc_html_e('Something Error!!!', 'ef4-framework') ?></h1><?php
    return;
}
ef4()->enqueue_admin_assets();
$active_tab = (!empty($_GET['tab']) && array_key_exists($_GET['tab'], $tabs)) ? $_GET['tab'] : array_keys($tabs)[0];
?>
<div class="wrap materialize ef4-container">
    <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('ef4-cpt-settings') ?>">
    <input type="hidden" id="action" value="<?php echo esc_attr('ef4_save_cpt_settings') ?>">
    <input type="hidden" id="post_type" name="post_type" class="input-settings"
           value="<?php echo esc_attr($post_type) ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="custom_card">
            <div class="header">
                <h3>
                    <?php if (empty($post_type_allow)) {
                        echo sprintf(__('EF4 Settings %s', 'ef4-framework'), $post_type_mask);
                    } else {
                        ?>
                        <?php esc_html_e('EF4 Settings - ', 'ef4-framework') ?>
                        <select class="change-redirect"
                                data-redirect="<?php echo esc_attr(ef4()->current_url(['settings_of' => '{{_value_}}'])) ?>">
                            <?php foreach ($post_type_allow as $slug) {
                                ?>
                                <option
                                value="<?php echo esc_attr($slug) ?>" <?php selected($post_type, $slug) ?>><?php echo esc_html($slug) ?></option><?php
                            } ?>
                        </select>
                        <?php
                    } ?>
                    <div class="btn waves-effect btn-remove-field" data-toggle="modal" data-target="#extends-editor-modal" style="float: right;">
                        <div class="dashicons dashicons-admin-generic"></div>
                    </div>
                </h3>
            </div>
            <div class="body">
                <div class="clearfix materialize">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="nav nav-tabs">
                            <?php foreach ($tabs as $slug => $tab): ?>
                                <li class="<?php if ($slug == $active_tab) echo esc_attr('active') ?>">
                                    <a href="<?php echo esc_attr(ef4()->current_url(['tab' => $slug])) ?>">
                                        <i class="material-icons"></i>
                                        <?php echo esc_html((is_string($tab)) ? $tab : $tab['title']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active">
                                <div class="row">
                                    <div class="col-lg-10 col-lg-offset-1">
                                        <?php ef4()->get_templates("admin/{$current_template_slug}-tabs/{$active_tab}.php", compact('post_type')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary waves-effect"
                        id="ef4-save-current-tab"><?php esc_html_e('Save', 'ef4-framework') ?>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="extends-editor-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="clearfix materialize">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs">
                        <?php
                        $ex_tabs = [
                            'exo_import' => __('Import', 'ef4-framework'),
                            'exo_export' => __('Export', 'ef4-framework'),
                        ];
                        $active_tab = 'exo_import';
                        foreach ($ex_tabs as $slug => $tab): ?>
                            <li class="<?php if ($slug == $active_tab) echo esc_attr('active') ?>">
                                <a href="#<?php echo esc_attr($slug) ?>">
                                    <i class="material-icons"></i>
                                    <?php echo esc_html((is_string($tab)) ? $tab : $tab['title']) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="exo_import" class="tab-pane active">
                            <div class="row">
                                <div class="col-lg-10 col-lg-offset-1">
                                    <form id="ef4_import_setting">
                                        <?php
                                        $templates = [
                                            [
                                                'type'    => 'textfield',
                                                'id'      => 'exo_import_post_type',
                                                'heading' => __('Post Type', 'ef4-framework'),
                                                'value'   => $post_type,
                                                'attr'    => [
                                                    'readonly' => 'readonly',
                                                ]
                                            ],
                                            [
                                                'type'    => 'file',
                                                'id'      => 'exo_import_file',
                                                'heading' => __('Setting File', 'ef4-framework'),
                                                'attr'=>['required'=>'required']
                                            ],
                                        ];
                                        foreach ($templates as $field) {
                                            do_action('ef4_admin_template', $field);
                                        }
                                        ?>
                                        <div class="row">
                                            <button type="submit" class="btn btn-primary waves-effect btn-text-ui"><?php esc_html_e('Import', 'ef4-framework') ?>
                                            </button>
                                            <div class="btn waves-effect btn-text-ui" data-dismiss="modal"><?php esc_html_e('Close', 'ef4-framework') ?></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" id="exo_export" class="tab-pane">
                            <div class="row">
                                <div class="col-lg-10 col-lg-offset-1">
                                    <?php
                                    $data_export = apply_filters('ef4_get_export_custom_settings_data','',['post_type'=>$post_type]);
                                    $file_name = "ef4-settings-{$post_type}.txt";
                                    $templates = [
                                        [
                                            'type'    => 'textfield',
                                            'id'      => 'exo_export_post_type',
                                            'heading' => __('Post Type', 'ef4-framework'),
                                            'value'   => $post_type,
                                            'attr'    => [
                                                'readonly' => 'readonly',
                                            ]
                                        ],
                                        [
                                            'type'    => 'textarea',
                                            'id'      => 'exo_export_result',
                                            'heading' => __('Data', 'ef4-framework'),
                                            'value'   => $data_export,
                                            'attr'    => [
                                                'readonly' => 'readonly',
                                                'rows'     => 15
                                            ]
                                        ],
                                    ];
                                    foreach ($templates as $field) {
                                        do_action('ef4_admin_template', $field);
                                    }
                                    ?>
                                    <a href="data:text/plain;charset=utf-8,<?php echo esc_attr($data_export) ?>" target="_blank" download="<?php echo esc_attr($file_name) ?>">
                                        <div class="btn btn-primary waves-effect btn-text-ui"><?php esc_html_e('Download', 'ef4-framework') ?></div>
                                    </a>
                                    <div class="btn waves-effect btn-text-ui" data-dismiss="modal"><?php esc_html_e('Close', 'ef4-framework') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        var nonce = "<?php echo wp_create_nonce('ef4-import-settings') ?>";
        $('#ef4_import_setting').on('submit',function (e) {
            e.preventDefault();
            var form = $(this);
            var data = new FormData();
            data.append('file_data',form.find('#exo_import_file')[0].files[0]);
            data.append('nonce',nonce);
            data.append('action','ef4_import_settings');
            data.append('post_type',form.find('#exo_import_post_type').val());
            send_import_request(data);
        });
        var ajax_check_running = false;
        function send_import_request(data) {
            ajax_check_running = true;
            $(document).trigger('ef4.loading.on');
            $.ajax({
                type: "POST",
                method: "POST",
                url: '<?php echo admin_url('admin-ajax.php') ?>',
                data:data,
                success: function (response) {
                    if (response.success == 'success')
                        window.location.reload();
                },
                cache: false,
                contentType: false,
                processData: false,
                dataType:'JSON'
            }).always(function () {
                ajax_check_running = false;
                $(document).trigger('ef4.loading.off');
            });
        }
    });
</script>



















