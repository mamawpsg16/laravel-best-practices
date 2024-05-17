<?php
namespace App\Test\Solid\OCP\Services;

class PaymentService{
    public function pay($paymentGateway){
        if($paymentGateway == 'stripe'){
            $this->stripe();
        }

        if($paymentGateway == 'paypal'){
            $this->paypal();
        }
        return 'via paypal';
    }

    public function stripe(){
        return 'stripe';
    }
    public function paypal(){
        return 'paypal';
    }
}