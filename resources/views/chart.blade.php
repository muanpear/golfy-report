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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
    <div class="container-fluid">
      <table width="100%" style="background-color: #6699FF;">
        <tr>
          <th><img src="https://1000logos.net/wp-content/uploads/2017/12/Pornhub-Logo.png" width="128" height="64"></th>
          <th><h4 style="padding: 10px; text-align:right">Interface Summary Report</h4></th>
        </tr>
      </table>

      <center><table width="80%">
        <tr>
          <th width="50%"><div id="chart"></div></th>
          <th width="50%"><div id="chart1"></div></th>
        </tr>
      </table></center>
     
      {{-- <a class="btn btn-primary" href="{{ URL::to('/chart/pdf') }}">Export to PDF</a> --}}
 
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
    
var options = {
  
  colors: ['#546E7A', '#E91E63'],
          series: [{
          name: 'Avg',
          data: {!! $rxs !!}
        }, {
          name: 'Max',
          data: {!! $txs !!}
        }],
          chart: {
          height: 250,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        yaxis: {
          title: {
          text: 'Interface',
          rotate: -90,
      },
  labels: {
    formatter: function (value) {
      return value + "kb/s";
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

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        var chart1 = new ApexCharts(document.querySelector("#chart1"), options);
        chart1.render();
// });

        function newDate(day, month) {
  return moment().date(day).month(month);
}

	
});


	</script>
</body>
	
</html>