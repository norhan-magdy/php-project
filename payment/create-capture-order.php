<?php

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/paypal-client.php';

use PaypalServerSdkLib\Models\OrderRequest;
use PaypalServerSdkLib\Models\AmountWithBreakdown;
use PaypalServerSdkLib\Models\PurchaseUnitRequest;
use PaypalServerSdkLib\Models\OrderApplicationContext;

session_start();

$ordersController = $client->getOrdersController();

$amount_value = $_GET['amount'] ?? 0;
if ($amount_value <= 0) {
    die("❌ Invalid amount");
}

$order_id = $_SESSION['pending_order_id'];

// بناء الطلب
$amount = new AmountWithBreakdown("USD", $amount_value);

$purchaseUnit = new PurchaseUnitRequest($amount);
$purchaseUnit->setCustomId($order_id);

$orderRequest = new OrderRequest('CAPTURE', [$purchaseUnit]);

$experienceContext = new OrderApplicationContext();
    $experienceContext->setReturnUrl("http://localhost:8080/payment/capture-order.php");
    $experienceContext->setCancelUrl("http://localhost:8080/payment/cancel.php");

    $orderRequest->setApplicationContext($experienceContext);
// إرسال الطلب
try {
    $response = $ordersController->createOrder(['body' => $orderRequest]);
    $order = $response->getResult();

  foreach ($order->getLinks() as $link) {
    if ($link->getRel() === 'approve') {
      header("Location: " . $link->getHref());
      exit;
    }
  }

  echo "❌ Approval link not found.";
} catch (Exception $e) {
  echo "❌ Error: " . $e->getMessage();
}
