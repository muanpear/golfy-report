<!doctype html>
<html>
<head>
    <title>Line Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script  type="text/javascript"src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>

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
  font-size: 10px;
}
	</style>
</head>
<body>
    <div class="container">
      <a class="btn btn-primary" href="{{ URL::to('/chart/pdf') }}">Export to PDF</a>
        <div class="row">
    <div class="col-md-12">
        <div style="border-block-color: black; border-width: 10px 1px;">
		<canvas id="line-chart"  width="100" height="40" ></canvas>
    </div>
    </div>
</div>
    </div>
    <hr>
    <div class="container">
     <div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped table-sm">
          <thead style="background-color:#778899">
            <tr>
  <th rowspan="2" class="vertical-center">Date</th>
  <th rowspan="2" class="vertical-center">Up(Hour)</th>
  <th rowspan="2" class="vertical-center">Down(Hour)</th>
  <th rowspan="2" class="vertical-center">Availbility(%)</th>
  <th colspan="3"><center>Receive(Rx)</center></th>
  <th colspan="3"><center>Transmit(Tx)</center></th>
</tr>

<tr>

  <th><center>Rx Min</center></th>
  <th><center>Rx Avg</center></th>
  <th><center>Rx Max</center></th>
  <th><center>Tx Min</center></th>
  <th><center>Tx Avg</center></th>
  <th><center>Tx Max</center></th>
</tr>
</thead>
<tbody>

  @foreach ($traffics as $key => $vl)
  <tr>
  <td><center>{{ $vl["date"] }}</center></td>
  <td><center>100%</center></td>
  <td><center>100%</center></td>
  <td><center>100%</center></td>
  <td><center>{{ $vl['rxMin'] }}</center></td>
  <td><center>{{ $vl['rxAvg'] }}</center></td>
  <td><center>{{ $vl['rxMax'] }}</center></td>
  <td><center>{{ $vl['txMin'] }}</center></td>
  <td><center>{{ $vl['txAvg'] }}</center></td>
  <td><center>{{ $vl['txMax'] }}</center></td>
</tr>
  {{-- <tr>
  <td><center><input type="text" value=""></center></td>
  <td><center><input type="text" value=""></center></td>
  <td><center><input type="text" value=""></center></td>
  <td><center><input type="text" value=""></center></td>
  <td><center><input type="text" class="number" value="{{ $vl['rx'] }}" style="width:70px"></center></td>
  <td><center><input type="text" value="{{ $vl['rx'] }}"></center></td>
  <td><center><input type="text" value="{{ $vl['rx'] }}"></center></td>
  <td><center><input type="text" value="{{ $vl['tx'] }}"></center></td>
  <td><center><input type="text" value="{{ $vl['tx'] }}"></center></td>
  <td><center><input type="text" value="{{ $vl['tx'] }}"></center></td>
</tr> --}}
@endforeach
</tbody>
</table>
    </div>
     </div>
</div>
    </div>
	</div>

	<script type="text/javascript">
  $( document ).ready(function() {
    $('.number').keypress(function(event) { if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57 || event.which == 190)) { event.preventDefault(); } });
//   $('.number').keypress(function(event) {
//   if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
//     event.preventDefault();
//   }
// });
});

        function newDate(day, month) {
  return moment().date(day).month(month);
}

		new Chart(document.getElementById("line-chart"), {
  type: 'line',
  data: {

                labels: 
                  {!! $dates !!}
                  
                ,
    datasets: [{ 
        data: {!! $rxs !!},
        label: "RX",
        borderColor: "#08a3bc",
    
				backgroundColor: "#08a3bc",
        // fill: false
      }, { 
        data: {!! $txs !!},
        label: "TX",
        borderColor: "#e55791",
        backgroundColor: "#e55791",
        // fill: false
      },
    ]
  },
  options: {
      responsive:true,
      
      scales: {
      yAxes: [{
        // stacked: true,
        scaleLabel: {
        display: true,
        labelString: 'Interface Traffic (Mbps)',
        ticks: {
              beginAtZero: true,
              stepSize: 0.5,
              callback: function(value, index, values) {
                return value;
              }
            }
      }
      }],
       xAxes: [{
        ticks: {
          autoSkip: true,
        // maxRotation: 90,
        minRotation: 45,
        },
      }]
    },
    title: {
      display: true,
      text: 'VPN1000000'
    }
  }
});


	</script>
</body>
	
</html>