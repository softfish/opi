@extends('layouts.master')

@section('body')
<div class="home container">
	<a class="col-sm-4" href="{{ url('/order/list') }}">
    	<div class="panel panel-info">
    		<div class="panel-heading">
    			Order List
    		</div>
    		<div class="panel-body">
    		</div>
    		<div class="panel-footer">
    		</div>
    	</div>
    </a>
	<a class="col-sm-4" href="{{ url('/product/list') }}">
    	<div class="panel panel-info">
    		<div class="panel-heading">
    			Product List
    		</div>
    		<div class="panel-body">
    		</div>
    		<div class="panel-footer">
    		</div>
    	</div>
    </a>
    <a class="col-sm-4" href="{{ url('/item/list') }}">
    	<div class="panel panel-info">
    		<div class="panel-heading">
    			Item List
    		</div>
    		<div class="panel-body">
    		</div>
    		<div class="panel-footer">
    		</div>
    	</div>
    </a>
</div>
@endsection
