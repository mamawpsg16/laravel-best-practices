<?php

namespace App\Test\Solid\OCP\Services;
use App\Test\Solid\OCP\Services\Interfaces\ShapeInterface;
class CircleService implements ShapeInterface{
    public function __construct(public int $radius){

    }
    public function area(){
        return $this->radius  * $this->radius;
    }
}