<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test\Solid\OCP\Services\CircleService;
use App\Test\Solid\OCP\Services\AreaCalculator;
use App\Test\Solid\OCP\Services\Interfaces\PaymentGatewayInterface;
use App\Test\Solid\OCP\Services\RectangleService;
use App\Test\Solid\SRP\Services\Export\CsvExportService;
use App\Test\Solid\SRP\Services\Export\PdfExportService;

class SolidController extends Controller
{
    /** SRP */

    // public function __construct(protected CsvExportService $exportService){
    // public function __construct(protected PdfExportService $exportService){
      
    // }

    /** OCP */
    // public function __construct(public AreaCalculator $areaCalculator){
    public function __construct(public PaymentGatewayInterface $paymentGateway){
        
    }
    public function index(Request $request)
    {
        /** OCP */
    //    $shapes = [new CircleService(10), new RectangleService(10,10)];
    //    $area  = $this->areaCalculator->totalArea(new CircleService(10), new RectangleService(10,10));
    //    dd($area);

    // Bind the appropriate payment gateway implementation based on the selected method

    // Now, Laravel will inject the appropriate implementation
        // $this->paymentGateway->makePayment();
    }

}
