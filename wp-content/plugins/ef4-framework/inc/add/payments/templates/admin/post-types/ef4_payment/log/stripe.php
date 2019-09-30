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
$data = get_post_meta($post->ID, 'stripe_charge_data', true);
if (empty($data)) {
    ?>
    <h3><?php esc_html_e('Empty Stripe Payment Data', 'ef4-framework') ?></h3>
    <?php
    return;
}
$static_data = [];
foreach ($data as $record) {
    if (empty($static_data)) {
        $static_data = [
            'id'            => $record['id'],
            'amount'        => $record['amount'],
            'created'       => $record['created'],
            'currency'      => $record['currency'],
            'customer'      => $record['customer'],
            'description'   => $record['description'],
            'livemode'      => $record['livemode'],
            'receipt_email' => $record['receipt_email'],
        ];
        if ($record['source']['object'] == 'card')
            $static_data['source'] = [
                'id'        => $record['source']['id'],
                'type'      => $record['source']['object'],
                'brand'     => $record['source']['brand'],
                'country'   => $record['source']['country'],
                'cvc_check' => $record['source']['cvc_check'],
                'funding'   => $record['source']['funding'],
            ];
        else
            $static_data['source'] = $record['source']['object'];
        break;
    }
}
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
            'captured'       => $record['captured'],
            'paid'           => $record['paid'],
            'refunded'       => $record['refunded'],
            'status'         => $record['status'],
            'risk_level'     => $record['outcome']['risk_level'],
            'seller_message' => $record['outcome']['seller_message'],
            'type'           => $record['outcome']['type'],
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
            <div id="check_stripe_payment_status" class="btn waves-effect"
                 style="width: 30%;margin: 15px"><?php esc_html_e('Check Status', 'ef4-framework') ?></div>
        </td>
    </tr>
</table>
<script>
    jQuery(document).ready(function ($) {
        var ajax_check_payment_running = false;
        $('#check_stripe_payment_status').on('click', function () {
            if(ajax_check_payment_running)
                return;
            ajax_check_payment_running = true;
            $(document).trigger('ef4.loading.on');
            $.ajax({
                type: "POST",
                url: '<?php echo admin_url('admin-ajax.php') ?>',
                data: {
                    action:'ef4_payment_check_stripe_payment_status',
                    nonce:'<?php echo wp_create_nonce('ef4-check-stripe-charge') ?>',
                    payment_id:'<?php echo esc_attr($post->ID) ?>',
                    charge_id:'<?php echo esc_attr(get_post_meta($post->ID,'stripe_charge_id',true)) ?>',
                },
                success: function (response) {
                    if(response.success == 'success')
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
