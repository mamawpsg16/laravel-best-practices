<?php
namespace App\Test\Solid\OCP\Services;
use App\Test\Solid\OCP\Services\Interfaces\PaymentGatewayInterface;

class PaypalPaymentMethod implements PaymentGatewayInterface{
    public function makePayment(){
        echo 'paypal';
    }
}