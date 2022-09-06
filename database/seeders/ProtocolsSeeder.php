<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProtocolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('protocols')->insert(
            [
                'id' => 1,
                'protocol' => 'http://',
            ]
        );
        DB::table('protocols')->insert(
            [
                'id' => 3,
                'protocol' => 'ftp://',
            ],
        );
        DB::table('protocols')->insert(
           [
             'id' => 2,
             'protocol' => 'https://',
            ],
        );
    }
}
