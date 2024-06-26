<?php
namespace App\Traits;

trait GlobalHelperClass{
    public function getFilterDate($date){
        if (is_array($date)) {
            $covered_from = date('Y-m-d', strtotime($date[0]));
            $covered_to = date('Y-m-d', strtotime($date[1]));
            $uploaded_date = count($date);
        } else if (is_string($date)) {
            $uploaded_date = explode('to', $date);
            $covered_from = isset($uploaded_date[0]) ? trim($uploaded_date[0]) : null;
            $covered_to = isset($uploaded_date[1]) ? trim($uploaded_date[1]) : null;
            $uploaded_date = count($uploaded_date);
        }
        return [$covered_from, $covered_to, $uploaded_date];
    }

    public function generateUuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
}