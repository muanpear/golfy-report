<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Device;

class GeneratorController extends Controller
{
    public function index()
    {
        
        return view('welcome');
    }

    public function customer_list(Request $request)
    {
        $data =Customer::select("groupID","customerName")->get();
        return response()->json($data);
    }

    public function get_circuit(Request $request)
    {
    	$data = [];

        if($request->has('customer_id')){
            $search = $request->customer_id;
            $data =Device::select("deviceID","deviceName")
            		->where('groupID',$search)
            		->get();
        }
        return response()->json($data);
    }
}