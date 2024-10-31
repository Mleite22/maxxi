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
        Schema::create('network', function (Blueprint $table) {
            $table->id('network_id');
            $table->integer('network_owner_id');
            $table->integer('contract_id');
            $table->integer('parent_id');
            $table->integer('sponsor_id');
            $table->integer('level');
            $table->integer('position')->nullable();
            $table->char('absolute_side', 1);
            $table->char('relative_side', 1);
            $table->timestamps();
        });

        $genesisNetwork = [ 

            "network_owner_id"   => 1,
            "contract_id"        => 2,
            "parent_id"          => 1,
            "sponsor_id"         => 1,
            "level"              => 1,
            "position"           => 1,
            "absolute_side"      => 'L',
            "relative_side"      => 'L',
            "created_at"         => now(),
            "updated_at"         => now() 


        ];

        DB::table('network')->insert($genesisNetwork); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network');
    }
};
