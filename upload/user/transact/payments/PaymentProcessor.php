<?php
class PaymentProcessor {
    private $gateway;

    public function __construct(PaymentGatewayInterface $gateway) {
        $this->gateway = $gateway;
    }

    public function initialize($config) {
        $this->gateway->initialize($config);
    }

    public function createTransaction($amount, $currency, $orderId, $callbackUrl, $returnUrl) {
        return $this->gateway->createTransaction($amount, $currency, $orderId, $callbackUrl, $returnUrl);
    }
}
?>