<?php

namespace App\Console\Commands;

ini_set('memory_limit', '512M');

use Illuminate\Console\Command;
use Faker\Factory as Faker;
use App\Models\Network;
use App\Services\UserRegistrationService;
use Illuminate\Support\Facades\Hash;

use DB;

class CreateNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-network';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an random network';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $faker = \Faker\Factory::create();


        $this->registerService = new UserRegistrationService;


        for ($i = 0; $i < 1000; $i++) {


            $data = [

                'username' => $faker->userName, 
                'password' => Hash::make('password123'),
                'email' => $faker->unique()->safeEmail,
                'sponsor' => 'ramonraniere10'

            ];

            $user = $this->registerService->registerUser($data);

            echo $user["user"]["contract_id"] . "\n";

        }


       

    }

}
