// payment/PayPalClient.php
<?php

// require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../conf/bootstrap.php';


use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Logging\LoggingConfigurationBuilder;
use PaypalServerSdkLib\Logging\RequestLoggingConfigurationBuilder;
use PaypalServerSdkLib\Logging\ResponseLoggingConfigurationBuilder;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use Psr\Log\LogLevel;

$client = PaypalServerSdkClientBuilder::init()
    ->clientCredentialsAuthCredentials(
        ClientCredentialsAuthCredentialsBuilder::init(
            $_ENV['PAYPAL_CLIENT_ID'],
            $_ENV['PAYPAL_CLIENT_SECRET']
        )
    )
    ->environment(Environment::SANDBOX)
    ->loggingConfiguration(
        LoggingConfigurationBuilder::init()
            ->level(LogLevel::INFO)
            ->requestConfiguration(RequestLoggingConfigurationBuilder::init()->body(true))
            ->responseConfiguration(ResponseLoggingConfigurationBuilder::init()->headers(true))
    )
    ->build();

return $client;