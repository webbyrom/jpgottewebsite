<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/31/2018
 * Time: 11:21 AM
 */
if (empty($post) || !($post instanceof WP_Post))
    return;
ef4()->enqueue_admin_assets();
$meta = apply_filters('ef4_get_post_meta', [], $post);
$default_index = 9999;
$fields_ordered = [];
foreach ($fields as $key => $field)
{
    $index = !empty($field['order']) ? $field['order'] :  $default_index;
    $order_key =  str_pad($index,9,'0',STR_PAD_LEFT)."-{$key}";
    $fields_ordered[$order_key] = $field;
}
ksort($fields_ordered);
?>
<div class="custom_card ef4-metabox">
    <div class="body">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="materialize">
                    <?php
                    foreach ($fields_ordered as $field) {
                        if (empty($meta[$field['id']]) && isset($field['default']))
                            $meta[$field['id']] = $field['default'];
                        $field['value'] = isset($meta[$field['id']]) ? $meta[$field['id']] : '';
                        if (!empty($field['readonly']) && $field['readonly'] == 'yes')
                        {
                            $field['id'] = 'readonly-' . rand(100000, 900000);
                            $field['attr'] = ['readonly'=>'readonly'];
                        }
                        do_action('ef4_metabox_template', $field);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>