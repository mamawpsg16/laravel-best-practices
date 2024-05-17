<?php
namespace App\Test\Solid\OCP\Services;
use App\Test\Solid\OCP\Services\Interfaces\PaymentGatewayInterface;

class StripePaymentMethod implements PaymentGatewayInterface{
    public function makePayment(){
        echo 'stripe';
    }
}