<!doctype html>
<html>
<head>
    <title>Golfy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script  type="text/javascript"src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  
  
  <style>
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}

    .vertical-center {
      text-align: center;
      vertical-align: middle;
    }

    .table-sm {
      font-size: 8.5px;
      padding: 2px;
    }
	</style>
</head>
<body>
    <div class="container-fluid">
      <center><table>
        <tr>
          <th><img src="{{ asset('images/hd.jpg') }}" width="100%"></th>
          {{-- <th><h4 style="padding: 10px; text-align:right">Interface Summary Report</h4></th> --}}
        </tr>
      </table></center>
    </div>
    <br><br>

    <div class="container-fluid">
      <div class="row">
        <div class="col-6">
          <p><strong>CIRCUIT NO. :</strong> {{$device->deviceName}}</p>
          <p><strong>SPEED  :</strong> {{$device->deviceSpeed}} Mbps</p>
          {{-- <p><strong>DESCRIPTION  :</strong>{{$device->deviceDecription}}</p> --}}
        </div>
        <div class="col-6">
          <p><strong>REPORT DATE :</strong> {{$daterange}}</p>
          {{-- <p><strong>GENERATE BY :</strong> NQC</p> --}}
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <p><strong>DESCRIPTION  :</strong> {{$device->deviceDecription}}</p>
      </div>
    </div>
    <br><br>

    <div class="container-fluid">
      <div class="card">
        <div class="row">
          <center><table width="85%">
            <tr>
              <th width="45%"><div id="chart"></div></th>
              <th width="5%"></td>
              <th width="45%"><div id="chart1"></div></th>
            </tr>
            <tr>
            <td width="45%"><center><small>Input : Min {{number_format($rxMin_chart, 2) }} {{$rx_unit}}, Avg {{number_format($rxAvg_chart, 2) }} {{$rx_unit}}, Max {{number_format($rxMax_chart, 2) }} {{$rx_unit}}</small></center></td>
            <td width="5%"></td>
            <td width="45%"><center><small>Output : Min {{number_format($txMin_chart, 2) }} {{$tx_unit}}, Avg {{number_format($txAvg_chart, 2) }} {{$tx_unit}}, Max {{number_format($txMax_chart, 2) }} {{$tx_unit}}</small></center></td>
            </tr>
          </table></center>
        </div>
      </div>
    </div>
    <br><br>

    {{-- <div class="container">
      <center><table width="80%">
        <tr>
          <td width="50%"><p>CIRCUIT NO. : {{$device->deviceName}}</p></td>
          <td width="50%"><p>REPORT DATE : {{$daterange}}</p></td>
        </tr>
        <tr>
          <td width="50%"><p>SPEED  : {{$device->deviceSpeed}} MBps</p></td>
          <td width="50%"><p>GENERATE BY : {{ucfirst(Auth::user()->name)}}</p></td>
        </tr>
        <tr>
          <td width="50%"><p>DESCRIPTION  : {{$device->deviceDecription}}</p></td>
        </tr>
      </table></center>
    </div> --}}


      {{-- <center><table width="70%">
        <tr>
          <th width="50%"><div id="chart"></div></th>
          <th width="50%"><div id="chart1"></div></th>
        </tr>
        <tr>
          <td width="50%"><center><small>Input Utilization: Min {{number_format($rxMin_chart, 2) }} {{$rx_unit}}, Avg {{number_format($rxAvg_chart, 2) }} {{$rx_unit}}, Max {{number_format($rxMax_chart, 2) }} {{$rx_unit}}</small></center></td>
          <td width="50%"><center><small>Output Utilization: Min {{number_format($txMin_chart, 2) }} {{$tx_unit}}, Avg {{number_format($txAvg_chart, 2) }} {{$tx_unit}}, Max {{number_format($txMax_chart, 2) }} {{$tx_unit}}</small></center></td>
        </tr>
      </table></center> --}}
     
      {{-- <a class="btn btn-primary" href="{{ URL::to('/chart/pdf') }}">Export to PDF</a> --}}
 
    
    <hr>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <table class="table table-sm">

                <thead style="background-color:#1B998B">
                  <tr>
                    <th rowspan="2" class="vertical-center">Date</th>
                    <th rowspan="2" class="vertical-center">Up(Hour)</th>
                    <th rowspan="2" class="vertical-center">Down(Hour)</th>
                    <th rowspan="2" class="vertical-center">Availbility(%)</th>
                    <th colspan="3"><center>Input traffic</center></th>
                    <th colspan="3"><center>Output traffic</center></th>
                  </tr>
                            
                  <tr>
                    <th class="border-bottom-0" style="background-color:#B6CFB6"><center>Min (bps)</center></th>
                    <th class="border-bottom-0" style="background-color:#B6CFB6"><center>Avg (bps)</center></th>
                    <th class="border-bottom-0" style="background-color:#B6CFB6"><center>Max (bps)</center></th>
                    <th class="border-bottom-0" style="background-color:#B6CFB6"><center>Min (bps)</center></th>
                    <th class="border-bottom-0" style="background-color:#B6CFB6"><center>Avg (bps)</center></th>
                    <th class="border-bottom-0" style="background-color:#B6CFB6"><center>Max (bps)</center></th>
                  </tr>

                </thead>
                    <tbody>
                      @foreach ($traffics as $key => $vl)
                        <tr style="border-bottom-color: #1B998B">
                            <td><center>{{ $vl["date"] }}</center></td>
                            <td><center>{{ $vl['up'] }}</center></td>
                            <td><center>{{ $vl['down'] }}</center></td>
                            <td><center>{{ $vl['availbility'] }}</center></td>
                            <td><center>{{ number_format($vl['rxMin'], 0) }}</center></td>
                            <td><center>{{ number_format($vl['rxAvg'], 0) }}</center></td>
                            <td><center>{{ number_format($vl['rxMax'], 0) }}</center></td>
                            <td><center>{{ number_format($vl['txMin'], 0) }}</center></td>
                            <td><center>{{ number_format($vl['txAvg'], 0) }}</center></td>
                            <td><center>{{ number_format($vl['txMax'], 0) }}</center></td>
                          </tr>
                      @endforeach
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <th><center>{{number_format($availability_cal, 2)}}</center></th>
                            <th><center>{{number_format($rx_min, 0)}}</center></th>
                            <th><center>{{number_format($rx_Avg_cal, 0)}}</center></th>
                            <th><center>{{number_format($rx_max, 0)}}</center></th>
                            <th><center>{{number_format($tx_min, 0)}}</center></th>
                            <th><center>{{number_format($tx_Avg_cal, 0)}}</center></td>
                            <th><center>{{number_format($tx_max, 0)}}</center></th>
                          </tr>
                    </tbody>
            </table>
        </div>
      </div>
    </div>

  <div class="container-fluid">
    <div class="row">
      <img src="{{ asset('images/ft.jpg') }}" width="100%">
      <div class="col-12">
    </div>
    </div>
  </div>

