<?php

namespace App\Models\Report;

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
}
