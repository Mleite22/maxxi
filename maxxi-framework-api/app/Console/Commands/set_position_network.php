<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Network;
use App\Library\ClassNetwork;


class set_position_network extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set_position_network';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $bath_size = 1000;
        $offset = 0;
        $totalRecords = 2500000; 


        while($offset < $totalRecords) { 

            echo $offset .'to'. $offset+$bath_size; 

            $network = Network::where('level', '!=', 1)->where('level', '<', 7)->limit($bath_size)->orderBy('network_id', 'asc')->get();

            foreach($network as $contract) { 


                if ($contract->level != 1 && $contract->level < 7) {

        
                    $father = Network::where('network_owner_id', $contract->network_owner_id)->where('contract_id', $contract->parent_id)->first();
        
                    if ($father) { 

                        if($contract->relative_side == 'L') { 
                            $position = $father->position * 2 + 1;
                        }

                        if($contract->relative_side == 'R') { 
                            $position = $father->position * 2 + 2;
                        }
                    }

                }

                Network::where('network_id', $contract->network_id)->update(['position' => $position]); 
        

            }


            $offset += $batch_size;
        }

    }
}
