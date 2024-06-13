<?php

namespace App\Models\Report;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportReply extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function report(){
        return $this->belongsTo(Report::class);
    }
}
