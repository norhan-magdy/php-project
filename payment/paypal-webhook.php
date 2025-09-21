// payment/paypal-webhook.php
<?php

$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// سجلي المعلومات في ملف أو قاعدة البيانات
file_put_contents('webhook_log.txt', print_r($data, true));

// ردي بـ 200 OK
http_response_code(200);
