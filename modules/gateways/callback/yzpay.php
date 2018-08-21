<?php
/**
 * 有赞推送服务消息接收
 */
 
$json = file_get_contents('php://input');
$jsondata = json_decode($json, true);  										//处理为array数组

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';
	
use Illuminate\Database\Capsule\Manager as Capsule;
function convert_helper($invoiceid,$amount)
{
	$setting = Capsule::table("tblpaymentgateways")->where("gateway","yzpay")->where("setting","convertto")->first();
	//系统没多货币 , 直接返回
	if (empty($setting)){ return $amount; }
    
    
	//获取用户ID 和 用户使用的货币ID
	$data = Capsule::table("tblinvoices")->where("id",$invoiceid)->get()[0];
	$userid = $data->userid;
	$currency = getCurrency( $userid );

	// 返回转换后的
	return  convertCurrency( $amount , $setting->value  ,$currency["id"] );
}
		
if($jsondata['test'] != "true")											//判断消息是否测试
{	
	// Detect module name from filename.
	$gatewayModuleName = basename(__FILE__, '.php');
		
	// Fetch gateway configuration parameters.
	$gatewayParams = getGatewayVariables($gatewayModuleName);
		
	// Die if module is not active.
	if(!$gatewayParams['type']) {
		die("Module Not Activated");
	}	
	
	$client_id = $gatewayParams['client_id'];							//应用的 client_id
	$client_secret = $gatewayParams['client_secret'];					//应用的 client_secret
	$sign = md5($client_id."".$jsondata['msg']."".$client_secret);
	
	if($jsondata['mode'] == "1" and $sign == $jsondata['sign'] and $jsondata['type'] == "trade_TradePaid")	//判断消息推送的模式/消息是否伪造/消息的业务
	{
		echo '{"code":0,"msg":"success"}';
		#下面开始处理业务
		$imsg = json_decode(urldecode($jsondata['msg']),true);
		$amount = $imsg['full_order_info']['orders']['0']['payment']; 	//交易金额
		$title = $imsg['full_order_info']['orders']['0']['title']; 		//交易标题
		$id = $jsondata['id'];    										//有赞交易号
		$invoice_id = explode("-",$title)[1];
		
		$invoiceid = checkCbInvoiceID($invoice_id,$gatewayParams["name"]);
		$amount = convert_helper( $invoice_id, $amount );
		checkCbTransID($id);
		addInvoicePayment($invoiceid,$id,$amount,"0",$gatewayModuleName);
        logTransaction($gatewayParams['name'], $jsondata, "异步回调入账 #" . $invoiceid);
	}
}else{
	echo '{"code":0,"msg":"success"}';  								//健康状态检查
}
