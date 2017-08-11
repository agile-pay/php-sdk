#Usage

```php
$agilePay = new \AgilePay\Sdk\AgilePay([
    'api_key' => '',
    'api_secret' => ''
]);
```

##Gateways

####To create a new gateway :

```php
$gateway = $agilePay->gateway()->create('stripe', [
    'secret_key' => ''
]);
```

The response will contain a gateway **reference** which is used to perform transactions against the gateway

```php
$gateway->reference;
```

##Payment methods

####To create a new payment method type of gateway token:

In this case the payment method will be retained with the provided gateway,
please check the availability of transaction store in the gateways

Gateways list -> http://docs.agilepay.io/#!/gateway 

Gateway token -> http://docs.agilepay.io/#!/payment-method-create-gateway-token


```php
$paymentMethod = $agilePay->paymentMethod()->createGatewayToken($gateway->reference, [
    'number' => '',
    'holder_name' => '',
    'cvv' => '',
    'expiry_month' => '', //mm
    'expiry_year' => '',  //yy
]);
```

The response will contain a payment method **token** which is used to perform transactions against the payment method

```php
$paymentMethod->token;
```

##Transactions

####Auth (Charge a credit card with a payment method type of gateway token):
```php
$transaction = $agilePay->transaction()
                ->setPaymentMethod($paymentMethod->token;)
                ->auth(500, 'eur'); //Charging 5.00 euros
```

The response will contain a **reference** which can be used for second steps transactions such as **void**, **capture** and **refund**

```php
$transaction->reference;
```

####Void (Cancel an authorized transaction):
```php
$response = $agilePay->transaction($transaction->reference)->void();
```

####Capture (Settle an authorized transaction):
```php
$response = $agilePay->transaction($transaction->reference)->capture();
```

####Credit (Refund a settled transaction):
```php
$response = $agilePay->transaction($transaction->reference)->credit();
```

