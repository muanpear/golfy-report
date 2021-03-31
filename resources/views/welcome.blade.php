@extends('layout.app')

<div class="container-fluid">

  <div class="row">
    <div class="col-md-6">
      <div class="row m-3">
        <div class="col-md-12">
          <h2><center>Generate graph</center></h2>
        </div>
      </div>
    <br>

    <div class="row">
      <form action="{{ URL::to('chart') }}" method="get" target="_blank">
      @csrf
      <div class="col-md-4 offset-md-4">
        <label>Step 1 -> Select Customer</label>
        <select class="form-control" name="customer" id="customer" required>
          <option value="">เลือกเลยจ้า</option>
        </select>
      </div>
    </div>
    <br>

    <div class="row">
    <div class="col-md-4 offset-md-4">
      <label>Step 2 -> Select circuit</label>
      <select class="form-control" name="circuit" id="circuit" required></select>
    </div>
    </div>
    <br>

    <div class="row">
    <div class="col-md-4 offset-md-4">
      <label>Step 3 -> Select date</label>
      <input type="text" id="daterange" name="daterange" class="form-control daterange" required>
    </div>
    </div>
    <br>

    <div class="row">
    <div class="col-md-4 offset-md-4 d-grid gap-2">
      <button type="submit" class="btn btn-outline-primary">Generate Now!!!</button>
    </div>
    </div>

    </form>
    </div> {{--  end gen --}}

    <div class="col-md-6">
      <div class="row m-3">
        <div class="col-md-12">
          <h2><center>Edit Data</center></h2>
        </div>
      </div>
      <br>

      <div class="row">
        <form action="{{ URL::to('edit-data') }}" method="get" target="_blank">
        @csrf
        <div class="col-md-4 offset-md-4">
          <label>Step 1 -> Select Customer</label>
          <select class="form-control" name="customer_edit" id="customer_edit" required>
            <option value="">เลือกเลยจ้า</option>
          </select>
        </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-4 offset-md-4">
        <label>Step 2 -> Select circuit</label>
        <select class="form-control" name="circuit_edit" id="circuit_edit" required></select>
      </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-4 offset-md-4">
        <label>Step 3 -> Select date</label>
        <input type="text" id="daterange_edit" name="daterange_edit" class="form-control daterange" required>
      </div>
      </div>
      <br>

      <div class="row">
      <div class="col-md-4 offset-md-4 d-grid gap-2">
        <button type="submit" class="btn btn-outline-primary">Edit !!!</button>
      </div>
      </div>

      </form>
    </div> {{--  end edit --}}
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
});
</script>
@endsection