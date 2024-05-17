<?php

namespace App\Test\Solid\SRP\Services\Export;

use App\Test\Solid\SRP\Interfaces\ViewInterface;
use App\Test\Solid\SRP\Services\Interfaces\ExportInterface;

class PdfExportService implements ExportInterface, ViewInterface{
    public function export(){
        dd('PDF');
    }

    public function preview(){
        dd('PREVIEW PDF');
    }
}