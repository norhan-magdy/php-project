<?php

require_once __DIR__ . '/../vendor/autoload.php';
$client = require __DIR__ . '/paypal-client.php';

// 1. استقبل التوكن (order ID) من الرابط
if (!isset($_GET['token'])) {
  echo "❌ Error: Missing token from PayPal.";
  exit;
}

$token = $_GET['token'];

try {
  // 2. تنفيذ عملية الكابتشر
  $response = $client->getOrdersController()->captureOrder([
    'id' => $token,
    'prefer' => 'return=representation' // اختياري، لعرض تفاصيل كاملة
  ]);

  // 3. التحقق من الاستجابة
  if ($response->isSuccess()) {
    echo "✅ Payment Captured Successfully!<br>";
    $result = $response->getResult();

    $localOrderId = $result->getPurchaseUnits()[0]->getCustomId();

    // مثال لعرض ID الكابتشر
    echo "💳 Capture ID: " .
      $result->getPurchaseUnits()[0]
        ->getPayments()
        ->getCaptures()[0]
      ->getId();

    header('Location: ../customer/order_success.php?order_id=' . $localOrderId);
    exit;
  } else {
    echo "❌ Error: ";
    print_r($response->getBody());
    echo "<br>Status Code: " . $response->getStatusCode();
  }
} catch (Exception $e) {
  echo "❌ Exception: " . $e->getMessage();
}
