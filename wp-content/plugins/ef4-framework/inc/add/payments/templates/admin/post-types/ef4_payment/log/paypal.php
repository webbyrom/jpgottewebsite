<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/26/2018
 * Time: 11:23 AM
 */
if (empty($post) || !($post instanceof WP_Post))
    return;
$date_format = 'Y-m-d H:i:s';
$data = get_post_meta($post->ID, 'paypal_data_payment', true);
if (empty($data)) {
    ?>
    <h3><?php esc_html_e('Empty Paypal Payment Data', 'ef4-framework') ?></h3>
    <?php
    return;
}


$static_data = [];
foreach ($data as $record) {
    if (empty($static_data)) {
        $static_data = [
            'Payment ID'   => $record['id'],
            'Amount'       => $record['transactions'][0]['amount']['total'],
            'Currency'     => $record['transactions'][0]['amount']['currency'],
            'Description'  => !empty($record['transactions'][0]['description']) ? $record['transactions'][0]['description'] : '' ,
            'Create Time'  => $record['create_time'],
            'Approval Url' => $record['links']['1']['href'],
        ];
        break;
    }
}
foreach ($data as $record) {
    if (!empty($static_data['Payer Email'])
        && !empty($static_data['First Name'])
        && !empty($static_data['last_name'])
    ) break;
    if (empty($static_data['Payer Email'])
        && !empty($record['payer'])
        && !empty($record['payer']['payer_info'])
        && !empty($record['payer']['payer_info']['email'])
    )
        $static_data['Payer Email'] = $record['payer']['payer_info']['email'];
    if (empty($static_data['First Name'])
        && !empty($record['payer'])
        && !empty($record['payer']['payer_info'])
        && !empty($record['payer']['payer_info']['first_name'])
    )
        $static_data['First Name'] = $record['payer']['payer_info']['first_name'];
    if (empty($static_data['Last Name'])
        && !empty($record['payer'])
        && !empty($record['payer']['payer_info'])
        && !empty($record['payer']['payer_info']['last_name'])
    )
        $static_data['Last Name'] = $record['payer']['payer_info']['last_name'];
}
$payment_process = [
    __('Create payment') => 'success',
    __('Payer')          => 'unchecked',
    __('Create payment') => true,

];
$process_time_check = [strtotime($post->post_date)];
$payer_redirect_back_meta = get_post_meta($post->ID, 'paypal_data_payer_redirect_back', true);
if (!is_array($payer_redirect_back_meta))
    $payer_redirect_back_meta = [];
$payment_execution_meta = get_post_meta($post->ID, 'paypal_data_payment_execution', true);
if (!is_array($payment_execution_meta))
    $payment_execution_meta = [];
$process_time_check = array_merge($process_time_check, array_keys($payer_redirect_back_meta), array_keys($payment_execution_meta));
sort($process_time_check);
$executed_fail = false;
?>
<div class="fields-group">
    <div class="col-lg-2">
        <label class="custom_label"><?php esc_html_e('Payment Process', 'ef4-framework') ?></label>
    </div>
    <div class="col-lg-10">
        <table class="col-lg-12 payment-status">
            <tr>
                <th class="col-lg-4"></th>
                <th class="col-lg-3"><?php esc_html_e('Payment', 'ef4-framework') ?></th>
                <th class="col-lg-3"><?php esc_html_e('Payer', 'ef4-framework') ?></th>
                <th class="col-lg-3"><?php esc_html_e('Api', 'ef4-framework') ?></th>
            </tr>
            <?php for ($i = 0; $i < count($process_time_check); $i++) {
                if ($i < 1) {
                    $ps_payment = __('Created', 'ef4-framework');
                    $ps_payer = __('Submit Form', 'ef4-framework');
                    $ps_api = __('Waiting', 'ef4-framework');
                } else {
                    $time = $process_time_check[$i];
                    $ps_payment = '';
                    $ps_payer = '';
                    $ps_api = '';
                    if (!empty($payer_redirect_back_meta[$time]))
                        switch ($payer_redirect_back_meta[$time]) {
                            case 'approved':
                                $ps_payer = __('Approved in Paypal');
                                break;
                            case 'cancel':
                                $ps_payer = __('Cancel in Paypal');
                                break;
                            default:
                                $ps_payer = __('Undefined Action');
                                break;
                        }
                    if (!empty($payment_execution_meta[$time])) {
                        switch ($payment_execution_meta[$time]) {
                            case 'success':
                                $executed_fail = false;
                                $ps_api = __('Payment Executed');
                                break;
                            case 'error':
                                $executed_fail = true;
                                $ps_api = __('Executed fail !!!');
                                break;
                            default:
                                $ps_api = __('Undefined Action');
                                break;
                        }
                    }
                }
                ?>
                <tr>
                <th><?php echo date($date_format, $process_time_check[$i]) ?></th>
                <td>
                    <div class="form-group no-margin-bot">
                        <div class="form-line">
                            <input class="form-control" value="<?php echo esc_attr($ps_payment) ?>" readonly="readonly">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group no-margin-bot">
                        <div class="form-line">
                            <input class="form-control" value="<?php echo esc_attr($ps_payer) ?>" readonly="readonly">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="form-group no-margin-bot">
                        <div class="form-line">
                            <input class="form-control" value="<?php echo esc_attr($ps_api) ?>" readonly="readonly">
                        </div>
                    </div>
                </td>
                </tr><?php
            }
            if($executed_fail)
            {
                ?>
                <tr>
                    <th></th>
                    <td>
                    </td>
                    <td>
                    </td>
                    <th>
                        <div id="paypal_executed_payment" class="btn waves-effect" style="margin:10px;"><?php esc_html_e('Execute', 'ef4-framework') ?></div>
                    </th>
                </tr>
                <?php
            }?>
        </table>
    </div>
