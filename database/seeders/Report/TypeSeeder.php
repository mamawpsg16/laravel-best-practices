<?php

namespace Database\Seeders\Report;

use App\Models\Report\ReportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportTypes = [
            ['name' => 'Account', 'account' => 1],
            ['name' => 'Bug', 'account' => 0],
        ];

        // Insert the report types into the database
        foreach ($reportTypes as $type) {
            // Create or update the report type
            ReportType::updateOrCreate(
                ['name' => $type['name']], // Use 'name' as the unique identifier
                ['account' => $type['account']]
            );
        }
    }
}
