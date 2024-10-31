<?php

namespace App\Library;

use App\Library\ClassContracts;
use App\Models\Network;
use DB;

class ClassNetwork
{

    private $contract;

    public function __construct($contractId)
    {
        $this->contract = ClassContracts::getContractInfoByID($contractId);
        $this->parent   = ClassContracts::getContractInfoByID($this->contract->contract_parent_id);
        $this->sponsor  = ClassContracts::getContractInfoByID($this->contract->contract_sponsor_id);
    }

    public function addToNetwork() { 

        $parent = $this->defineParent();

        $contractUpdate = [ 
            'contract_parent_id' => $parent["parentId"],
            'network_side'       => $parent["side"]
        ];

        $upline = new ClassContractUpline($parent["parentId"]);
        //dd($upline, $parent);

        foreach ($upline->contracts as $key => $contract) {

            $level = ($key + 1);

            $position = $this->calculatePosition($level, $parent["side"], $parent["parentId"], $contract);

            $absolute_side = $key == 0 ? $parent["side"] : $upline->contracts[$key - 1]->network_side;

            Network::create([
                'network_owner_id'  => $contract->contract_id,
                'contract_id'       => $this->contract->contract_id,
                'parent_id'         => $parent["parentId"],
                'sponsor_id'        => $this->contract->contract_sponsor_id,
                'level'             => $level,
                'position'          => $position,
                'absolute_side'     => $absolute_side,
                'relative_side'     => $parent["side"]
            ]);

		}

        ClassContracts::updateContractByID($this->contract->contract_id, $contractUpdate);

        return true;

    }

    private function defineParent()
    {

        switch ($this->sponsor->network_info->insertion_method) {
            case 1:
                return $this->handleBalancedInsertion();
            case 2:
                return $this->handleDefaultInsertion();
            case 3:
                return $this->handleDefaultInsertion();
            default:
                return response()->json(['status' => 'false']);
        }
    }

    private function handleBalancedInsertion()
    {
        $downlineLeft = new ClassContractDownline($this->sponsor->contract_id, 'L');
        $downlineRight = new ClassContractDownline($this->sponsor->contract_id, 'R');


        if ($downlineLeft->size() == 0 && $downlineRight->size() == 0) {
            return $this->formatParentData($this->sponsor->contract_id, 'L');
        }

        if ($downlineLeft->size() > $downlineRight->size()) {
            return $this->assignParentAndSide($downlineRight, 'R');
        }

        return $this->assignParentAndSide($downlineLeft, 'L');
    }

    private function handleDefaultInsertion() 
    { 

        if($this->sponsor->network_info->insertion_method == 2) { 
            $side = 'L';
        } 

        if($this->sponsor->network_info->insertion_method == 3) { 
            $side = 'R';
        } 

        $downline = new ClassContractDownline($this->sponsor->contract_id, $side);

        if ($downline->size() == 0) {
            return $this->formatParentData($this->sponsor->contract_id, $side);
        }

        return $this->assignParentAndSide($downline, $side);

    }

    private function assignParentAndSide(ClassContractDownline $downline, $side)
    {

        $parentId = $this->sponsor->contract_id;
        if($downline->getLastContract() !== null) { 
            $parentId = $downline->getLastContract()->contract_id;
        }

        return $this->formatParentData($parentId, $side);
    }

    private function formatParentData($parentId, $side)
    {
        return [
            'parentId' => $parentId,
            'side'     => $side
        ];
    }

    public function calculatePosition($level, $side, $parentId, $contract)
    {
        if ($level == 1) {
            return $side === 'L' ? 1 : 2;
        }

        if ($level > 1) {

            $parentPosition = Network::where('network_owner_id', $contract->contract_id)
                                     ->where('contract_id', $parentId)
                                     ->first();

            if ($parentPosition) {
                return $side === 'L' ? ($parentPosition->position * 2) + 1 : ($parentPosition->position * 2) + 2;
            }
        }

        return null;
    }

