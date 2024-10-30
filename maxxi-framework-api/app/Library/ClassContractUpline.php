<?php

namespace App\Library;

use Illuminate\Support\Facades\DB;

class ClassContractUpline
{
    public $contracts = [];

    public function __construct($contractId)
    {
        $this->contracts = $this->fetchUpline($contractId);
    }

    private function fetchUpline($contractId)
    {
        // $results = DB::select("
        //     WITH RECURSIVE contract_tree AS (
        //         SELECT 
        //             contract_id,
        //             contract_parent_id,
        //             network_side,
        //             contract_sponsor_id
        //         FROM contracts 
        //         WHERE contract_id = :contractId  
                
        //         UNION ALL
                
        //         SELECT 
        //             c.contract_id,
        //             c.contract_parent_id,
        //             c.network_side AS side,
        //             c.contract_sponsor_id
        //         FROM contracts c
        //         INNER JOIN contract_tree ct ON c.contract_id = ct.contract_parent_id
        //     )
        //     SELECT * FROM contract_tree
        // ", [
        //     'contractId' => $contractId,
        // ]);

        $results = DB::select("

            WITH RECURSIVE contract_tree AS (
                SELECT 
                    contract_id,
                    contract_parent_id,
                    network_side,
                    contract_sponsor_id
                FROM contracts 
                WHERE contract_id = :contractId  
                
                UNION ALL
                
                SELECT 
                    c.contract_id,
                    c.contract_parent_id,
                    c.network_side AS side,
                    c.contract_sponsor_id
                FROM contracts c
                INNER JOIN contract_tree ct ON c.contract_id = ct.contract_parent_id
            )
            SELECT * FROM contract_tree
            
        ", [
            'contractId' => $contractId,
        ]);

        return  $results;
        
    }

    public function getContracts()
    {
        return $this->contracts;
    }

    public function getLastContract()
    {
        if (empty($this->contracts)) {
            return null;
        }

        return end($this->contracts);
    }

    public function size()
    {
        return count($this->contracts);
    }
}
