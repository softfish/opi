@extends('admin.master')

@section('htmlheader')
@endsection

@section('body')
<div class="list page">
	<?php if (isset($flashMessages)): ?>
		@include('admin.snippets.flash-messages', ['flashMessages' => $flashMessages])
	<?php elseif (\Session::has('flashMessages')) : ?>
		<?php 
		  $flashMessages = \Session::get('flashMessages');
		 ?>
		@include('admin.snippets.flash-messages', ['flashMessages' => $flashMessages])
	<?php endif; ?>
	<ul class="breadcrumb">
		<li><i class="fa fa-list-alt" aria-hidden="true"></i> Item List</li>
	</ul>
	<table class="table table-striped table-bordered table-hover col-sm-8">
		<thead>
			<tr>
				<th>ID</th>
				<th>SKU</th>
				<th class="text-center">Order</th>
				<th>Status</th>
				<th>Physical Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($items as $item)
				<tr class="{{ $item->status }} {{ $item->item_physical_status }}">
    				<td>{{ $item->item_id }} </td>
    				<td><a href="{{ url('/product/view') }}/{{ $item->product_id }}">{{ $item->sku }} <i class="fa fa-external-link" aria-hidden="true"></i></a></td>
    				@if (empty($item->order_id))
    					<td></td>
    				@else
    					<td class="text-center"><a href="{{ url('/order/view') }}/{{ $item->order_id }}">{{ $item->order_id }} <i class="fa fa-external-link" aria-hidden="true"></i></a></td>
    				@endif
    				<td>{{ $item->status }} </td>
    				<td>{{ $item->item_physical_status }} </td>
    				<td class="text-center"><a class="btn btn-info" href="{{ url('item/view') }}/{{ $item->item_id }}">View</a></td>
    			</tr>
    		@endforeach
		</tbody>
	</table>
	
	<div class="pagination-links col-sm-12 text-center">
		{{ $items->links() }}
	</div>
</div>
@endsection

@section('footer')
@endsection
