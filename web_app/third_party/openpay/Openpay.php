<?php 
/**
 * Openpay API v1 Client for PHP (version 1.0.0)
 * 
 * Copyright © Openpay SAPI de C.V. All rights reserved.
 * http://www.openpay.mx/
 * soporte@openpay.mx
 */

if (!function_exists('curl_init')) {
	throw new Exception('CURL PHP extension is required to run Openpay client.');
}
if (!function_exists('json_decode')) {
	throw new Exception('JSON PHP extension is required to run Openpay client.');
}
if (!function_exists('mb_detect_encoding')) {
	throw new Exception('Multibyte String PHP extension is required to run Openpay client.');
}

require(realpath(dirname(__FILE__)) . '/data/OpenpayApiError.php');
require(realpath(dirname(__FILE__)) . '/data/OpenpayApiConsole.php');
require(realpath(dirname(__FILE__)) . '/data/OpenpayApiResourceBase.php');
require(realpath(dirname(__FILE__)) . '/data/OpenpayApiConnector.php');
require(realpath(dirname(__FILE__)) . '/data/OpenpayApiDerivedResource.php');
require(realpath(dirname(__FILE__)) . '/data/OpenpayApi.php');

require(realpath(dirname(__FILE__)) . '/resources/OpenpayBankAccount.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayCapture.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayCard.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayCharge.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayCustomer.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayFee.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayPayout.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayPlan.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayRefund.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpaySubscription.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayTransfer.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayWebhook.php');
require(realpath(dirname(__FILE__)) . '/resources/OpenpayToken.php');
?>
