<?php

namespace App\Test\Solid\OCP\Services;
use App\Test\Solid\OCP\Services\Interfaces\ShapeInterface;
class RectangleService implements ShapeInterface{
    public function __construct(public int $width, public int $height){

    }
    public function area(){
        return $this->width * $this->height;
    }
}