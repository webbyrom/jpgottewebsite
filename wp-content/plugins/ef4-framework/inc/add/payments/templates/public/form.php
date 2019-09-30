<?php
if (empty($post_type)) {
    return;
}
$form_views = [
    'form'=>[
        'path'=>"public/payments/{$post_type}/form.php",
    ],
    'result'=>[
        'path'=>"public/payments/{$post_type}/result.php",
        'default'=>"public/default/result.php"
    ],
    'loading'=>[
        'path'=>"public/payments/{$post_type}/loading.php",
        'default'=>"public/default/loading.php"
    ],
    'error'=>[
        'path'=>"public/payments/{$post_type}/error.php",
        'default'=>"public/default/error.php"
    ],
];
?>
<div class="modal fade ef4-payment-form view-container" data-target="<?php echo ef4()->get_hash($post_type) ?>" tabindex="-1"
     role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form class="view-single active view-initial" data-name="payment-form" data-group="form-container">
                <?php
                    ef4()->try_get_template($form_views['form']);
                ?>
            </form>
            <div class="view-single" data-name="payment-result" data-group="form-container">
                <?php ef4()->try_get_template($form_views['result']); ?>
            </div>
            <div class="view-single" data-name="payment-loading" data-group="form-container">
                <?php ef4()->try_get_template($form_views['loading']); ?>
            </div>
            <div class="view-single" data-name="payment-error" data-group="form-container">
                <?php ef4()->try_get_template($form_views['error']); ?>
            </div>
        </div>
    </div>
</div>
