<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function yzpay_MetaData()
{
    return array(
        'DisplayName' => '有赞支付(52Fancy)',
        'APIVersion' => '1.1', // Use API Version 1.1
    );
}

function yzpay_config()  
{
    require_once __DIR__ ."/class/yzpay/yzpay.config.php";
    $config = new yzpay_config();
    return $config->get_configuration();
}

function yzpay_link($params)
{
    require_once __DIR__ ."/class/yzpay/yzpay.link.php";
    $link = new yzpay_link();
    return $link->get_paylink($params);
}