<script type="text/javascript">
  $( document ).ready(function() {
    //// rx
    let rxPercentile = {!! $rxPercentile_chart !!};

    if (rxPercentile != 0){
    var options = {
      colors: ['#4BD0B8', '#1B998B', '#E91E63'],
      series: [{
        name: 'Min',
        data: {!! $rxMin_cal !!}
        }, {
        name: 'Avg',
        data: {!! $rxAvg_cal !!}
        }, {
        name: 'Max',
        data: {!! $rxMax_cal !!}
      }],
      chart: {
        height: 220,
        type: 'area'
      },
      annotations: {
          yaxis: [{
            y: {!! $rxPercentile_chart !!},
            borderColor: '#FF0000',
            label: {
              show: true,
              text: '{!! $percentile_chart !!}th Percentile ~{!! $rxPercentile_chart !!} {!! $rx_unit !!}',
              style: {
                color: "#fff",
                background: '#00E396'
              }
            }
          }],
        },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2,
      },
      yaxis: {
        
        title: {
          text: 'Input traffic ( {!! $rx_unit !!} )',
          rotate: -90,
        },
        labels: {
          formatter: function (value) {
          return value.toFixed(1);
          }
        },
      },
      xaxis: {
        type: 'datetime',
        categories: {!! $dates !!},
        labels: {
          format: 'd/M',
        },
      },
      tooltip: {
        x: {
          format: 'd/M',
        },
      },
    };

    }else if (rxPercentile == 0){
    var options = {
      colors: ['#4BD0B8', '#1B998B', '#E91E63'],
      series: [{
        name: 'Min',
        data: {!! $rxMin_cal !!}
        }, {
        name: 'Avg',
        data: {!! $rxAvg_cal !!}
        }, {
        name: 'Max',
        data: {!! $rxMax_cal !!}
      }],
      chart: {
        height: 220,
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2,
      },
      yaxis: {
        
        title: {
          text: 'Input traffic ( {!! $rx_unit !!} )',
          rotate: -90,
        },
        labels: {
          formatter: function (value) {
          return value.toFixed(1);
          }
        },
      },
      xaxis: {
        type: 'datetime',
        categories: {!! $dates !!},
        labels: {
          format: 'd/M',
        },
      },
      tooltip: {
        x: {
          format: 'd/M',
        },
      },
    };

    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();


    //// tx

    let txPercentile = {!! $txPercentile_chart !!};

    if (txPercentile != 0){
    var options1 = {
      colors: ['#4BD0B8','#1B998B', '#E91E63'],
      series: [{
        name: 'Min',
        data: {!! $txMin_cal !!}
        }, {
        name: 'Avg',
        data: {!! $txAvg_cal !!}
        }, {
        name: 'Max',
        data: {!! $txMax_cal !!}
      }],
      chart: {
        height: 220,
        type: 'area'
      },
      annotations: {
          yaxis: [{
            y: {!! $txPercentile_chart !!},
            borderColor: '#FF0000',
            label: {
              show: true,
              text: '{!! $percentile_chart !!}th Percentile ~{!! $txPercentile_chart !!} {!! $tx_unit !!}',
              style: {
                color: "#fff",
                background: '#00E396'
              }
            }
          }],
        },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2,
      },
      yaxis: {
        title: {
          text: 'Output traffic ( {!! $tx_unit !!} )',
          rotate: -90,
        },
        labels: {
          formatter: function (value) {
          return value.toFixed(1);
          }
        },
      },
      xaxis: {
        type: 'datetime',
        categories: {!! $dates !!},
        labels: {
          format: 'd/M',
        },
      },
      tooltip: {
        x: {
          format: 'd/M',
        },
      },
    };

    }else if (txPercentile == 0){
    var options1 = {
      colors: ['#4BD0B8','#1B998B', '#E91E63'],
      series: [{
        name: 'Min',
        data: {!! $txMin_cal !!}
        }, {
        name: 'Avg',
        data: {!! $txAvg_cal !!}
        }, {
        name: 'Max',
        data: {!! $txMax_cal !!}
      }],
      chart: {
        height: 220,
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2,
      },
      yaxis: {
        title: {
          text: 'Output traffic ( {!! $tx_unit !!} )',
          rotate: -90,
        },
        labels: {
          formatter: function (value) {
          return value.toFixed(1);
          }
        },
      },
      xaxis: {
        type: 'datetime',
        categories: {!! $dates !!},
        labels: {
          format: 'd/M',
        },
      },
      tooltip: {
        x: {
          format: 'd/M',
        },
      },
    };
    }

    var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
    chart1.render();
// });

//         function newDate(day, month) {
//   return moment().date(day).month(month);
// }
	
});

</script>
</body>	
</html>