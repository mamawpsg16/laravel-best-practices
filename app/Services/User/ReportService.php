<?php
namespace App\Services\User;

use App\Models\Report\Report;
use App\Models\Report\ReportAttachment;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Report\StoreReportRequest;
use Exception;
class ReportService
{
    public function storeReport(StoreReportRequest $request)
    {
        $data = $request->validated();
        $data['report_type_id'] = $data['report_type'];
        $data['user_id'] = auth()->user()->id;
        try {
            return DB::transaction(function () use ($data, $request) {
                $report = Report::create($data);

                if ($request->hasFile('attachments')) {
                    $this->upload($report, $request->file('attachments'));
                }
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
            $path = $file->store('public/images');
            ReportAttachment::create([
                'report_id' => $report->id,
                'attachment' => $path,
            ]);
        }
    }
}
