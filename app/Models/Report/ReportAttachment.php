<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAttachment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function report(){
        return $this->belongsTo(Report::class);
    }
}
