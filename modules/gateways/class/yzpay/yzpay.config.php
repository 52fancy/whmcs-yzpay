<?php
if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

class yzpay_config{   
	function get_configuration (){
		global $CONFIG;
		$extra_config = [
			"kdt_id" => ["FriendlyName" => "店铺ID", "Type" => "text", "Size" => "34"],
			"client_id" => ["FriendlyName" => "client_id", "Type" => "text",  "Size" => "34"],
			"client_secret" => ["FriendlyName" => "client_secret", "Type" => "text",  "Size" => "34"],
			"type" => [
				'FriendlyName' => '',
				'Type' => 'dropdown',
				'Options' => [
					'yzpay' => "</option></select><div class='alert alert-info' role='alert' id='yzpay_notice' style='margin-bottom: 0px;'>您可能需要：<a type='button' class='btn btn-primary' href='https://console.youzanyun.com/login' target='_blank'><span class='glyphicon glyphicon-new-window'></span>有赞支付控制面板</a><br/><span style='color:red'>特别注意：</span><br/>异步通知的使用方法<br/>一定一定一定要仔细看<a href='https://github.com/52fancy/whmcs-yzpay/wiki/%E6%9C%89%E8%B5%9E%E4%BA%91%E4%BA%A4%E6%98%93API%E5%BC%80%E9%80%9A%E6%95%99%E7%A8%8B' target='_blank'><span class='glyphicon glyphicon-new-window'></span> 有赞支付开通教程</a></div><script>$('#yzpay_notice').prev().hide();</script><select style='display:none'>"
				]
			]			
		];
				
		$base_config = ["FriendlyName" => ['Type' => 'System','Value' => '有赞支付(52Fancy)']];
		
		$config = array_merge($base_config,$extra_config);
		$config["author"] = [
			'FriendlyName' => '',
			'Type' => 'dropdown',
			'Options' => [
				'52Fancy' => "</option></select><div class='alert alert-success' role='alert' id='yzpay_author' style='margin-bottom: 0px;'>该插件由 <a href='https://github.com/52fancy' target='_blank'><span class='glyphicon glyphicon-new-window'></span>52fancy</a> 开发 ，本插件为免费开源插件<a target='_blank' href='//shang.qq.com/wpa/qunwpa?idkey=be0fad3bb9d82603cc491c1b8f51513e647e8eff4f9be752c5cc41d5d5429b4e'><img border='0' src='//pub.idqqimg.com/wpa/images/group.png' alt='Whmcs支付宝插件' title='575798563'></a><br/><span class='glyphicon glyphicon-ok'></span> 支持 WHMCS 5/6/7 , 当前WHMCS 版本 ".$CONFIG["Version"]."<br/><span class='glyphicon glyphicon-ok'></span> 仅支持 PHP 5.4 以上的环境 , 当前PHP版本 ".phpversion()."</div><script>$('#yzpay_author').prev().hide();</script><style>* {font-family: Microsoft YaHei Light , Microsoft YaHei}</style><select style='display:none'>"
			]
		];
		return $config;
	}
}
