<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\ClassContracts;
use App\Library\ClassNetwork;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{

    public function getNetworkTree(Request $request) { 

        $user = $request->user();

        $searchContract = ClassContracts::getContractInfoByUsername($request->login);
        $belongs = self::controlNetworkAccess($user, $searchContract);

        if(!$searchContract || !$belongs) { 
            return response()->json([
                'status' => false,
                'Message' => "User not found."
            ]);
        }

        $network = new ClassNetwork($searchContract->contract_id);
        $networkTree = $network->getNetworkTree();
        $networkCount = $network->getNetworkCount();

        return response()->json([
            'status' => true,
            'network' => $networkTree,
            'count'   => $networkCount
        ]);
    }

    public function getNetworkNode(Request $request) { 

        $user = $request->user();

        $searchContract = ClassContracts::getContractInfoByUsername($request->login);
        $belongs = self::controlNetworkAccess($user, $searchContract);

        if(!$searchContract || !$belongs) { 
            return response()->json([
                'status' => false,
                'Message' => "User not found."
            ]);
        }

        $node = ClassNetwork::getNode($user->contract_id, $searchContract->contract_id);

        return response()->json([
            'status' => true,
            'node' => $node
        ]);

    }

    public function controlNetworkAccess($user, $searchContract) {

        if(!$searchContract) { 
            return false;
        }

        if ($user->username === $searchContract->username) {
            return true;
        }
    
        if ($searchContract) {
            return ClassNetwork::verifyIfBelongsToTheNetwork($user->contract_id, $searchContract->contract_id);
        }
        return false;
    }
    


}
