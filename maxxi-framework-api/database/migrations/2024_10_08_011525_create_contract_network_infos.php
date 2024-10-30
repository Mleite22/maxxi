<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contract_network_infos', function (Blueprint $table) {
            $table->id('contract_network_infos_id');
            $table->integer('contract_id');
            $table->integer('insertion_method')->default(1);
            $table->integer('left_side_size')->default(0);
            $table->integer('right_side_size')->default(0);
            $table->integer('left_side_directs')->default(0);
            $table->integer('right_side_directs')->default(0);
            $table->timestamps();
        });

        $genesisContract = [ 

            "contract_id"  => 1,
        ];

        $evaContract = [ 

            "contract_id"  => 2,
        ];

        DB::table('contract_network_infos')->insert($genesisContract); 
        DB::table('contract_network_infos')->insert($evaContract); 

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_network_infos');
    }
};
