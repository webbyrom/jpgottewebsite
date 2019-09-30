<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/8/2018
 * Time: 2:26 PM
 */
global $wp;
ef4()->enqueue_admin_assets();
$current_template_slug = 'settings';
$tabs = apply_filters('ef4_settings_tabs',[
//    'general'   => esc_html__('General', 'ef4-framework')
]);
$active_tab = !empty($_GET['tab']) ? $_GET['tab'] : array_keys($tabs)[0];
?>
<div class="wrap materialize ef4-container">
    <input type="hidden" id="nonce" value="<?php echo wp_create_nonce('ef4-settings') ?>">
    <input type="hidden" id="action" value="<?php echo esc_attr('ef4_save_settings') ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
        <div class="custom_card">
            <div class="header">
                <h3><?php esc_html_e('EF4 Settings','ef4-framework') ?></h3>
            </div>
            <div class="body">
                <div class="clearfix materialize">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class="nav nav-tabs">
                            <?php foreach ($tabs as $slug => $tab): ?>
                                <li class="<?php if ($slug == $active_tab) echo esc_attr('active') ?>">
                                    <a href="<?php echo esc_attr(ef4()->current_url(['tab'=>$slug])) ?>">
                                        <i class="material-icons"></i>
                                        <?php echo esc_html((is_string($tab)) ? $tab : $tab['title']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active">
                                <div class="row">
                                    <div class="col-lg-10 col-lg-offset-1">
                                        <?php ef4()->get_templates("admin/{$current_template_slug}-tabs/{$active_tab}.php"); ?>
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

