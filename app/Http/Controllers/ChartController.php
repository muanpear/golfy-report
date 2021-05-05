<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Traffic;
use App\Models\Device;
use Redirect;
use AUTH;

class ChartController extends Controller
{
    function get_percentile($percentile, $array) {
        sort($array);
        $index = ($percentile/100) * count($array);
        if (floor($index) == $index) {
             $result = ($array[$index-1] + $array[$index])/2;
        }
        else {
            $result = $array[floor($index)];
        }
        return number_format((float)$result, 2, '.', '');
    }

    public function unit($val_max){
       if ($val_max < 1000000){
            $unit = "Kbps";
       }elseif ($val_max > 1000000 and $val_max < 1000000000){
            $unit = "Mbps";
       }elseif ($val_max > 1000000000){
            $unit = "Gbps";
       }else{
            $unit = "bps";
       }

        return $unit;
    }

    public function data_graph($val_max, $val){
        if ($val_max < 1000000){
            $cal = $val / 1000;
        }elseif ($val_max > 1000000 and $val_max < 1000000000){
            $cal = $val / 1000 / 1000;
        }elseif ($val_max > 1000000000){
            $cal = $val / 1000 / 1000 / 1000;
        }else{
            $cal = $val;
        }

        return $cal;

    }

    public function cal($val){
        $cal = $val / 1000 / 1000;
        $val = number_format((float)$cal, 2, '.', ''); 
        return $val;
    }

    public function cal_reverse($val){
        $cal = $val * 1000 * 1000;
        // $val = number_format((float)$cal, 2, '.', ''); 
        return $cal;
    }
    