</div>
<?php
foreach ($static_data as $key => $value):
    switch ($key) {
        case 'created':
            $value = date($date_format, $value);
    }
    if (is_bool($value))
        $value = ($value) ? 'true' : 'false';
    ?>
    <div class="fields-group">
        <div class="col-lg-2"><label class="custom_label"><?php echo esc_html($key) ?></label></div>
        <div class="col-lg-10">
            <div class="form-group no-margin-bot">
                <?php if (is_array($value)):
                    foreach ($value as $sub_key => $sub_val) {
                        ?>
                        <div class="col-lg-2"><label><?php echo esc_html($sub_key) ?></label></div>
                        <div class="col-lg-10">
                            <div class="form-line">
                                <input class="form-control" value="<?php echo esc_attr($sub_val) ?>"
                                       readonly="readonly">
                            </div>
                        </div>
                        <?php
                    }
                else: ?>
                    <div class="form-line">
                        <input class="form-control" value="<?php echo esc_attr($value) ?>" readonly="readonly">
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php endforeach;
?>
<h3><?php esc_html_e('Payment Status', 'ef4-framework') ?></h3>
<p><?php esc_html_e('---- " ---- is same as above.', 'ef4-framework') ?></p>
<table class="payment-status">
    <?php
    $heading_added = false;
    $count = 0;
    foreach ($data as $time => $record) {
        $count++;
        $data_use = [
            'State'        => $record['state'],
            'Payer Status' => (!empty($record['payer']) && !empty($record['payer']['status'])) ? $record['payer']['status'] : '',
        ];
        if (!$heading_added || ($count == count($data) && $count > 10)) {
            ob_start();
            ?>
            <tr>
                <th></th>
                <?php foreach (array_keys($data_use) as $title): ?>
                    <th><?php echo esc_html($title) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php
            $table_label = ob_get_clean();
        }
        if (!$heading_added) {
            echo $table_label;
            $heading_added = true;
        }
        ?>
        <tr>
            <th><?php echo esc_html(date($date_format, $time)) ?></th>
            <?php foreach ($data_use as $key => $value):
                if (is_bool($value))
                    $value = ($value) ? 'true' : 'false';
                $same_as_above = false;
                if (!empty($prev_value) && !empty($prev_value[$key]) && $prev_value[$key] == $value)
                    $same_as_above = true;
                ?>
                <td>
                    <div class="form-group no-margin-bot">
                        <div class="form-line">
                            <input class="form-control"
                                   value="<?php echo esc_attr(($same_as_above) ? '---- " ----' : $value) ?>"
                                   readonly="readonly">
                        </div>
                    </div>
                </td>
                <?php
                if (empty($prev_value))
                    $prev_value = [];
                $prev_value[$key] = $value;
            endforeach; ?>
        </tr>
        <?php
        if (($count == count($data) && $count > 10)) {
            echo $table_label;
        }
    }
    ?>
    <tr>
        <td colspan="<?php echo count($data_use) + 1 ?>" style="text-align: center">
            <div id="check_paypal_payment_status" class="btn waves-effect"
                 style="width: 30%;margin: 15px"><?php esc_html_e('Check Status', 'ef4-framework') ?></div>
        </td>
    </tr>
</table>
<script>
    jQuery(document).ready(function ($) {
        var ajax_check_payment_running = false,nonce = '<?php echo wp_create_nonce('ef4-check-paypal-payment') ?>';
        $('#paypal_executed_payment').on('click',function () {
            if (ajax_check_payment_running)
                return;
            ajax_check_payment_running = true;
            $(document).trigger('ef4.loading.on');
            $.ajax({
                type: "POST",
                url: '<?php echo admin_url('admin-ajax.php') ?>',
                data: {
                    action: 'ef4_payment_execute_paypal_payment',
                    nonce: nonce,
                    payment_id: '<?php echo esc_attr($post->ID) ?>',
                    paypal_id: '<?php echo esc_attr(get_post_meta($post->ID, 'paypal_data_payment_id', true)) ?>',
                },
                success: function (response) {
                    if (response.success == 'success')
                        window.location.reload();
                },
                dataType: 'JSON'
            }).always(function () {
                ajax_check_payment_running = false;
                $(document).trigger('ef4.loading.off');
            });
        });
        $('#check_paypal_payment_status').on('click', function () {
            if (ajax_check_payment_running)
                return;
            ajax_check_payment_running = true;
            $(document).trigger('ef4.loading.on');
            $.ajax({
                type: "POST",
                url: '<?php echo admin_url('admin-ajax.php') ?>',
                data: {
                    action: 'ef4_payment_check_paypal_payment_status',
                    nonce: nonce,
                    payment_id: '<?php echo esc_attr($post->ID) ?>',
                    paypal_id: '<?php echo esc_attr(get_post_meta($post->ID, 'paypal_data_payment_id', true)) ?>',
                },
                success: function (response) {
                    if (response.success == 'success')
                        window.location.reload();
                },
                dataType: 'JSON'
            }).always(function () {
                ajax_check_payment_running = false;
                $(document).trigger('ef4.loading.off');
            });
        });
    })
</script>
