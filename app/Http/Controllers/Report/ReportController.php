<?php

namespace App\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Report\Report;
use App\Models\Report\ReportType;
use App\Http\Controllers\Controller;
use App\Services\User\ReportService;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService){
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Report::select($this->selectedColumns(null))
        ->join('users as u', 'reports.user_id', '=', 'u.id')
        ->join('report_types as rt', 'reports.report_type_id', '=', 'rt.id')
        ->get();
        return response(['data' => $data]);
    }

    private function selectedColumns($search){
        $columns =  ['u.email', 'reports.*', 'rt.name as report_type'];
        if(isset($search)){
            $columns = ['name as label', 'id as value'];
        }
        return $columns;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        return $this->reportService->storeReport($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    public function getReportTypes(){
        $data = ReportType::select(['name as label', 'id as value', 'description', 'account'])->active()->get();

        return response(['options' => $data]);
    }
}
