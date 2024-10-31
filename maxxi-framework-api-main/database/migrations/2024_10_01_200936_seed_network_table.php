<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class SeedNetworkTable extends Migration
{
    /**
     * Execute the migrations.
     *
     * @return void
     */
    public function up()
    {
    //     $data = [
    //         [
    //             'contract_id' => 1,
    //             'parent_id' => 0,
    //             'sponsor_id' => 0,
    //             'level' => 0,
    //             'side' => 'G',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 2,
    //             'parent_id' => 1,
    //             'sponsor_id' => 1,
    //             'level' => 1,
    //             'side' => 'L',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 3,
    //             'parent_id' => 1,
    //             'sponsor_id' => 1,
    //             'level' => 1,
    //             'side' => 'R',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 4,
    //             'parent_id' => 2,
    //             'sponsor_id' => 2,
    //             'level' => 2,
    //             'side' => 'L',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 5,
    //             'parent_id' => 2,
    //             'sponsor_id' => 2,
    //             'level' => 2,
    //             'side' => 'R',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 6,
    //             'parent_id' => 3,
    //             'sponsor_id' => 3,
    //             'level' => 2,
    //             'side' => 'L',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 7,
    //             'parent_id' => 3,
    //             'sponsor_id' => 3,
    //             'level' => 2,
    //             'side' => 'R',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 8,
    //             'parent_id' => 4,
    //             'sponsor_id' => 4,
    //             'level' => 3,
    //             'side' => 'R',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 10,
    //             'parent_id' => 8,
    //             'sponsor_id' => 8,
    //             'level' => 4,
    //             'side' => 'L',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'contract_id' => 9,
    //             'parent_id' => 8,
    //             'sponsor_id' => 8,
    //             'level' => 4,
    //             'side' => 'R',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ];

    //     DB::table('network')->insert($data); 

       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('network')->truncate();
    }
}
