<?php
interface PaymentGatewayInterface {
    public function initialize($config);
    public function createTransaction($amount, $currency, $orderId, $callbackUrl, $returnUrl);
}
?>