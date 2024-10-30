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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id('contract_id');
            $table->integer('contract_sponsor_id');
            $table->integer('contract_parent_id');
            $table->char('network_side', 1);
            $table->char('status', 1);
            $table->string('btc_address')->nullable();
            $table->string('usdt_address')->nullable();
            $table->string('eth_address')->nullable();
            $table->timestamps();
        });


        $genesisContract = [ 

            "contract_sponsor_id" => 0,
            "contract_parent_id"  => 0,
            'network_side' => 'R',
            'status' => 'A'
        ];

        $evaContract = [ 

            "contract_sponsor_id" => 1,
            "contract_parent_id"  => 1,
            'network_side' => 'L',
            'status' => 'I'
        ];

        DB::table('contracts')->insert($genesisContract); 
        DB::table('contracts')->insert($evaContract); 


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
