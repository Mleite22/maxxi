<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->integer('contract_id');
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('two_factor_secret')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        $genesisUser = [ 

            "username" => 'genesis',
            "contract_id" => 1,
            "email"    => 'ramonranieremkt@gmail.com',
            "first_name" => "Genesis",
            "last_name" => "Smith",
            'password' => '$2y$12$udnvWbfunz.qV6GBJP3Dzu/DnUzg9YW9g00CMyEyDTG1xxJtM4rzK',

        ];

        $evaUser = [ 

            "username" => 'eva',
            "contract_id" => 2,
            "email"    => 'ramonranieremkt@gmail.com',
            "first_name" => "Eva",
            "last_name" => "Smith",
            'password' => '$2y$12$udnvWbfunz.qV6GBJP3Dzu/DnUzg9YW9g00CMyEyDTG1xxJtM4rzK',

        ];

        DB::table('users')->insert($genesisUser); 
        DB::table('users')->insert($evaUser); 


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
