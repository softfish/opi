@extends('admin.master')

@section('htmlheader')
@endsection

@section('body')
<div class="view page col-sm-12">
	<ul class="breadcrumb">
		<li><i class="fa fa-tachometer" aria-hidden="true"></i> <?= link_to_route('/order/list', 'Order List') ?></li>
		<li class="active">Order View</li>
	</ul>
	
	<div class="col-sm-6">
    	<div class="panel panel-default">
    		<div class="panel-heading">
    			Order Information
    		</div>
    		<div class="panel-body">
    			<table class="table table-striped">
    				<tbody>
        				<tr>
        					<td><strong>Order ID</strong></td>
        					<td>{{ $order->id }}</td>
        					<td><strong>Order Date</strong></td>
        					<td>{{ $order->order_date }}</td>
        				</tr>
        				<tr>
        					<td><strong>Customer Name</strong></td>
        					<td>{{ $order->customer_name }}</td>
        					<td><strong>Status</strong></td>
        					<td>{{ $order->status }}</td>
        				</tr>
        				<tr>
        					<td><strong>Customer Address</strong></td>
        					<td colspan="3">{{ $order->address }}</td>
        				</tr>
        				<tr>
        					<td><h2>Total: ${{ number_format($order->total, 2) }}</h2></td>
        					<td></td>
        					<td></td>
        					<td></td>
        				</tr>
        			</tbody>
    			</table>
    			<div class="alert alert-info">
    				* Assumption [doc reference no.]
    			</div>
            </div>
    	</div>
    </div>
	<div class="col-sm-6">
    	<div class="panel panel-default">
    		<div class="panel-heading">
    			Order Items List
    		</div>
    		<div class="panel-body">
    			<table class="table table-striped table-bordered">
            		<thead>
            			<tr>
            				<th>Item ID</th>
            				<th>Product SKU</th>
            				<!-- <th class="text-center">Status</th> -->
            				<th></th>
            			</tr>
            		</thead>
            		<tbody>
            			@foreach($orderItems as $orderItem)
            				<tr class="item-summary">
                				<td>{{ $orderItem->item_id }}</td>
                				<td>{{ $orderItem->sku }}</td>
                				<!-- <td class="text-center">{{ $orderItem->item_physical_status }}</td> -->
                				<td class="text-center">
                					<div class="btn-group">
                    					<button class="view-item btn btn-info" href="{{ url('item/view') }}/{{ $orderItem->item_id }}""><i class="fa fa-search" aria-hidden="true"></i> View</button>
                    					<button class="delete-item btn btn-danger" data-itemid="{{ $orderItem->item_id }}">Delete <i class="fa fa-times" aria-hidden="true"></i></button>
                    				</div>
                				</td>
                			</tr>
                			<tr class="item-progress-bar" data-itemid="{{ $orderItem->item_id }}">
                				<td colspan="4">
                					@include('admin.item.snippets.item_physical_status_bar', ['itemPhysicalStatus' => $orderItem->item_physical_status]);
                				</td>
                			</tr>
                		@endforeach
            		</tbody>
            	</table>
    		</div>
    	</div>
    </div>
	
</div>
@endsection

@section('footer')
<script>
jQuery(document).ready(function(){
	
});
</script>
@endsection
