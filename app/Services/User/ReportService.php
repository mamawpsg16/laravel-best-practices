<?php
namespace App\Services\User;

use Exception;
use App\Mail\ReportSent;
use App\Models\Report\Report;
use App\Traits\GlobalHelperClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Report\ReportAttachment;
use App\Http\Requests\Report\StoreReportRequest;

class ReportService
{
    use GlobalHelperClass;

    public function storeReport(StoreReportRequest $request)
    {
        $data = $request->validated();
        $data['report_type_id'] = $data['report_type'];
        $data['uuid'] = $this->generateUuid();
        $data['user_id'] = auth()->user()->id;
        try {
            return DB::transaction(function () use ($data, $request) {
                $report = Report::with('type')->create($data);

                if ($request->hasFile('attachments')) {
                    $this->upload($report, $request->file('attachments'));
                }

                Mail::to($request->user())->queue(new ReportSent($report));
                return response()->json([
                    'report' => $report,
                    'message' => 'Report has been successfully delivered.',
                ], 200);
            }, 5); // 5 is the number of attempts before throwing an exception if a deadlock occurs.
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to save report and attachments',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function upload($report, $files)
    {
        foreach ($files as $file) {
            $originalFilename = $file->getClientOriginalName();
            $hashedFilename = $file->hashName();
            $file->storeAs('public/report/images', $hashedFilename);
    
            ReportAttachment::create([
                'report_id' => $report->id,
                'filename' => $originalFilename,
                'attachment' => $hashedFilename,
            ]);
        }
    }
}
