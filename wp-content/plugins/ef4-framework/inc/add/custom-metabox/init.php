<?php
/**
 * Created by FsFlex.
 * User: VH
 * Date: 8/2/2018
 * Time: 3:19 PM
 */
require_once 'class/MetaBox.php';
require_once 'class/Settings.php';
require_once 'class/Templates.php';
//require_once 'class/Api.php';
//require_once 'class/Ajax.php';

add_filter('ef4_extends_library', 'ef4_attach_metabox_library',11);
function ef4_attach_metabox_library($lib = [])
{
    if (!is_array($lib))
        $lib = [];
    $lib = array_merge($lib, [
        'metabox'         => ef4_metabox\MetaBox::class,
        'metabox_setting' => ef4_metabox\Settings::class,
        'metabox_templates'     => ef4_metabox\Templates::class,
//        'payment_ajax'    => \ef4_payment\Ajax::class,
    ]);
    return $lib;
}