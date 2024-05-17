<?php
namespace App\Test\Solid\OCP\Services\Interfaces;

interface PaymentGatewayInterface{
    public function makePayment();
}