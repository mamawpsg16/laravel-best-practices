<?php

namespace App\Test\Solid\LSP\Services;
use App\Test\Solid\OCP\Services\Interfaces\ShapeInterface;
class Child implements ShapeInterface{
    public function __construct(public int $radius){

    }
    public function area(){
        return $this->radius  * $this->radius;
    }
}