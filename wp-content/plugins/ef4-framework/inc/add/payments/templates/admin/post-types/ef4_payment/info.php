<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 7/9/2018
 * Time: 10:54 AM
 */
if (empty($post) || !($post instanceof WP_Post))
    return;
ef4()->enqueue_admin_assets();
$specials_key = [
    'request_url'    => [
        'type'  => 'url',
        'value' => 'meta:request_url',
        'title' => __('Payments From', 'ef4-framework')
    ],
    'payment_type'   => [
        'type'  => 'text',
        'value' => 'meta:payment_type',
        'title' => __('Payments Type', 'ef4-framework')
    ],
    'items'          => [
        'type'  => 'items',
        'value' => json_encode(get_post_meta($post->ID, 'items', true)),
        'title' => __('Items', 'ef4-framework')
    ],
    'amount_preview' => [
        'type'  => 'text',
        'value' => 'meta:amount_preview',
        'title' => __('Total Amount', 'ef4-framework')
    ]
    ,
    'currency'       => [
        'type'  => 'currency',
        'value' => 'meta:currency',
        'title' => __('Currency', 'ef4-framework')
    ],
    'description'    => [
        'type'  => 'text',
        'value' => 'meta:description',
        'title' => __('Description', 'ef4-framework')
    ],
];
$target_post_type = get_post_meta($post->ID, 'items_source', true);
$meta_name_swap = apply_filters(
    'ef4_custom_get_settings',
    [],
    ['post_type' => $target_post_type, 'name' => 'meta_name_swap']
);
$add_keys = [];
foreach ($meta_name_swap as $field) {
    $add_keys[] = [
        'type'  => 'text',
        'value' => "meta:{$field['meta_name']}",
        'title' => $field['title']
    ];
}
$data = array_merge($add_keys, $specials_key);
$form_type = apply_filters(
    'ef4_custom_get_settings',
    '',
    ['post_type' => $target_post_type, 'name' => 'form_type']
);
?>
<div class="custom_card">
    <div class="body">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="materialize">
                    <?php
                    foreach ($data as $id => $record):
                        switch ($record['type']) {
                            case 'items':
                                switch ($form_type) {
                                    case 'donate':
                                        $items = json_decode($record['value'], true);
                                        if (!is_array($items))
                                            break;
                                        ?>
                                        <div class="row">
                                            <div class="fields-group row">
                                                <div class="col-lg-2 no-margin-bot">
                                                    <label class="custom_label"
                                                           for="field72609"><?php esc_html_e('Donated to') ?></label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <div class="form-group no-margin-bot">
                                                        <?php foreach ($items as $key => $value) {
                                                            $value = wp_parse_args($value, ['id' => '', 'name' => ''])
                                                            ?>
                                                            <div class="row">
                                                                <div class="col-lg-10">
                                                                    -
                                                                    <a href="<?php echo get_permalink($value['id']) ?>"><?php echo sprintf(__('%s (ID: %d)', 'ef4-framework'), $value['name'], $value['id']) ?></a>
                                                                    -
                                                                    <a href="<?php echo get_edit_post_link($value['id']) ?>"><?php esc_html_e('Edit', 'ef4-framework') ?></a>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        break;
                                    case 'purchase':
                                        $items = json_decode($record['value'], true);
                                        if (!is_array($items))
                                            break;
                                        ?>
                                        <div class="row">
                                            <div class="fields-group row">
                                                <div class="col-lg-2 no-margin-bot">
                                                    <label class="custom_label"
                                                           for="field72609"><?php esc_html_e('Donated to') ?></label>
                                                </div>
                                                <div class="col-lg-10">
                                                    <table class="col-lg-12 table-text-center">
                                                        <?php
                                                        $total = 0;
                                                        $currency = '';
                                                        $index = 1;
                                                        ob_start();
                                                        foreach ($items as $key => $value) {
                                                            $value = wp_parse_args($value, [
                                                                'id'       => '',
                                                                'quantity' => '',
                                                                'name'     => '',
                                                                'currency' => '',
                                                                'price'    => ''
                                                            ]);
                                                            $currency = $value['currency'];
                                                            $amount = $value['price'] * $value['quantity'];
                                                            $total += $amount;
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="form-group no-margin-bot">
                                                                        <div class="form-line">
                                                                            <input class="form-control"
                                                                                   value="<?php echo esc_attr($index++) ?>"
                                                                                   readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group no-margin-bot">
                                                                        <div class="form-line">
                                                                            <input class="form-control"
                                                                                   value="<?php echo esc_attr($value['name']) ?>"
                                                                                   readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group no-margin-bot">
                                                                        <div class="form-line">
                                                                            <input class="form-control"
                                                                                   value="<?php echo esc_attr($value['price']) ?>"
                                                                                   readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group no-margin-bot">
                                                                        <div class="form-line">
                                                                            <input class="form-control"
                                                                                   value="<?php echo esc_attr($value['quantity']) ?>"
                                                                                   readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group no-margin-bot">
                                                                        <div class="form-line">
                                                                            <input class="form-control"
                                                                                   value="<?php echo esc_attr($amount) ?>"
                                                                                   readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        $content = ob_get_clean();
                                                        $currency_data = ef4()->get_currency_data([],$currency);
                                                        $symbol = $currency_data['symbol'];
                                                        ?>
                                                        <tr>
                                                            <th class="col-lg-1"></th>
                                                            <th class="col-lg-6"><?php esc_html_e('Item Name','ef4-framework') ?></th>
                                                            <th class="col-lg-2"><?php echo sprintf(__('Price (%s)','ef4-framework'),$symbol); ?></th>
                                                            <th class="col-lg-1"><?php esc_html_e('Quantity','ef4-framework') ?></th>
                                                            <th class="col-lg-2"><?php echo sprintf(__('Amount (%s)','ef4-framework'),$symbol); ?></th>
                                                        </tr>
                                                        <?php echo ($content) ?>
                                                        <tr>
                                                            <th colspan="4" style="text-align: right"><?php echo sprintf(__('Total (%s) :','ef4-framework'),$symbol); ?></th>
                                                            <th>
                                                                <div class="form-group no-margin-bot">
                                                                    <div class="form-line">
                                                                        <input class="form-control"
                                                                               value="<?php echo esc_attr($total) ?>"
                                                                               readonly="readonly">
                                                                    </div>
                                                                </div>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        break;
                                    default:
                                        do_action('ef4_admin_template', [
                                            'type'    => 'textfield',
                                            'heading' => $record['title'],
                                            'value'   => $record['value'],
                                            'id'      => 'field' . rand(10000, 99999),
                                        ]);
                                        break;
                                }
                                break;
                            case 'currency':
                                do_action('ef4_admin_template', [
                                    'type'    => 'textfield',
                                    'heading' => $record['title'],
                                    'value'   => ef4()->parse_amount($record['value'], [
                                        'amount'   => '',
                                        'mask'     => '{{short}} ({{symbol}}) - {{full}}',
                                        'currency' => $record['value']
                                    ]),
                                    'id'      => 'field' . rand(10000, 99999),
                                ]);
                                break;
                            default:
                                do_action('ef4_admin_template', [
                                    'type'    => 'textfield',
                                    'heading' => $record['title'],
                                    'value'   => ef4()->parse_post_data($record['value'], $post),
                                    'id'      => 'field' . rand(10000, 99999),
                                ]);
                                break;
                        }
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>