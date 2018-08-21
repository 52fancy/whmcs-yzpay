<?php
if (!defined("WHMCS")) {
	die("This file cannot be accessed directly");
}

class yzpay_link 
{
	public function get_paylink($params)
	{
		if (!function_exists("openssl_open"))
		{
			return '<span style="color:red">Fatal Error:管理员未开启openssl组件<br/>正常情况下该组件必须开启<br/>请开启openssl组件解决该问题</span>';
		}
		if (!function_exists("scandir"))
		{
			return '<span style="color:red">Fatal Error:管理员未开启scandir PHP函数<br/>支付宝Sdk 需要使用该函数<br/>请修改php.ini下的disable_function来解决该问题</span>';
		}
		if (empty($params['kdt_id']))
		{
			return "管理员未配置 店铺ID , 无法使用该支付接口";
		} 
		if (empty($params['client_id']))
		{
			return "管理员未配置 client_id  , 无法使用该支付接口";
		}	
		if (empty($params['client_secret']))
		{
			return "管理员未配置 client_secret  , 无法使用该支付接口";
		}	
		return $this->YzQrPay($params);
	}
	
	public function YzQrPay($params)
	{
		require_once __DIR__ ."/yzpay.class.php";

		$yzpay = new YzClient();
		$yzpay->setclientid($params['client_id']);
		$yzpay->setclientsecret($params['client_secret']);
		$yzpay->setkdtid($params['kdt_id']);
		$yzpay->setqrprice($params['amount']);
		$yzpay->setqrname("Billing"."-".$params['invoiceid']);	
		
		$result = $yzpay->YzQrPayServie();	
		if($result['response']['qr_code'])
		{
			$status = '
			<!--
				可用变量
				$id       - 账单ID
				$qr_url   - 支付链接
				$qr_code  - 二维码

			-->
			<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
			<script>
			setTimeout(stop, 300000);
			function stop()
			{
				clearInterval(paid_timeout);
			}
			
			var paid_timeout = setInterval(go, 3000);
			function go()
			{
				$.get("/viewinvoice.php?id={$id}",function(data)
					{
						if (data.indexOf("unpaid") == -1)
						{
							clearInterval(paid_timeout);
							alert("支付完成");
							window.location.href = "/cart.php?a=complete";
						}
					}
				);
			}
			</script>
			<a href= "{$qr_url}" ><img src= "{$qr_code}" /><br><img src="https://ws2.sinaimg.cn/large/006f1tRAly1fuhfz1u8fxj30g405kwf9.jpg" style="width:120px;"><br>支付宝 - 微信 - 银联支付</a>';
			$status_raw = str_replace(['{$id}','{$qr_url}','{$qr_code}'],[$params['invoiceid'],$result['response']['qr_url'],$result['response']['qr_code']],$status);
            return $status_raw;
		}else
		{
			return "二维码生成失败";
		}	
	}	
}
