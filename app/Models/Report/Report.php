<?php

namespace App\Models\Report;

use App\Models\Report\ReportReply;
use App\Models\Report\ReportAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function attachments(){
        return $this->hasMany(ReportAttachment::class);
    }

    public function type(){
        return $this->belongsTo(ReportType::class, 'report_type_id');
    }

    public function replies(){
        return $this->hasMany(ReportReply::class);
    }

    public function scopeResolved($q){
        $q->whereNotNull('resolved_at');
    }

    public function scopeRead($q){
        $q->whereNotNull('read_at');
    }
}
