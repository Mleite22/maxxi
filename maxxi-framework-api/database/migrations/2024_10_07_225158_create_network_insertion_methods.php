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
        Schema::create('network_insertion_methods', function (Blueprint $table) {
            $table->id('network_insertion_method_id');
            $table->string('name', 100);
            $table->text('description');
            $table->timestamps();
        });

        $data = [
            [
                'name' => 'Balance Insertion',
                'description' => 'Insert the new package on the weaker side of your network until both sides are balanced. Once the sides are equal, alternate the placement of new packages between the left and right sides to maintain balance.',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Left Side Insertion',
                'description' => 'The new package will be inserted on the left end of the Sponsor.',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Right Side Insertion',
                'description' => 'The new package will be inserted on the right end of the Sponsor.',
                'created_at' => now(),
                'updated_at' => now(),

            ]
        ];

        DB::table('network_insertion_methods')->insert($data); 

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_insertion_methods');
    }
};
