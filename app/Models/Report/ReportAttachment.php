<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class ReportAttachment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['url'];
    
    public function report(){
        return $this->belongsTo(Report::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('report/images/'.$this->attachment);
    }
}
