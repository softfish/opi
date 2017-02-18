@extends('admin.master')

@section('htmlheader')
@endsection

@section('body')
<div class="list page">
	<?php if (isset($flashMessages)): ?>
		@include('admin.snippets.flash-messages', ['flashMessages' => $flashMessages])
	<?php endif; ?>
	<ul class="breadcrumb">
		<li><i class="fa fa-list-alt" aria-hidden="true"></i> Products List</li>
	</ul>
	<div class="alert alert-info col-sm-12">
		<div class="col-sm-8">
			You can start creating a product here. By clicking the Add button on the right. All you need is to have a SKU first.<br />
			If you want to edit more information, please use the view button below and update feature will be in the product page.
		</div>
		<div class="col-sm-4">
    		<form id="AddNewProductForm" action="{{ url('product/new') }}" method="post" data-toggle="validator">
    			{{ csrf_field() }}
    			<div class="form-group btn-group">
        			<input required class="btn" type="text" name="sku" placeholder="SKU here" value="" />
        			<button type="submit" class="btn btn-success">New Product</button>
        		</div>
    		</form>
		</div>
	</div>
				
	<table class="table table-striped table-bordered table-hover col-sm-8">
		<thead>
			<tr>
				<th>ID</th>
				<th>SKU</th>
				<th>Name/Label</th>
				<th>Price</th>
				<th>In Stock</th>
				<th>Ordered</th>
				<th>
				</th>
			</tr>
		</thead>
		<tbody>
			@foreach($products as $product)
				<?php 
				    $saleReport = \App\Models\Product::find($product->id)->getSaleReport();
				?>
				<tr>
					<td>{{ $product->id }}</td>
					<td>{{ $product->sku }}</td>
					<td>{{ $product->label }}</td>
					<td>{{ number_format($product->price, 2) }}</td>
					<td>{{ ($saleReport['Available'])?? 0 }}</td>
					<td>{{ ($saleReport['Assigned'])?? 0 }}</td>
					<td class="text-center">
						<a class="btn btn-info" href="{{ url('/product/view') }}/{{ $product->id }}">View</a>
					</td>
				</tr>
    		@endforeach
		</tbody>
	</table>
	
	<div class="pagination-links col-sm-12 text-center">
		{{ $products->links() }}
	</div>
</div>
@endsection

@section('footer')
@endsection
