<!doctype html>
<html>
<head>
    <title>Golfy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script  type="text/javascript"src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> --}}
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
      <table width="100%" style="background-color: #ff9e66;">
        <tr>
          <th></th>
          <th><h4 style="padding: 10px; text-align:right">Edit</h4></th>
        </tr>
      </table>
    </div>
    <br>

    <div class="container">
      <center><table width="80%">
        <tr>
          <td width="50%"><p>CIRCUIT NO. : {{$device->deviceName}}</p></td>
          <td width="50%"><p>REPORT DATE : {{$daterange}}</p></td>
        </tr>
        <tr>
          <td width="50%"><p>DESCRIPTION  : {{$device->deviceDecription}}</p></td>
          <td width="50%">
            <form action="{{ URL::to('edit-update') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-primary">Edit !!!</button>
            <button type="type" class="btn btn-outline-warning">Clear !!!</button>
          </td>
        </tr>
        <tr>
          <td width="50%"><p>SPEED  : {{$device->deviceSpeed}}</p></td>
        </tr>
      </table></center>
    </div>

    <hr>
    <div class="container-fluid">
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
  <td><center>{{ $vl["date"] }}<input type="text" name="txtTrafficID_{{$key}}" id="txtTrafficID_{{$key}}" value="{{ $vl["id"] }}"></center></td>
  <td><center><input type="text" name="txtUp_{{$key}}" id="txtUp_{{$key}}" value="{{ $vl["up"] }}"></center></td>
  <td><center><input type="text" name="txtDown_{{$key}}" id="txtDown_{{$key}}" value="{{ $vl["down"] }}"></center></td>
  <td><center><input type="text" name="txtAvailbility_{{$key}}" id="txtAvailbility_{{$key}}" value="{{ $vl["availbility"] }}"></center></td>
  <td><center><input type="text" name="txtRxMin_{{$key}}" id="txtRxMin_{{$key}}" value="{{ $vl["rxMin"] }}"></center></td>
  <td><center><input type="text" name="txtRxAvg_{{$key}}" id="txtRxAvg_{{$key}}" value="{{ $vl["rxAvg"] }}"></center></td>
  <td><center><input type="text" name="txtRxMax_{{$key}}" id="txtRxMax_{{$key}}" value="{{ $vl["rxMax"] }}"></center></td>
  <td><center><input type="text" name="txtTxMin_{{$key}}" id="txtTxMin_{{$key}}" value="{{ $vl["txMin"] }}"></center></td>
  <td><center><input type="text" name="txtTxAvg_{{$key}}" id="txtTxAvg_{{$key}}" value="{{ $vl["txAvg"] }}"></center></td>
  <td><center><input type="text" name="txtTxMax_{{$key}}" id="txtTxMax_{{$key}}" value="{{ $vl["txMax"] }}"></center></td>
</tr>

@endforeach
  <input type="text" name="txtCount" id="txtCount" value="{{ $key }}">
</tbody>
</table>
    </div>
     </div>
</div>



    {{-- </div>
	</div> --}}


<script type="text/javascript">
  $( document ).ready(function() {
    
});

</script>
</body>	
</html>