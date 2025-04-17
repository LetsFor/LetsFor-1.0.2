<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/user/transact/payments/PaymentGatewayInterface.php');

class NicePayGateway implements PaymentGatewayInterface {
    private $merchantID;
    private $merchantKey;
    private $apiURL;

    public function initialize($config) {
        $this->merchantID = $config['merchant_id'];
        $this->merchantKey = $config['merchant_key'];
        $this->apiURL = $config['api_url'];
    }

    public function createTransaction($amount, $currency, $orderId, $callbackUrl, $returnUrl) {
        $data = array(
            'merchant_id' => $this->merchantID,
            'merchant_key' => $this->merchantKey,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $orderId,
            'callback_url' => $callbackUrl,
            'return_url' => $returnUrl
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            die('Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
