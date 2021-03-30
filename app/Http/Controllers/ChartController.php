<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Traffic;
use App\Models\Device;
use PDF;

class ChartController extends Controller
{
    public function unit($val_max){
       if ($val_max < 1000000){
            $unit = "Kbps";
       }elseif ($val_max > 1000000 && $val_max < 1000000000){
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
        }elseif ($val_max > 1000000 && $val_max < 1000000000){
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
                $traffics[$key]['up'] = $traffic_data->deviceUp;
                $traffics[$key]['down'] = $traffic_data->deviceDown;
                $traffics[$key]['availbility'] = $traffic_data->deviceAva;
            $traffics[$key]['rxMin'] = $traffic_data->rxSpeedMin;
            $traffics[$key]['rxMax'] = $traffic_data->rxSpeedMax;
            $traffics[$key]['rxAvg'] = $traffic_data->rxSpeedAvg;
            $traffics[$key]['txMin'] = $traffic_data->txSpeedMin;
            $traffics[$key]['txMax'] = $traffic_data->txSpeedMax;
            $traffics[$key]['txAvg'] = $traffic_data->txSpeedAvg;

            array_push($rxAvg, $traffic_data->rxSpeedAvg);
            array_push($rxMax, $traffic_data->rxSpeedMax);

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
            array_push($rxAvg, 0);
            array_push($rxMax, 0);

            array_push($txAvg, 0);
            array_push($txMax, 0);
            }
        } 

        ///////////// RX /////////////////
        $rx_Max = max($rxMax);
        $rx_unit = $this->unit($rx_Max);
        $rxMax_cal = [];
        $rxAvg_cal = [];

        foreach ($rxMax as $key => $val1) {
            array_push($rxMax_cal, $this->data_graph($rx_Max, $val1));
        }

        foreach ($rxAvg as $key => $val2) {
            array_push($rxAvg_cal, $this->data_graph($rx_Max, $val2));
        }

        ///////////// TX //////////////////
        $tx_Max = max($txMax);
        $tx_unit = $this->unit($tx_Max);
        $txMax_cal = [];
        $txAvg_cal = [];

        foreach ($txMax as $key => $val3) {
            array_push($txMax_cal, $this->data_graph($tx_Max, $val3));
        }

        foreach ($rxAvg as $key => $val4) {
            array_push($txAvg_cal, $this->data_graph($tx_Max, $val4));
        }

        // dd($rxMax_cal);
        // dd($this->unit(max($rxMax)));

        
    
       $traffics_data = json_encode($traffics);

    	return view('chart')->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))
        ->with('rxAvg_cal',json_encode($rxAvg,JSON_NUMERIC_CHECK))
        ->with('rxMax_cal',json_encode($rxMax,JSON_NUMERIC_CHECK))
        ->with('txAvg_cal',json_encode($txAvg,JSON_NUMERIC_CHECK))
        ->with('txMax_cal',json_encode($txMax,JSON_NUMERIC_CHECK))
        ->with('rx_unit',$rx_unit)
        ->with('tx_unit',$tx_unit)
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
                $traffics[$key]['up'] = $traffic_data->deviceUp;
                $traffics[$key]['down'] = $traffic_data->deviceDown;
                $traffics[$key]['availbility'] = $traffic_data->deviceAva;
            $traffics[$key]['rxMin'] = $this->cal($traffic_data->rxSpeedMin);
            $traffics[$key]['rxMax'] = $this->cal($traffic_data->rxSpeedMax);
            $traffics[$key]['rxAvg'] = $this->cal($traffic_data->rxSpeedAvg);
            $traffics[$key]['txMin'] = $this->cal($traffic_data->txSpeedMin);
            $traffics[$key]['txMax'] = $this->cal($traffic_data->txSpeedMax);
            $traffics[$key]['txAvg'] = $this->cal($traffic_data->txSpeedAvg);
            array_push($rxAvg, $this->cal($traffic_data->rxSpeedAvg));
            array_push($rxMax, $this->cal($traffic_data->rxSpeedMax));

            array_push($txAvg, $this->cal($traffic_data->txSpeedAvg));
            array_push($txMax, $this->cal($traffic_data->txSpeedMax));

            }elseif(empty($traffic_data)){
                $traffics[$key]['id']= null;
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
        // dd($input["txtRxMax_".$i]);
        // dd($request->txtTrafficID_23);
        for($i = 0;$i<=$request->txtCount;$i++)
        {
            if(($input["txtTrafficID_".$i]) != ""){
            $data_update = ['deviceUp' => $input["txtUp_".$i],
                    'deviceDown' => $input["txtDown_".$i],
                    'deviceAva' => $input["txtAvailbility_".$i],
                    'rxSpeedMin' => $this->cal_reverse($input["txtRxMin_".$i]),
                    'rxSpeedMax' => $this->cal_reverse($input["txtRxAvg_".$i]),
                    'rxSpeedAvg' => $this->cal_reverse($input["txtRxMax_".$i]),
                    'txSpeedMin' => $this->cal_reverse($input["txtTxMin_".$i]),
                    'txSpeedMax' => $this->cal_reverse($input["txtTxAvg_".$i]),
                    'txSpeedAvg' => $this->cal_reverse($input["txtTxMax_".$i]),
            ];
            $traffic_update = Traffic::where('id',$input["txtTrafficID_".$i])->update($data_update);
            dd($traffic_update);
                
            }else{
                var_dump($request->txtTrafficID_+$i);
            }
            // dd($request->txtUp_.$i);
        }
    }

    
}