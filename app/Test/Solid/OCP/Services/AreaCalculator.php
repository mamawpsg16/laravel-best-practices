<?php
namespace App\Test\Solid\OCP\Services;

use App\Test\Solid\OCP\Services\Interfaces\ShapeInterface;
use Exception;

class AreaCalculator{
    public function totalArea(ShapeInterface ...$shapes){
        $area = 0;
        foreach($shapes as $shape){
                $area += $shape->area();
        }
        return $area;
    }
}