<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Test\Solid\OCP\Services\CircleService;
use App\Test\Solid\OCP\Services\AreaCalculator;
use App\Test\Solid\OCP\Services\RectangleService;
use App\Test\Solid\SRP\Services\Export\CsvExportService;
use App\Test\Solid\SRP\Services\Export\PdfExportService;
use App\Test\Solid\OCP\Services\Interfaces\PaymentGatewayInterface;

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

        // $fields = "name, email, banned_at";
        // $where = "WHERE 1=1";
        // $name = 'Kevin';
        // // Variable for name filter
        // $name = 'Kevin';

        // // Constructing the query
        // $where = " WHERE 1=1";
        // // $where .= " AND name LIKE '%".$name."%'";
        // $where .= " AND id  = 66";
        // // Final SQL query
        // $sql = "SELECT $fields FROM users" . $where. " LIMIT 1";
        // // dd($sql);
        // $abc = DB::SELECT($sql);
        // dd($abc);

        $emails = "kevinmensah110@gmail.com,qweqwe@gmail.com";
        $exploded_emails = explode(',', str_replace(' ', '', $emails));
        $additionalCondition ="";
        
        foreach ($exploded_emails as $key => $email) {
            $additionalCondition .= $key == 0 ? " WHERE FIND_IN_SET('$email',email) " : " OR FIND_IN_SET('$email',email)";
        }
        // $params = implode(',',array_map(function($ref) {
        //     return "'".$ref."'";
        // }, $exploded_emails));
        // $condition = " WHERE email IN ($params)";
        $query = "SELECT * FROM users".$additionalCondition;
        $result =   DB::SELECT($query);
        dd($result);
        /** OCP */
    //    $shapes = [new CircleService(10), new RectangleService(10,10)];
    //    $area  = $this->areaCalculator->totalArea(new CircleService(10), new RectangleService(10,10));
    //    dd($area);

    // Bind the appropriate payment gateway implementation based on the selected method

    // Now, Laravel will inject the appropriate implementation
        // $this->paymentGateway->makePayment();
    }

}
