<?php

namespace App\Test\Solid\SRP\Services\Export;

use App\Test\Solid\SRP\Services\Interfaces\ExportInterface;

class CsvExportService implements ExportInterface{
    public function export(){
        dd('CSV');
    }
}