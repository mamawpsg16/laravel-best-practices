<?php

namespace App\Http\Controllers\Report;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Report\Report;
use App\Models\Report\ReportType;
use App\Traits\GlobalHelperClass;
use App\Models\Report\ReportReply;
use App\Http\Controllers\Controller;
use App\Services\User\ReportService;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;

class ReportController extends Controller
{
    use GlobalHelperClass;
    public function __construct(protected ReportService $reportService){
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reportType = $request->query('reportType');
        $viewStatus = $request->query('viewStatus');
        $resolveStatus = $request->query('resolveStatus');
        $creationDate = $request->query('date');
        $isAdmin = auth()->user()->isAdmin();
        $data = Report::select($this->selectedColumns(null))
        // ->join('users as u', 'reports.user_id', '=', 'u.id')
        ->join('report_types as rt', 'reports.report_type_id', '=', 'rt.id')
        ->when($creationDate, function ($q) use ($creationDate) {
            [$covered_from, $covered_to, $creation_date] = $this->getFilterDate($creationDate);
            if (!empty($covered_from) && !empty($covered_to)) {
                // If both dates are provided, create a date range
                $q->whereBetween('reports.created_at', [
                    Carbon::parse($covered_from)->startOfDay(),
                   Carbon::parse($covered_to)->endOfDay(),
                ]);
            } elseif (!empty($covered_from)) {
                // If only one date is provided, create a date range for that day
                $q->whereDate('reports.created_at', '=', Carbon::parse($covered_from)->toDateString());
            }
        })->when(!is_null($reportType), function($q) use($reportType){
            $q->where('rt.id', $reportType);
        })->when(!is_null($viewStatus), function($q) use($viewStatus){
            if($viewStatus == 0){
                $q->whereNull('reports.read_at');
            }
            if($viewStatus == 1){
                $q->whereNotNull('reports.read_at');
            }
        })->when(!is_null($resolveStatus), function($q) use($resolveStatus){
            $q->where('reports.resolved_status', $resolveStatus);
        })->when(!$isAdmin, function($q){
            $q->IsOwner(auth()->user()->id);
        })
        ->oldest('reports.created_at')
        ->get();
        return response(['data' => $data]);
    }

    private function selectedColumns($search){
        // $columns =  ['u.email', 'reports.*', 'rt.name as report_type'];
        $columns =  ['reports.*', 'rt.name as report_type'];
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
    public function show($id)
    {   
        $report = is_numeric($id) ? Report::findOrFail($id): Report::where('uuid', $id)->firstOrFail(); 
        if (!Gate::allows('show-report', $report)) {
            return response(['status'=>'forbidden'],403);
        }
        return response(['data' => $report->load(['type','attachments'])]);
    }

    public function getReportTypes(){
        $data = ReportType::select(['name as label', 'id as value', 'description', 'account'])->active()->get();

        return response(['options' => $data]);
    }

    public function markAsRead($id)
    {   
        $report = is_numeric($id) ? Report::findOrFail($id): Report::where('uuid', $id)->firstOrFail(); 
        $report->update(['read_at' => now(), 'read_by' => auth()->user()->email]);
        return response(['success' => true]);
    }

    public function reply (Request $request){
        $report = Report::findOrFail($request->id);
        $report->replies()->create(['description' => $request->message, 'user_id' => auth()->user()->id]);
        return response(['message' => 'Message sent successfully.']);
    }

    public function getReplies(Request $request){
        $reportId = $request->query('reportId');
        $replies = ReportReply::select('description', 'report_id', 'u.name', 'u.email', 'report_replies.created_at')
        ->join('users as u', 'report_replies.user_id', 'u.id')
        ->where('report_id', $reportId)->get();
        return response(['replies' => $replies]);
    }


    public function getReportMonitoringDetails(Request $request){
        $pendingReports = Report::whereNull('read_at')->count();
        $reviewedReports = Report::whereNotNull('read_at')->whereNull('resolved_at')->count();
        $resolvedReports = Report::whereNotNull('resolved_at')->count();
        
        return response()->json([
            'pendingReports' => $pendingReports,
            'reviewedReports' => $reviewedReports,
            'resolvedReports' => $resolvedReports,
        ]);
    }

    public function resolvedReport(Report $report){
        $report->update(['resolved_at' => now(), 'resolved_by' => auth()->user()->email]);

        return response(['data' =>  $report->load(['type','attachments'])]);
    }
}
