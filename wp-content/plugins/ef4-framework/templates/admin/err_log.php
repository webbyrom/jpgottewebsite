<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 5/26/2018
 * Time: 4:15 PM
 */
if (empty($post) || !($post instanceof WP_Post) || !(current_user_can('administrator') && empty($site_log)))
    return;
$date_format = 'Y-m-d H:i:s';
//normal_log
$log = get_post_meta($post->ID,'ef4-framwork_error',true);
if(!is_array($log))
{
    ?>
    <h1><?php esc_html_e('Empty error record','ef4-framwork') ?> </h1>
    <?php
    return;
}
?>
<table border="1" class="col-lg-12 th-center">
    <tr>
        <th></th>
        <th><?php esc_html_e('Type','ef4-framwork') ?></th>
        <th><?php esc_html_e('Message','ef4-framwork') ?></th>
        <th><?php esc_html_e('Add Info','ef4-framwork') ?></th>
    </tr>
<?php
foreach ($log as $time => $records)
{
    $first = true;
    foreach ($records as $record)
    {

        ?>
        <tr>
            <th><?php if($first) {echo esc_html(date($date_format, $time));$first = false;} ?></th>
            <td><?php echo esc_html($record['type']) ?></td>
            <td><?php echo esc_html($record['message']) ?></td>
            <td><?php echo esc_html(json_encode($record['add_info'])) ?></td>
        </tr>
        <?php
    }
}
?>
</table>
<style>
    table.th-center th{
        text-align: center;
    }
</style>
