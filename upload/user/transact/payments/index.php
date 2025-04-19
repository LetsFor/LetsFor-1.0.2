<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/root-dir/root-dir-head.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/user/transact/payments/NicePayGateway.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/user/transact/payments/PaymentProcessor.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentSystem = $_POST['payment_system'];
    $amount = $_POST['amount'];
    $userId = $user['id']; // Пример идентификатора пользователя
    $orderId = 'ORDER' . uniqid();

    $callbackUrl = homeLink() . '/callback';
    $returnUrl = homeLink() . '/return';

    if ($paymentSystem === 'nicepay') {
        $nicePayConfig = array(
            'merchant_id' => '66dd9b1378fa07dcb91ad232',
            'merchant_key' => 'MdVOM-hVTFp-2DGQl-18F12-mQ0Ef',
            'api_url' => 'https://api.nicepay.co.id/'
        );
        $nicePayGateway = new NicePayGateway();
        $paymentProcessor = new PaymentProcessor($nicePayGateway);
        $paymentProcessor->initialize($nicePayConfig);
        $response = $paymentProcessor->createTransaction($amount, 'IDR', $orderId, $callbackUrl, $returnUrl);
    }

    if (isset($response['status']) && $response['status'] === 'success') {
        // Обновление баланса пользователя
        $stmt = $adp->prepare("UPDATE users SET balance = balance + ? WHERE id = '".$user['id']."'");
        $stmt->execute([$amount, $userId]);

        echo 'Пополнение успешно. URL для оплаты: ' . $response['payment_url'];
    } else {
        echo 'Ошибка: ' . $response['message'];
    }
}


echo '<form action="index.php" method="post">
<label for="payment_system">Выберите платёжную систему:</label>
<select id="payment_system" name="payment_system">
<option value="nicepay">NicePay</option>
</select>

<br>
<label for="amount">Сумма:</label>
<input type="number" id="amount" name="amount" required>
<br>
<button type="submit">Отправить</button>
</form>';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/root-dir/root-dir-footer.php');
?>