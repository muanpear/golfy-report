<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Traffic;
use PDF;

class ChartController extends Controller
{
    public function index1()
    {
        // $pdf = \PDF::loadView('welcome');
        // // $pdf->setOption('enable-javascript', true);
        // // $pdf->setOption('javascript-delay', 15000);
        // // $pdf->setOption('enable-smart-shrinking', true);
        // // $pdf->setOption('no-stop-slow-scripts', true);
        // // return $pdf->download('chart.pdf');
        //  return $pdf->download('chart.pdf');
        // return $pdf = PDF::loadView('welcome')->setPaper('a4', 'landscape')->setWarnings(false)
// ->setPaper('a4')
// ->setOrientation('landscape')
// ->setOptions(['enable-javascript', true,'javascript-delay', 13000,'no-stop-slow-scripts', true,'isPhpEnabled', true])
// ->setOptions('javascript-delay', 13000)
// ->setOption('images', true)
// ->setOption('enable-smart-shrinking', true)
// ->setOption('no-stop-slow-scripts', true)
// ->save(storage_path()."/xxx.pdf");
// ->stream('test.pdf');
return view('welcome');
    }

    public function cal($val){
        $cal = $val / 1000 / 1000;
        $val = number_format((float)$cal, 2, '.', ''); 
        return $val;
    }
    
    public function index(Request $request)
    {
        
        $daterange = explode(" ",$request->daterange);
        $from = Carbon::parse($daterange[0])->format('Y-m-d');
        $to = Carbon::parse($daterange[2])->format('Y-m-d');
        // dd($createdAt);
        $traffics = [];
        $period = CarbonPeriod::create($from, $to);
        // dd($period);
        $dates = [];
        // Iterate over the period
        foreach ($period as $key => $date) {
            $traffics[$key]['date'] = $date->format('d/m/Y');
            array_push($dates, $date->format('Y-m-d'));
        }  
    
        
        $rx = Traffic::take(31)->get();
    
        $rxs = [];
        $txs = [];
        foreach ($rx as $key => $val) {
            $traffics[$key]['rxMin'] = $this->cal($val['rxSpeedMin']);
            $traffics[$key]['rxMax'] = $this->cal($val['rxSpeedMax']);
            $traffics[$key]['rxAvg'] = $this->cal($val['rxSpeedAvg']);
            $traffics[$key]['txMin'] = $this->cal($val['txSpeedMin']);
            $traffics[$key]['txMax'] = $this->cal($val['txSpeedMax']);
            $traffics[$key]['txAvg'] = $this->cal($val['txSpeedAvg']);
            array_push($rxs, $this->cal($val['rxSpeedAvg']));
            array_push($txs, $this->cal($val['txSpeedAvg']));
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
    //    $traffics1 = json_decode($traffics);
        // dd( json_decode($traffics));
    	return view('chart')->with('dates',json_encode($dates,JSON_NUMERIC_CHECK))
        ->with('rxs',json_encode($rxs,JSON_NUMERIC_CHECK))
        ->with('txs',json_encode($txs,JSON_NUMERIC_CHECK))
        ->with('traffics',json_decode($traffics, true));
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
    
        $rxs = [];
        $txs = [];
        foreach ($rx as $key => $val) {
            $traffics[$key]['rxMin'] = $this->cal($val['rxSpeedMin']);
            $traffics[$key]['rxMax'] = $this->cal($val['rxSpeedMax']);
            $traffics[$key]['rxAvg'] = $this->cal($val['rxSpeedAvg']);
            $traffics[$key]['txMin'] = $this->cal($val['txSpeedMin']);
            $traffics[$key]['txMax'] = $this->cal($val['txSpeedMax']);
            $traffics[$key]['txAvg'] = $this->cal($val['txSpeedAvg']);
            array_push($rxs, $this->cal($val['rxSpeedAvg']));
            array_push($txs, $this->cal($val['txSpeedAvg']));
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

    
}