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
    tr.nook {
  border-collapse:separate; 
  border-spacing: 0 1em;
}
td.pn{
  padding: 10px 0px 10px 0px;
}
    tr.nook:nth-child(even) { border-spacing: 10px; border-collapse: separate; background-color:#FF99CC;}
	</style>
</head>
<body>
    <div class="container-fluid">
      <table width="100%" style="background-color: #aca29d;">
        <tr>
          <th><h4 style="padding: 10px; text-align:left">Edit</h4></th>
          <th>
            <h4 style="padding: 10px; text-align:right">
            <form action="{{ URL::to('chart') }}" method="get" target="_blank">
              @csrf
            <input type="hidden" id="customer" name="customer" required value="{{$device->groupID}}">
            <input type="hidden" id="circuit" name="circuit" required value="{{$device->deviceID}}">
            <input type="hidden" id="daterange" name="daterange" required value="{{$daterange}}"> 
            <button type="submit" class="btn btn-info">Graph it !!!</button> 
            </form>
            </h4>
          </th>
        </tr>
      </table>
    </div>
    <br>

    <div class="container">
      <center><table width="80%">
        <tr>
          <td width="50%"><p>CIRCUIT NO. : {{$device->deviceName}}</p></td>
          <td width="50%"><p>DATE : {{$daterange}}</p></td>
        </tr>
        <tr>
          <td width="50%"><p>DESCRIPTION  : {{$device->deviceDecription}}</p></td>
          <td width="50%"><p>SPEED  : {{$device->deviceSpeed}}</p></td>
        </tr>
        <tr>
          <td width="50%">
          </td>
          <td width="50%">
            <form action="{{ URL::to('edit-update') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-outline-primary">Save Edit !!!</button>
            <button type="type" class="btn btn-outline-warning">Back !!!</button>
            {{-- <button type="type" class="btn btn-outline-info">Graph it !!!</button> --}}
          </td>
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
            <tr class="nook">
              <td class="pn"><center>
                {{ $vl["date"] }}
                <input type="hidden" name="txtTrafficID[]" id="txtTrafficID_{{$key}}" value="{{ $vl["id"] }}">
                <input type="hidden" name="txtDate[]" id="txtDate{{$key}}" value="{{ $vl["date"] }}">
              </center></td>
  <td class="pn"><center><input type='text' class="floatNumberField" readonly name="txtUp[]" id="txtUp_{{$key}}" value="{{ $vl["up"] }}"></center></td>
  <td class="pn"><center><input type='number'  name="txtDown[]" id="txtDown_{{$key}}" value="{{ $vl["down"] }}"></center></td>
  <td class="pn"><center><input type="number" readonly name="txtAvailbility[]" id="txtAvailbility_{{$key}}" value="{{ $vl["availbility"] }}"></center></td>
  <td class="pn"><center><input type="number" name="txtRxMin[]" id="txtRxMin_{{$key}}" value="{{ $vl["rxMin"] }}"></center></td>
  <td class="pn"><center><input type="number" name="txtRxAvg[]" id="txtRxAvg_{{$key}}" value="{{ $vl["rxAvg"] }}"></center></td>
  <td class="pn"><center><input type="number" name="txtRxMax[]" id="txtRxMax_{{$key}}" value="{{ $vl["rxMax"] }}"></center></td>
  <td class="pn"><center><input type="number" name="txtTxMin[]" id="txtTxMin_{{$key}}" value="{{ $vl["txMin"] }}"></center></td>
  <td class="pn"><center><input type="number" name="txtTxAvg[]" id="txtTxAvg_{{$key}}" value="{{ $vl["txAvg"] }}"></center></td>
  <td class="pn"><center><input type="number" name="txtTxMax[]" id="txtTxMax_{{$key}}" value="{{ $vl["txMax"] }}"></center></td>
</tr>

@endforeach
  <input type="hidden" name="txtDeviceID" id="txtDeviceID" value="{{ $device->deviceID }}">
  <input type="hidden" name="txtDeviceName" id="txtDeviceName" value="{{ $device->deviceName }}">
  <input type="hidden" name="txtCount" id="txtCount" value="{{ $key }}">
</tbody>
</table>
    </div>
     </div>
</div>



    {{-- </div>
	</div> --}}


<script type="text/javascript">
  $( document ).ready(function() {
    function checkTime(i) {
        return (i < 10) ? "0" + i : i;
    }

    $(".floatNumberField").change(function() {
            $(this).val(parseFloat($(this).val()).toFixed(2));
        });

    $('input[name="txtDown[]"]').on('change', function(){
    var $down = $(this);
    var downtime = $down.val().split('.');
    // alert(downtime);
    var d2 = new Date("2014-02-02 "+downtime[0]+":"+downtime[1]);
    var d1 = new Date("2014-02-03 00:00:00");

    var diff = d1.getTime() - d2.getTime();
    var msec = diff;
    var hh = Math.floor(msec / 1000 / 60 / 60);
    msec -= hh * 1000 * 60 * 60;
    var mm = Math.floor(msec / 1000 / 60);
    msec -= mm * 1000 * 60;
      
      ///สูตรที่ถูกต้อง 
    // var today = new Date("2014-02-02 "+hh+":"+mm);
    // var p = Math.round(((today - d2) / (d1 - d2)) * 100) + '%';
    var ava = (parseFloat(hh+'.'+mm))*100/24;
    // console.log(checkTime(hh));
    // console.log(diff)
    var no = $down.attr('id').split('_');

    $('#txtUp_'+no[1]).val(checkTime(hh) + "." + checkTime(mm));
    $('#txtAvailbility_'+no[1]).val(parseFloat(ava).toFixed(4));

  });
});

</script>
</body>	
</html>