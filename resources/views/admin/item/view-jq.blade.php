@extends('admin.master')

@section('htmlheader')
@endsection

@section('body')
<div class="view page">
	<?php if (isset($flashMessages)): ?>
		@include('admin.snippets.flash-messages', ['flashMessages' => $flashMessages])
	<?php endif; ?>
	
	<ul class="breadcrumb">
		<li><i class="fa fa-list-alt" aria-hidden="true"></i> <?= link_to_route('web-item-list', 'Item List') ?></li>
		<li class="active">Item [ {{ $item->id }} ]</li>
	</ul>
	
	<div class="col-sm-4">
    	<div class="panel panel-default item-info">
    		<div class="panel-heading">
    			Item Information
    		</div>
    		<div class="panel-body">
        		<table class="table table-striped table-bordered">
        			<tbody>
        				<tr>
        					<td><strong>ID</strong></td>
        					<td>{{ $item->item_id }}</td>
        				</tr>
        				<tr>
        					<td><strong>Product SKU</strong></td>
        					<td><a href="{{ url('/product/view') }}/{{ $item->product_id }}">{{ $item->sku }} <i class="fa fa-external-link" aria-hidden="true"></i></a></td>
        				</tr>
        				<tr>
        					<td><strong>Order</strong></td>
        					<td>
            					@if (empty($item->order_id))
            						Item hasn't assigned to any order.
            					@else
            						<a href="{{ url('/order/view') }}/{{ $item->order_id }}">{{ $item->order_id }} <i class="fa fa-external-link" aria-hidden="true"></i></a>
            					@endif
            				</td>
        				</tr>
        				<tr>
        					<td><strong>Status</strong></td>
        					<td>{{ $item->status }}</td>
        				</tr>
        				<tr>
        					<td><strong>Physical Status</strong></td>
        					<td>
        						<select id="item_status_selection" class="form-control" data-itemid="{{ $item->item_id }}" {{ (empty($item->order_id))? 'disabled' : '' }}>
        							<option value="" disabled>Select a Physical Status</option>
        							@foreach($availablePhyscialStatus as $pStatus)
        								<option value="{{ $pStatus->id }}" {{ ($pStatus->id === $item->physical_status_id)? 'selected' : '' }}>{{ $pStatus->name }}</option>
        							@endforeach()
        						</select>
        					</td>
        				</tr>
        				<tr>
        					<td><strong>Last Updated At</strong></td>
        					<td>{{ $item->item_updated_at }}</td>
        				</tr>
        				<tr>
        					<td><strong>Item Created At</strong></td>
        					<td>{{ $item->item_created_at }}</td>
        				</tr>
        			</tbody>
        		</table>
        	</div>
    	</div>
    </div>
    <div class="col-sm-8">
    	<div class="alert alert-info">
    		<ul>
    			<li>You can update the item physical status here. Just to beaware that once you change the selection a request will be send to the API and update the physical status for this item right away.</li>
    			<li>When the request is send back to the API here is the list of things will happen.<br />
    				<ol>
    					<li>Item will change the physical status and system report back with error in the alert.</li>
    					<li>System will check after this change does it need to update the Order' status.</li>
    				</ol>
    			</li>
    		</ul>
    	</div>
    </div>
</div>
@endsection

@section('footer')
<script>
jQuery(document).ready(function(){
	$('#item_status_selection').on('change', function(){
		var status_id = $('#item_status_selection option:selected').val();
		var item_id = $(this).data('itemid');
		$.ajax({
			url: "{{ url('/api/item/update') }}",
			type: "POST",
			dataType: "json",
			data: {
				id: item_id,
				physical_status_id: status_id,
				updated_by: 'user'
			},
			success: function(response){
				if (!response.success) {
					alert(response.error);
				} else {
					alert(response.message);
				}
			},
			error: function(e){
				alert("Something goes wrong while we are updating the status. Please refresh the page and try again or contract system admin.");
			}
		});
	});
});
</script>
@endsection
