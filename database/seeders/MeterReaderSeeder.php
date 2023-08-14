<?php

namespace Database\Seeders;

use App\Models\MeterReader;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MeterReaderSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Use the create() method to insert records
        MeterReader::create([
            'reader_id' => '001',
            'password' => Hash::make('bentram37'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        MeterReader::create([
            'reader_id' => '002',
            'password' => Hash::make('blackroad19'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        MeterReader::create([
            'reader_id' => '003',
            'password' => Hash::make('murkypaste20'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
