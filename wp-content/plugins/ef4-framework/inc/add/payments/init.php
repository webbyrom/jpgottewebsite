<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 7/24/2018
 * Time: 3:17 PM
 */
require_once 'class/Payments.php';
require_once 'class/Settings.php';
require_once 'class/Api.php';
require_once 'class/Ajax.php';

add_filter('ef4_extends_library', 'ef4_attach_payment_library',12);
function ef4_attach_payment_library($lib = [])
{
    if (!is_array($lib))
        $lib = [];
    $lib = array_merge($lib, [
        'payment'         => \ef4_payment\Payments::class,
        'payment_setting' => \ef4_payment\Settings::class,
        'payment_api'     => \ef4_payment\Api::class,
        'payment_ajax'    => \ef4_payment\Ajax::class,
    ]);
    return $lib;
}