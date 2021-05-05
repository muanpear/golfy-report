@extends('layout.app')

<div class="container-fluid">

  <div class="row">
    <div class="col-md-4" style="background-color: rgb(102, 210, 30)">
      <div class="row m-3">
        <div class="col-md-12">
          <h2><center>Generate graph</center></h2>
        </div>
      </div>
    <br>

    <div class="row">
      <form action="{{ URL::to('chart') }}" method="get" target="_blank">
      @csrf
      <div class="col-md-6 offset-md-3">
        <label>Step 1 -> Select Customer <font color=red>***</font></label>
        <select class="form-control" name="customer" id="customer" required>
          <option value="">เลือกเลยจ้า</option>
        </select>
      </div>
    </div>
    <br>

    <div class="row">
    <div class="col-md-6 offset-md-3">
      <label>Step 2 -> Select circuit <font color=red>***</font></label>
      <select class="form-control" name="circuit" id="circuit" required></select>
    </div>
    </div>
    <br>

    <div class="row">
    <div class="col-md-6 offset-md-3">
      <label>Step 3 -> Select date <font color=red>***</font></label>
      <input type="text" id="daterange" name="daterange" class="form-control daterange" required>
    </div>
    </div>
    <br>

    <div class="row">
      <div class="col-md-6 offset-md-3">
        <label>Step 4 -> Show Percentile</label>
        <div class="input-group mb-3">
          <input type="number" id="percent_val" name="percent_val" class="form-control" min="0" max="100">
          <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">%</span>
          </div>
        </div>
      </div>
      </div>
      <br>

    <div class="row">
    <div class="col-md-6 offset-md-3 d-grid gap-2">
      <button type="submit" class="btn btn-outline-primary">Generate Now!!!</button>
    </div>
    </div>
    <br>

    </form>
    </div> {{--  end gen --}}

    <div class="col-md-4" style="background-color: rgb(210, 183, 30)">
      <div class="row m-3">
        <div class="col-md-12">
          <h2><center>Edit Data</center></h2>
        </div>
      </div>
      <br>

      <div class="row">
        <form action="{{ URL::to('edit-data') }}" method="get" target="_blank">
        @csrf
        <div class="col-md-6 offset-md-3">
          <label>Step 1 -> Select Customer</label>
          <select class="form-control" name="customer_edit" id="customer_edit" required>
            <option value="">เลือกเลยจ้า</option>
          </select>
        </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-6 offset-md-3">
        <label>Step 2 -> Select circuit</label>
        <select class="form-control" name="circuit_edit" id="circuit_edit" required></select>
      </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-6 offset-md-3">
        <label>Step 3 -> Select date</label>
        <input type="text" id="daterange_edit" name="daterange_edit" class="form-control daterange" required>
      </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-6 offset-md-3 d-grid gap-2">
        <button type="submit" class="btn btn-outline-primary">Edit !!!</button>
      </div>
      </div>

      </form>
    </div> {{--  end edit --}}

    <div class="col-md-4" style="background-color: rgb(210, 30, 30)">
      <div class="row m-3">
        <div class="col-md-12">
          <h2><center>Generate graph </center></h2><center><small>(for my boss)</small></center>
        </div>
      </div>
      <br>

      <div class="row">
        <form action="{{ URL::to('chart1') }}" method="get" target="_blank">
        @csrf
        <div class="col-md-6 offset-md-3">
          <label>Step 1 -> Select Customer</label>
          <select class="form-control" name="customer_boss" id="customer_boss" required>
            <option value="">เลือกเลยจ้า</option>
          </select>
        </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-6 offset-md-3">
        <label>Step 2 -> Select circuit</label>
        <select class="form-control" name="circuit_boss" id="circuit_boss" required></select>
      </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-6 offset-md-3">
        <label>Step 3 -> Select date</label>
        <input type="text" id="daterange_boss" name="daterange_boss" class="form-control daterange" required>
      </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-6 offset-md-3 d-grid gap-2">
        <button type="submit" class="btn btn-outline-primary">Generate Now !!!</button>
      </div>
      </div>

      </form>
    </div> {{--  end for jk --}}
    {{-- <img src="{{ asset('images/nyan-cat.gif') }}" width="100%" height="200px;"> --}}
  </div> {{--  end row --}}
  
</div> {{--  end container --}}

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
  $("#circuit").prop( "disabled", true );
  $("#customer").select2();
                $.ajax({
                    url:"/customer-list",
                    dataType: "json", 
                    success:function(data){
                        $.each(data, function( index, value ) {
                              $("#customer").append("<option value='"+ value.groupID +"'> " + value.customerName + "</option>");
                        });
                    }
                });
                

$("#customer").change(function(){
  var customer_id = $(this).val();
  if (customer_id == ""){
    $("#circuit").prop( "disabled", true );
  }else{
    $("#circuit").prop( "disabled", false );
  }
  $.ajax({
     url:"/get-circuit",
     dataType: "json",
     data:{customer_id:customer_id},
     success:function(data){
         $("#circuit").text("");
         $.each(data, function( index, value ) {
               $("#circuit").append("<option value='"+ value.deviceID +"'> " + value.deviceName + "</option>");
         });
     }
 });

});

$('#circuit').select2();


//////////////////
$("#circuit_edit").prop( "disabled", true );
  $("#customer_edit").select2();
                $.ajax({
                    url:"/customer-list",
                    dataType: "json", 
                    success:function(data){
                        $.each(data, function( index, value ) {
                              $("#customer_edit").append("<option value='"+ value.groupID +"'> " + value.customerName + "</option>");
                        });
                    }
                });
                

$("#customer_edit").change(function(){
  var customer_id = $(this).val();
  if (customer_id == ""){
    $("#circuit_edit").prop( "disabled", true );
  }else{
    $("#circuit_edit").prop( "disabled", false );
  }
  $.ajax({
     url:"/get-circuit",
     dataType: "json",
     data:{customer_id:customer_id},
     success:function(data){
         $("#circuit_edit").text("");
         $.each(data, function( index, value ) {
               $("#circuit_edit").append("<option value='"+ value.deviceID +"'> " + value.deviceName + "</option>");
         });
     }
 });

});

$('#circuit_edit').select2();

//////////////////
$("#circuit_boss").prop( "disabled", true );
  $("#customer_boss").select2();
                $.ajax({
                    url:"/customer-list",
                    dataType: "json", 
                    success:function(data){
                        $.each(data, function( index, value ) {
                              $("#customer_boss").append("<option value='"+ value.groupID +"'> " + value.customerName + "</option>");
                        });
                    }
                });
                

$("#customer_boss").change(function(){
  var customer_id = $(this).val();
  if (customer_id == ""){
    $("#circuit_boss").prop( "disabled", true );
  }else{
    $("#circuit_boss").prop( "disabled", false );
  }
  $.ajax({
     url:"/get-circuit",
     dataType: "json",
     data:{customer_id:customer_id},
     success:function(data){
         $("#circuit_boss").text("");
         $.each(data, function( index, value ) {
               $("#circuit_boss").append("<option value='"+ value.deviceID +"'> " + value.deviceName + "</option>");
         });
     }
 });

});

$('#circuit_boss').select2();
});
</script>
@endsection