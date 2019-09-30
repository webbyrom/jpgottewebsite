<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/26/2018
 * Time: 8:38 AM
 */
if (empty($post) || !($post instanceof WP_Post))
    return;
$payment_type = get_post_meta($post->ID,'payment_type',true);
switch ($payment_type)
{
    case 'stripe':
        $label = __('Stripe Request Data','ef4-framework');
        $templates = 'admin/post-types/ef4_payment/log/stripe.php';
        break;
    case 'paypal':
        $label = __('Paypal Request Data','ef4-framework');
        $templates = 'admin/post-types/ef4_payment/log/paypal.php';
        break;
}
?>
    <div class="custom_card">
        <div class="body">
            <div class="materialize">
                <ul class="nav nav-tabs " role="tablist">
                    <?php if(!empty($label)): ?>
                        <li role="presentation">
                            <a href="#ef4-api_data" data-toggle="tab">
                                <i class="material-icons"></i>
                                <?php echo esc_html($label) ?></a>
                        </li>
                    <?php endif; ?>
                    <li role="presentation">
                        <a href="#ef4_error" data-toggle="tab">
                            <i class="material-icons"></i>
                            <?php esc_html_e('Error', 'ef4-framework') ?></a>
                    </li>
                    <li role="presentation">
                        <a href="#ef4_error" data-toggle="tab">
                            <i class="material-icons"></i>
                            <?php esc_html_e('Other', 'ef4-framework') ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <?php if(!empty($label)): ?>
                        <div role="tabpanel" class="tab-pane" id="ef4-api_data">
                            <div class="row">
                                <div class="col-lg-10 col-lg-offset-1">
                                    <?php ef4()->get_templates($templates,['post'=>$post]); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div role="tabpanel" class="tab-pane" id="ef4_error">
                        <div class="row">
                            <div class="col-lg-10 col-lg-offset-1">
                                <?php ef4()->get_templates('admin/err_log.php',['post'=>$post]); ?>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="ef4_log">
                        <div class="row">
                            <div class="col-lg-10 col-lg-offset-1">
                                <?php ef4()->get_templates('admin/notice_log.php',['post'=>$post]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
    .payment-status th,.payment-status input{
        text-align: center;
    }
</style>