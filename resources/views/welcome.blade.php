@extends('layout.app')
<div class="container">

<div class="page-header">
  <h2><center>Generate graph</center></h2>
</div>
<br>

<form action="{{ URL::to('chart') }}" method="post">
@csrf

<div class="row">
  <div class="col-md-4 offset-md-4">
      <label>Step 1 -> Select Customer</label>
      {{-- <select name="customer_id" id="customer_id" class="form-control customer_id" required>
        <option value="">Please select</option>
      </select> --}}
      <input type="text" id="cid" name="cid" class="form-control">
  </div>
  </div>
  <br>

<div class="row">
<div class="col-md-4 offset-md-4">
    <label>Step 1 -> Select circuit</label>
    {{-- <select name="customer_id" id="customer_id" class="form-control customer_id" required>
    	<option value="">Please select</option>
    </select> --}}
    <input type="text" id="cid" name="cid" class="form-control">
</div>
</div>
<br>

<div class="row">
<div class="col-md-4 offset-md-4">
  <label>Step 2 -> Select date</label>
  <input type="text" id="daterange" name="daterange" class="form-control">
    {{-- <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <i class="fa fa-caret-down"></i> --}}
</div>
</div>
<br>

<div class="row">
<div class="col-md-4 offset-md-4 d-grid gap-2">
  <button type="submit" class="btn btn-outline-primary">Generate Now!!!</button>
</div>
</div>



</form>



</div>



</body>
</html>