    private function createEmptyTree() {

        $emptyNode = [ 
            "username"            => null,
            "parent_username"     => null,
            "sponsor_username"    => null,
            "level"               => null,
            "position"            => null,
            "status"              => null,
        ];

        $tree = [];

        $tree[0][1] = $emptyNode;
    
        for ($i = 1; $i <= 2; $i++) {
            $tree[1][$i] = $emptyNode;
        }
    
        // Nível 2 - 4 posições
        for ($i = 3; $i <= 6; $i++) {
            $tree[2][$i] = $emptyNode;
        }
    
        // Nível 3 - 8 posições
        for ($i = 7; $i <= 14; $i++) {
            $tree[3][$i] = $emptyNode;
        }
    
        // Nível 4 - 16 posições
        for ($i = 15; $i <= 30; $i++) {
            $tree[4][$i] = $emptyNode;
        }
    
        return $tree;
    }
    
    function populateTree($nodes, $root) {


        $tree = $this->createEmptyTree();
    
        $tree[0][1] = $root;
    
        foreach ($nodes as $node) {
            $level = $node->level;
            $position = $node->position;
    
            $tree[$level][$position] = $node;
        }
    
        return $tree;
    }
    

    public function getNetworkTree() { 

        $network = $this->network(5);

        $root = [ 
            "username"              => $this->contract->users[0]->username,
            "parent_username"       => $this->parent->users[0]->username ?? null,
            "sponsor_username"      => $this->sponsor->users[0]->username ?? null,
            "level"                 => 0,
            "position"              => 0,
            "status"                => $this->contract->status
        ];

        $filledTree = $this->populateTree($network, $root);

        return $filledTree;

    }

    public function getNetworkCount() {
        
        $query = "
            SELECT 
                COUNT(CASE WHEN absolute_side = 'L' THEN 1 END) AS left,
                COUNT(CASE WHEN absolute_side = 'R' THEN 1 END) AS right
            FROM network
            WHERE network_owner_id = :contractId;
            ";


        $params = ['contractId' => $this->contract->contract_id];

        $count = DB::select($query, $params);

        return $count[0];


    }

    public function network($levelLimit = null) { 

        $query = "
            select 
            parent.username as parent_username, sponsor.username as sponsor_username, u.username,
            n.level, n.position, n.absolute_side, n.relative_side,
            c.status
            from network n
            inner join contracts c on n.contract_id = c.contract_id 
            inner join users parent on n.parent_id = parent.contract_id
            inner join users sponsor on n.sponsor_id = sponsor.contract_id
            inner join users u on c.contract_id = u.contract_id 
            where network_owner_id = :contractId
        ";
    
        if ($levelLimit !== null) {
            $query .= " and level < :levelLimit ";
        }

    
        $query .= " order by level, position asc ";
    
        $params = ['contractId' => $this->contract->contract_id];
    
        if ($levelLimit !== null) {
            $params['levelLimit'] = $levelLimit;
        }
    
        $network = DB::select($query, $params);
    
        return $network;
    }


    public static function verifyIfBelongsToTheNetwork($ownerId, $contractId) {
        
        $network = Network::where('network_owner_id', $ownerId)->where('contract_id', $contractId)->exists();
        return $network;

    }

    public static function getNode($ownerId, $contractId) { 


        $query = "

            select 
            parent.username as parent_username, sponsor.username as sponsor_username, u.username,
            n.level, n.position, n.absolute_side, n.relative_side,
            c.status
            from network n
            inner join contracts c on n.contract_id = c.contract_id 
            inner join users parent on n.parent_id = parent.contract_id
            inner join users sponsor on n.sponsor_id = sponsor.contract_id
            inner join users u on c.contract_id = u.contract_id 
            where network_owner_id = :ownerId
            and n.contract_id = :contractId
           
        ";

        $params = ['ownerId' => $ownerId, 'contractId' => $contractId];

        $node = DB::select($query, $params);

        return $node[0];


    }

}
