@extends('admin.master')

@section('htmlheader')
@endsection

@section('body')
<div class="list page">
	<?php if (isset($flashMessages)): ?>
		@include('admin.snippets.flash-messages', ['flashMessages' => $flashMessages])
	<?php endif; ?>
	<ul class="breadcrumb">
		<li><i class="fa fa-list-alt" aria-hidden="true"></i> Order List</li>
	</ul>
	
	<table class="table table-striped table-bordered table-hover col-sm-8">
		<thead>
			<tr>
				<th>ID</th>
				<th>Customer</th>
				<th>Address</th>
				<th>Total</th>
				<th>Status</th>
				<th>Date of Order</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($orders as $order)
    			<tr class="{{ strtolower($order->status) }}">
    				<td>{{ $order->id }} </td>
    				<td>{{ $order->customer_name }} </td>
    				<td>{{ $order->address }} </td>
    				<td>{{ $order->total }} </td>
    				<td>{{ $order->status }} </td>
    				<td>{{ $order->order_date }} </td>
    				<td class="text-center"><a class="btn btn-info" href="{{ url('order/view') }}/{{ $order->id }}">View</a></td>
    			</tr>
    		@endforeach
		</tbody>
	</table>
	
	<div class="pagination-links col-sm-12 text-center">
		{{ $orders->links() }}
	</div>
</div>
@endsection

@section('footer')
@endsection
