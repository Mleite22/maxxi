<?php

namespace App\Library;

use Illuminate\Support\Facades\DB;

class ClassContractDownline
{
    private $contracts = [];

    public function __construct($sponsorId, $side)
    {
        $this->contracts = $this->fetchDownline($sponsorId, $side);
    }

    private function fetchDownline($sponsorId, $side)
    {
        $results = DB::select("
            WITH RECURSIVE contract_tree AS (
                SELECT 
                    contract_id,
                    contract_parent_id,
                    network_side,
                    contract_sponsor_id
                FROM contracts 
                WHERE contract_parent_id = :sponsorId
                AND network_side = :side
            
                UNION ALL
                
                SELECT 
                    c.contract_id,
                    c.contract_parent_id,
                    c.network_side AS side,
                    c.contract_sponsor_id
                FROM contracts c
                INNER JOIN contract_tree ct ON c.contract_parent_id = ct.contract_id
                where c.network_side = :side
            )
            SELECT * FROM contract_tree
        ", [
            'sponsorId' => $sponsorId,
            'side' => $side
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
