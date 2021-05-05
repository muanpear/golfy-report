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
    }
	</style>
</head>
<body>
    <div class="container">
      <center><table>
        <tr>
          <th><img src="{{ asset('images/hd.jpg') }}" width="100%"></th>
          {{-- <th><h4 style="padding: 10px; text-align:right">Interface Summary Report</h4></th> --}}
        </tr>
      </table></center>
    </div>
    <br>

    <div class="container">
      <div class="row">
        <div class="col-6">
          <p><strong>CIRCUIT NO. :</strong> {{$device->deviceName}}</p>
          <p><strong>SPEED  :</strong> {{$device->deviceSpeed}} Mbps</p>
          {{-- <p><strong>DESCRIPTION  :</strong>{{$device->deviceDecription}}</p> --}}
        </div>
        <div class="col-6">
          <p><strong>REPORT DATE :</strong> {{$daterange}}</p>
          <p><strong>GENERATE BY :</strong> NQC</p>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <p><strong>DESCRIPTION  :</strong> {{$device->deviceDecription}}</p>
      </div>
    </div>

    <div class="container">
      <div class="card">
        <div class="row">
          <center><table width="85%">
            <tr>
              <th width="100%"><div id="chart"></div></th>
            </tr>
            <tr>
                <th width="100%"><hr></th>
            </tr>
            <tr>
                <th width="100%"><div id="chart1"></div></th>
            </tr>
          </table></center>
        </div>
      </div>
    </div>

  <div class="container">
    <div class="row">
      <img src="{{ asset('images/ft.jpg') }}" width="100%">
      <div class="col-12">
    </div>
    </div>
  </div>

<script type="text/javascript">
  $( document ).ready(function() {
    //// rx
    var options = {
          series: [{
            name: "RX",
          data: {!! $data_rx_percentile !!}
        }],
        chart: {
          type: 'area',
          height: 320,
          zoom: {
            enabled: false
          }
        },
        annotations: {
          yaxis: [{
            y: {!! $rx_percentile !!},
            borderColor: '#FF0000',
            label: {
              show: true,
              text: '95th Percentile ~ {!! $rx_percentile !!} {!! $rx_unit !!}',
              style: {
                color: "#FF0000",
                background: '#00E396'
              }
            }
          }],
          xaxis: [{
            x: 95,
            borderColor: '#FF0000',
            // label: {
            //   show: true,
            //   text: 'sssss',
            //   style: {
            //     color: "#fff",
            //     background: '#00E396'
            //   }
            // }
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
        title: {
          text: '(Input) 95th Percentile',
          align: 'left'
        },
        labels: [5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],
        // legend: {
        //   horizontalAlign: 'left'
        // }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      


    //// tx

    var options1 = {
          series: [{
            name: "TX",
          data: {!! $data_tx_percentile !!}
        }],
        chart: {
          type: 'area',
          height: 320,
          zoom: {
            enabled: false
          }
        },
        annotations: {
          yaxis: [{
            y: {!! $tx_percentile !!},
            borderColor: '#FF0000',
            label: {
              show: true,
              text: '95th Percentile ~ {!! $tx_percentile !!} {!! $tx_unit !!}',
              style: {
                color: "#FF0000",
                background: '#00E396'
              }
            }
          }],
          xaxis: [{
            x: 95,
            borderColor: '#FF0000',
            // label: {
            //   show: true,
            //   text: 'sssss',
            //   style: {
            //     color: "#fff",
            //     background: '#00E396'
            //   }
            // }
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
        title: {
          text: '(Output) 95th Percentile',
          align: 'left'
        },
        labels: [5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],
        // legend: {
        //   horizontalAlign: 'left'
        // }
        };

        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();
	
});

</script>
</body>	
</html>