    public function index(Request $request)
    {
        $circuit = $request->circuit;
        $daterange = explode(" ",$request->daterange);
        $from = Carbon::parse($daterange[0])->format('Y-m-d');
        $to = Carbon::parse($daterange[2])->format('Y-m-d');
        // dd($createdAt);
        $traffics = [];
        $period = CarbonPeriod::create($from, $to);
        // dd($period);
        $dates = [];
        
        $availability = [];
        $rxMin = [];
        $rxAvg = [];
        $rxMax = [];
        $rxPercentile = 0;

        $txMin = [];
        $txAvg = [];
        $txMax = [];
        $txPercentile = 0;

        $device = Device::where('deviceID', $circuit)->first();

        foreach ($period as $key => $date) {
            $traffics[$key]['date'] = $date->format('d/m/Y');
            array_push($dates, $date->format('Y-m-d'));

            $traffic_data = Traffic::where('deviceID', $circuit)->where('pollTimeUtc','like', '%' . $date->format('Y-m-d') . '%')->orderBy('pollTimeUtc','DESC')->first();
            if (!empty($traffic_data)){
                $traffics[$key]['up'] = $traffic_data->deviceUp;
                $traffics[$key]['down'] = $traffic_data->deviceDown;
                $traffics[$key]['availbility'] = $traffic_data->deviceAva;
            $traffics[$key]['rxMin'] = $traffic_data->rxSpeedMin;
            $traffics[$key]['rxMax'] = $traffic_data->rxSpeedMax;
            $traffics[$key]['rxAvg'] = $traffic_data->rxSpeedAvg;
            $traffics[$key]['txMin'] = $traffic_data->txSpeedMin;
            $traffics[$key]['txMax'] = $traffic_data->txSpeedMax;
            $traffics[$key]['txAvg'] = $traffic_data->txSpeedAvg;
            
            array_push($availability, $traffic_data->deviceAva);

            array_push($rxMin, $traffic_data->rxSpeedMin);
            array_push($rxAvg, $traffic_data->rxSpeedAvg);
            array_push($rxMax, $traffic_data->rxSpeedMax);
            
            array_push($txMin, $traffic_data->txSpeedMin);
            array_push($txAvg, $traffic_data->txSpeedAvg);
            array_push($txMax, $traffic_data->txSpeedMax);

            }elseif(empty($traffic_data)){
                $traffics[$key]['up'] = null;
                $traffics[$key]['down'] = null;
                $traffics[$key]['availbility'] = null;
                $traffics[$key]['rxMin'] = null;
            $traffics[$key]['rxMax'] = null;
            $traffics[$key]['rxAvg'] = null;
            $traffics[$key]['txMin'] = null;
            $traffics[$key]['txMax'] = null;
            $traffics[$key]['txAvg'] = null;
            array_push($availability, 0);
            array_push($rxMin, 0);
            array_push($rxAvg, 0);
            array_push($rxMax, 0);
            
            array_push($txMin, 0);
            array_push($txAvg, 0);
            array_push($txMax, 0);
            }
        } 

        ///////////// RX /////////////////
        $rx_Min = min($rxMin);
        $rx_Max = max($rxMax);
        $rx_unit = $this->unit($rx_Max);
        $rxMin_cal = [];
        $rxMax_cal = [];
        $rxAvg_cal = [];

        $sum_rx_Avg = array_sum($rxAvg);
        $rx_Avg_cal =  $sum_rx_Avg/($key+1);

        foreach ($rxMin as $key => $val0) {
            array_push($rxMin_cal, $this->data_graph($rx_Max, $val0));
        }

        foreach ($rxMax as $key => $val1) {
            array_push($rxMax_cal, $this->data_graph($rx_Max, $val1));
        }

        foreach ($rxAvg as $key => $val2) {
            array_push($rxAvg_cal, $this->data_graph($rx_Max, $val2));
        }

        ///////////// TX //////////////////
        $tx_Min = min($txMin);
        $tx_Max = max($txMax);
        $tx_unit = $this->unit($tx_Max);
        $txMin_cal = [];
        $txMax_cal = [];
        $txAvg_cal = [];
        $sum_tx_Avg = array_sum($txAvg);
        $tx_Avg_cal =  $sum_tx_Avg/($key+1);

        foreach ($txMin as $key => $val0) {
            array_push($txMin_cal, $this->data_graph($tx_Max, $val0));
        }

        foreach ($txMax as $key => $val3) {
            array_push($txMax_cal, $this->data_graph($tx_Max, $val3));
        }

        foreach ($txAvg as $key => $val4) {
            array_push($txAvg_cal, $this->data_graph($tx_Max, $val4));
        }
        ////////// percentile /////////////////////
        if ($request->percent_val){
            $rxPercentile = $this->get_percentile($request->percent_val, $rxMax_cal);
            $txPercentile = $this->get_percentile($request->percent_val, $txMax_cal);
        }

        ////////// availability ///////////////////
        $sum_availability = array_sum($availability);
        $availability_cal =  $sum_availability/($key+1);
        
        /////////// data in chart /////////////////
        $rxMin_chart = min($rxMin_cal);
        $rxMax_chart = max($rxMax_cal);
        $rxAvg_chart = array_sum($rxAvg_cal)/($key+1);

        $txMin_chart = min($txMin_cal);
        $txMax_chart = max($txMax_cal);
        $txAvg_chart = array_sum($txAvg_cal)/($key+1);
        
        $traffics_data = json_encode($traffics);

    	return view('chart')->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))
        ->with('rxMin_cal',json_encode($rxMin_cal,JSON_NUMERIC_CHECK))
        ->with('rxAvg_cal',json_encode($rxAvg_cal,JSON_NUMERIC_CHECK))
        ->with('rxMax_cal',json_encode($rxMax_cal,JSON_NUMERIC_CHECK))
        ->with('txMin_cal',json_encode($txMin_cal,JSON_NUMERIC_CHECK))
        ->with('txAvg_cal',json_encode($txAvg_cal,JSON_NUMERIC_CHECK))
        ->with('txMax_cal',json_encode($txMax_cal,JSON_NUMERIC_CHECK))
        ->with('availability_cal',$availability_cal)
        ->with('rx_min',$rx_Min)
        ->with('rx_Avg_cal',$rx_Avg_cal)
        ->with('rx_max',$rx_Max)
        ->with('tx_min',$tx_Min)
        ->with('tx_Avg_cal',$tx_Avg_cal)
        ->with('tx_max',$tx_Max)
        ->with('rx_unit',$rx_unit)
        ->with('tx_unit',$tx_unit)
        ->with('rxMin_chart',$rxMin_chart)
        ->with('rxMax_chart',$rxMax_chart)
        ->with('rxAvg_chart',$rxAvg_chart)
        ->with('rxPercentile_chart',$rxPercentile)
        ->with('txMin_chart',$txMin_chart)
        ->with('txMax_chart',$txMax_chart)
        ->with('txAvg_chart',$txAvg_chart)
        ->with('txPercentile_chart',$txPercentile)
        ->with('percentile_chart',$request->percent_val)
        ->with('daterange',$request->daterange)
        ->with('device',$device)
        ->with('traffics',json_decode($traffics_data, true));
    }


     public function createPDF() {
      $traffics = [];
        $period = CarbonPeriod::create('2021-01-01', '2021-01-31');
        $dates = [];
        // Iterate over the period
        foreach ($period as $key => $date) {
            $traffics[$key]['date'] = $date->format('d/m/Y');
            array_push($dates, [$date->format('d/m/Y')]);
        }  

        
        $rx = Traffic::take(31)->get();
    
        $rxAvg = [];
        $rxMax = [];

        $txAvg = [];
        $txMax = [];
        foreach ($rx as $key => $val) {
            $traffics[$key]['rxMin'] = $this->cal($val['rxSpeedMin']);
            $traffics[$key]['rxMax'] = $this->cal($val['rxSpeedMax']);
            $traffics[$key]['rxAvg'] = $this->cal($val['rxSpeedAvg']);
            $traffics[$key]['txMin'] = $this->cal($val['txSpeedMin']);
            $traffics[$key]['txMax'] = $this->cal($val['txSpeedMax']);
            $traffics[$key]['txAvg'] = $this->cal($val['txSpeedAvg']);

            array_push($rxAvg, $this->cal($val['rxSpeedAvg']));
            array_push($rxMax, $this->cal($val['rxSpeedMax']));

            array_push($txAvg, $this->cal($val['txSpeedAvg']));
            array_push($txMax, $this->cal($val['txSpeedMax']));
        }

    // $tx = Traffic::select('txSpeedAvg')->take(31)->get();
    // $txs = [];
    // foreach ($tx as $key => $val1) {
    //     $traffics[$key]['tx'] = $this->cal($val1['txSpeedAvg']);
    //     array_push($txs, $this->cal($val1['txSpeedAvg']));
    // }
    
// dd($traffics);
    // dd($rxs);

       $traffics = json_encode($traffics);

// view()->share(['traffics'=>json_decode($traffics, true), 'dates' => json_encode($dates,JSON_NUMERIC_CHECK), 'rxs' => json_encode($rxs,JSON_NUMERIC_CHECK), 'txs' => json_encode($txs,JSON_NUMERIC_CHECK)]);

// $pdf = \PDF::loadView('chart');
// $pdf->setOption('enable-javascript', true);
//         $pdf->setOption('javascript-delay', 15000);
//         // $pdf->setOption('enable-smart-shrinking', true);
//         // $pdf->setOption('no-stop-slow-scripts', true);
// // ->setOrientation('landscape')
// // ->setOptions(['enable-javascript', true])
// // ->setOptions(['javascript-delay', 5000])
// // ->setOptions(['images', true])
// // ->setOptions(['enable-smart-shrinking', true])
// // ->setOptions(['no-stop-slow-scripts', true]);
// return $pdf->download('pdfview.pdf');

return $pdf = PDF::loadView('chart',['traffics'=>json_decode($traffics, true), 'dates' => json_encode($dates,JSON_NUMERIC_CHECK), 'rxs' => json_encode($rxs,JSON_NUMERIC_CHECK), 'txs' => json_encode($txs,JSON_NUMERIC_CHECK)]);
// ->setPaper('a4')
// ->setOrientation('landscape')
// ->setOption('enable-javascript', true)
// ->setOption('javascript-delay', 13000)
// ->setOption('images', true)
// ->setOption('enable-smart-shrinking', true)
// ->setOption('no-stop-slow-scripts', true)
// ->save(storage_path()."/xxx.pdf")
// ->stream('test.pdf');


    }

    public function edit(Request $request)
    {
        $circuit = $request->circuit_edit;
        $daterange = explode(" ",$request->daterange_edit);
        $from = Carbon::parse($daterange[0])->format('Y-m-d');
        $to = Carbon::parse($daterange[2])->format('Y-m-d');
        // dd($createdAt);
        $traffics = [];
        $period = CarbonPeriod::create($from, $to);
        // dd($period);
        $dates = [];
        
        $rxAvg = [];
        $rxMax = [];

        $txAvg = [];
        $txMax = [];

        $device = Device::where('deviceID', $circuit)->first();

        foreach ($period as $key => $date) {
            $traffics[$key]['date'] = $date->format('d/m/Y');
            array_push($dates, $date->format('Y-m-d'));

            $traffic_data = Traffic::where('deviceID', $circuit)->where('pollTimeUtc','like', '%' . $date->format('Y-m-d') . '%')->orderBy('pollTimeUtc','DESC')->first();
            if (!empty($traffic_data)){
                $traffics[$key]['id'] = $traffic_data->id;
                $traffics[$key]['deviceID'] = $traffic_data->deviceID;
                $traffics[$key]['deviceName'] = $traffic_data->deviceName;
                $traffics[$key]['up'] = $traffic_data->deviceUp;
                $traffics[$key]['down'] = $traffic_data->deviceDown;
                $traffics[$key]['availbility'] = $traffic_data->deviceAva;
            $traffics[$key]['rxMin'] = $traffic_data->rxSpeedMin;
            $traffics[$key]['rxMax'] = $traffic_data->rxSpeedMax;
            $traffics[$key]['rxAvg'] = $traffic_data->rxSpeedAvg;
            $traffics[$key]['txMin'] = $traffic_data->txSpeedMin;
            $traffics[$key]['txMax'] = $traffic_data->txSpeedMax;
            $traffics[$key]['txAvg'] = $traffic_data->txSpeedAvg;
            array_push($rxAvg, $this->cal($traffic_data->rxSpeedAvg));
            array_push($rxMax, $this->cal($traffic_data->rxSpeedMax));

            array_push($txAvg, $this->cal($traffic_data->txSpeedAvg));
            array_push($txMax, $this->cal($traffic_data->txSpeedMax));

            }elseif(empty($traffic_data)){
                $traffics[$key]['id']= null;
                $traffics[$key]['deviceID'] = null;
                $traffics[$key]['deviceName'] = null;
                $traffics[$key]['up'] = null;
                $traffics[$key]['down'] = null;
                $traffics[$key]['availbility'] = null;
                $traffics[$key]['rxMin'] = null;
            $traffics[$key]['rxMax'] = null;
            $traffics[$key]['rxAvg'] = null;
            $traffics[$key]['txMin'] = null;
            $traffics[$key]['txMax'] = null;
            $traffics[$key]['txAvg'] = null;
            array_push($rxAvg, 0);
            array_push($rxMax, 0);

            array_push($txAvg, 0);
            array_push($txMax, 0);
            }
        }  
        
    
       $traffics_data = json_encode($traffics);

    	return view('edit')->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))
        ->with('daterange',$request->daterange_edit)
        ->with('device',$device)
        ->with('traffics',json_decode($traffics_data, true));
    }


    public function update(Request $request)
    {
        
        $input = $request->all();
        
        // dd($input);
        // dd($request->txtTrafficID_23);
        for($i = 0;$i<=$request->txtCount;$i++)
        {
            // dd($input);
            if(($input['txtTrafficID'][$i]) != ""){
                // dd($input['txtTrafficID'][$i]);
                $data_update = ['deviceUp' => $input['txtUp'][$i],
                    'deviceDown' => $input['txtDown'][$i],
                    'deviceAva' => $input['txtAvailbility'][$i],
                    'rxSpeedMin' => $input['txtRxMin'][$i],
                    'rxSpeedMax' => $input['txtRxMax'][$i],
                    'rxSpeedAvg' => $input['txtRxAvg'][$i],
                    'txSpeedMin' => $input['txtTxMin'][$i],
                    'txSpeedMax' => $input['txtTxMax'][$i],
                    'txSpeedAvg' => $input['txtTxAvg'][$i],
            ];
            $traffic_update = Traffic::where('id',$input["txtTrafficID"][$i])->update($data_update);
            // dd($data_update);
                
            }else{
                $date_traffic =  Carbon::createFromFormat('d/m/Y', $input['txtDate'][$i])->format('Y-m-d 0:0:0');
                // $date_traffic = Carbon::createFromFormat('Y-m-d', $input['txtDate'][$i]);
                $data_insert = ['deviceID' =>  $input['txtDeviceID'],
                    'deviceName' =>  $input['txtDeviceName'],
                    'deviceUp' => $input['txtUp'][$i],
                    'deviceDown' => $input['txtDown'][$i],
                    'deviceAva' => $input['txtAvailbility'][$i],
                    'rxSpeedMin' => $input['txtRxMin'][$i],
                    'rxSpeedMax' => $input['txtRxMax'][$i],
                    'rxSpeedAvg' => $input['txtRxAvg'][$i],
                    'txSpeedMin' => $input['txtTxMin'][$i],
                    'txSpeedMax' => $input['txtTxMax'][$i],
                    'txSpeedAvg' => $input['txtTxAvg'][$i],
                    'pollTimeUtc' => $date_traffic
            ];

            if($input['txtDown'][$i] != ""){
                $traffic_insert = new Traffic;           
                $traffic_insert->create($data_insert);
            }
            //     var_dump($request->txtTrafficID_+$i);
            }
            // dd($request->txtUp_.$i);
        // }
        }

        return Redirect::back()->withErrors(['msg', 'The Message']);
    }

    public function index1(Request $request)
    {
        $circuit = $request->circuit_boss;
        $daterange = explode(" ",$request->daterange_boss);
        $from = Carbon::parse($daterange[0])->format('Y-m-d');
        $to = Carbon::parse($daterange[2])->format('Y-m-d');
        // dd($createdAt);
        $traffics = [];
        $period = CarbonPeriod::create($from, $to);
        // dd($period);
        $dates = [];
        
        $availability = [];
        $rxMin = [];
        $rxAvg = [];
        $rxMax = [];
        $rxPercentile = 0;

        $txMin = [];
        $txAvg = [];
        $txMax = [];
        $txPercentile = 0;

        $device = Device::where('deviceID', $circuit)->first();

        foreach ($period as $key => $date) {
            $traffics[$key]['date'] = $date->format('d/m/Y');
            array_push($dates, $date->format('Y-m-d'));

            $traffic_data = Traffic::where('deviceID', $circuit)->where('pollTimeUtc','like', '%' . $date->format('Y-m-d') . '%')->orderBy('pollTimeUtc','DESC')->first();
            if (!empty($traffic_data)){
                $traffics[$key]['up'] = $traffic_data->deviceUp;
                $traffics[$key]['down'] = $traffic_data->deviceDown;
                $traffics[$key]['availbility'] = $traffic_data->deviceAva;
            $traffics[$key]['rxMin'] = $traffic_data->rxSpeedMin;
            $traffics[$key]['rxMax'] = $traffic_data->rxSpeedMax;
            $traffics[$key]['rxAvg'] = $traffic_data->rxSpeedAvg;
            $traffics[$key]['txMin'] = $traffic_data->txSpeedMin;
            $traffics[$key]['txMax'] = $traffic_data->txSpeedMax;
            $traffics[$key]['txAvg'] = $traffic_data->txSpeedAvg;
            
            array_push($availability, $traffic_data->deviceAva);

            array_push($rxMin, $traffic_data->rxSpeedMin);
            array_push($rxAvg, $traffic_data->rxSpeedAvg);
            array_push($rxMax, $traffic_data->rxSpeedMax);
            
            array_push($txMin, $traffic_data->txSpeedMin);
            array_push($txAvg, $traffic_data->txSpeedAvg);
            array_push($txMax, $traffic_data->txSpeedMax);

            }elseif(empty($traffic_data)){
                $traffics[$key]['up'] = null;
                $traffics[$key]['down'] = null;
                $traffics[$key]['availbility'] = null;
                $traffics[$key]['rxMin'] = null;
            $traffics[$key]['rxMax'] = null;
            $traffics[$key]['rxAvg'] = null;
            $traffics[$key]['txMin'] = null;
            $traffics[$key]['txMax'] = null;
            $traffics[$key]['txAvg'] = null;
            array_push($availability, 0);
            array_push($rxMin, 0);
            array_push($rxAvg, 0);
            array_push($rxMax, 0);
            
            array_push($txMin, 0);
            array_push($txAvg, 0);
            array_push($txMax, 0);
            }
        } 

        ///////////// RX /////////////////
        $rx_Min = min($rxMin);
        $rx_Max = max($rxMax);
        $rx_unit = $this->unit($rx_Max);
        $rxMin_cal = [];
        $rxMax_cal = [];
        $rxAvg_cal = [];

        $sum_rx_Avg = array_sum($rxAvg);
        $rx_Avg_cal =  $sum_rx_Avg/($key+1);

        foreach ($rxMin as $key => $val0) {
            array_push($rxMin_cal, $this->data_graph($rx_Max, $val0));
        }

        foreach ($rxMax as $key => $val1) {
            array_push($rxMax_cal, $this->data_graph($rx_Max, $val1));
        }

        foreach ($rxAvg as $key => $val2) {
            array_push($rxAvg_cal, $this->data_graph($rx_Max, $val2));
        }

        $percent_rx_list = [5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95];
        // $rx_sort = sort($rxMax_cal);
        $data_rx_percentile = [];
        $percentile_rx = null;
        foreach ($percent_rx_list as $key => $percent_rx) {
            array_push($data_rx_percentile, $this->get_percentile($percent_rx, $rxMax_cal));

            if($percent_rx == 95){
                $percentile_rx = $this->get_percentile($percent_rx, $rxMax_cal);
            }
            // array_push($data_rx_percentile, $percent_rx);
        }
        array_push($data_rx_percentile, max($rxMax_cal));

        // dd($percentile_rx);

        ///////////// TX //////////////////
        $tx_Min = min($txMin);
        $tx_Max = max($txMax);
        $tx_unit = $this->unit($tx_Max);
        $txMin_cal = [];
        $txMax_cal = [];
        $txAvg_cal = [];
        $sum_tx_Avg = array_sum($txAvg);
        $tx_Avg_cal =  $sum_tx_Avg/($key+1);

        foreach ($txMin as $key => $val0) {
            array_push($txMin_cal, $this->data_graph($tx_Max, $val0));
        }

        foreach ($txMax as $key => $val3) {
            array_push($txMax_cal, $this->data_graph($tx_Max, $val3));
        }

        foreach ($txAvg as $key => $val4) {
            array_push($txAvg_cal, $this->data_graph($tx_Max, $val4));
        }

        $percent_tx_list = [5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95];
        // $rx_sort = sort($rxMax_cal);
        $data_tx_percentile = [];
        $percentile_tx = null;
        foreach ($percent_tx_list as $key1 => $percent_tx) {
            array_push($data_tx_percentile, $this->get_percentile($percent_tx, $txMax_cal));

            if($percent_tx == 95){
                $percentile_tx = $this->get_percentile($percent_tx, $txMax_cal);
            }
            // array_push($data_rx_percentile, $percent_rx);
        }
        array_push($data_tx_percentile, max($txMax_cal));
        
        ////////// availability ///////////////////
        $sum_availability = array_sum($availability);
        $availability_cal =  $sum_availability/($key+1);
        
        /////////// data in chart /////////////////
        $rxMin_chart = min($rxMin_cal);
        $rxMax_chart = max($rxMax_cal);
        $rxAvg_chart = array_sum($rxAvg_cal)/($key+1);

        $txMin_chart = min($txMin_cal);
        $txMax_chart = max($txMax_cal);
        $txAvg_chart = array_sum($txAvg_cal)/($key+1);
        
        $traffics_data = json_encode($traffics);
        
    	return view('chart1')->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))
        ->with('data_rx_percentile',json_encode($data_rx_percentile,JSON_NUMERIC_CHECK))
        ->with('rx_percentile',$percentile_rx)
        ->with('data_tx_percentile',json_encode($data_tx_percentile,JSON_NUMERIC_CHECK))
        ->with('tx_percentile',$percentile_tx)
        // ->with('rxMin_cal',json_encode($rxMin_cal,JSON_NUMERIC_CHECK))
        // ->with('rxAvg_cal',json_encode($rxAvg_cal,JSON_NUMERIC_CHECK))
        // ->with('rxMax_cal',json_encode($rxMax_cal,JSON_NUMERIC_CHECK))
        // ->with('txMin_cal',json_encode($txMin_cal,JSON_NUMERIC_CHECK))
        // ->with('txAvg_cal',json_encode($txAvg_cal,JSON_NUMERIC_CHECK))
        // ->with('txMax_cal',json_encode($txMax_cal,JSON_NUMERIC_CHECK))
        // ->with('availability_cal',$availability_cal)
        // ->with('rx_min',$rx_Min)
        // ->with('rx_Avg_cal',$rx_Avg_cal)
        // ->with('rx_max',$rx_Max)
        // ->with('tx_min',$tx_Min)
        // ->with('tx_Avg_cal',$tx_Avg_cal)
        // ->with('tx_max',$tx_Max)
        ->with('rx_unit',$rx_unit)
        ->with('tx_unit',$tx_unit)
        // ->with('rxMin_chart',$rxMin_chart)
        // ->with('rxMax_chart',$rxMax_chart)
        // ->with('rxAvg_chart',$rxAvg_chart)
        // ->with('rxPercentile_chart',$rxPercentile)
        // ->with('txMin_chart',$txMin_chart)
        // ->with('txMax_chart',$txMax_chart)
        // ->with('txAvg_chart',$txAvg_chart)
        // ->with('txPercentile_chart',$txPercentile)
        // ->with('percentile_chart',$request->percent_val)
        ->with('daterange',$request->daterange_boss)
        ->with('device',$device)
        ->with('traffics',json_decode($traffics_data, true));
    }
    
}