@extends('admin.master')

@section('htmlheader')
	<script src="//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
@endsection

@section('body')
<?php 
    $saleReport = $product->getSaleReport();
?>
<div class="view page">
	<?php if (isset($flashMessages)): ?>
		@include('admin.snippets.flash-messages', ['flashMessages' => $flashMessages])
	<?php endif; ?>
	
	<ul class="breadcrumb">
		<li><i class="fa fa-list-alt" aria-hidden="true"></i> <?= link_to_route('web-product-list', 'Product List') ?></li>
		<li class="active">Product [ {{ $product->id }} ]</li>
	</ul>
	<div class="col-sm-4">
    	<div class="panel panel-default item-info">
    		<div class="panel-heading">
    			Product Basic Information
    		</div>
<form id="updateProductForm" class="form" method="POST" data-toggle="validator">
	{{ csrf_field() }}

    		<div class="panel-body">
        		<table class="table table-striped table-bordered">
        			<tbody>
        				<tr>
        					<td><strong>ID</strong></td>
        					<td>
        						{{ $product->id }}
        						<input type="hidden" name="id" value="{{ $product->id }}"  />
        					</td>
        				</tr>
        				<tr>
        					<td><strong>Product SKU</strong></td>
        					<td>{{ $product->sku }}</td>
        				</tr>
        				<tr>
        					<td><strong>Label/ Name</strong></td>
        					<td>
        						<input pattern="[a-zA-Z\s]+" class="form-control" type="text" name="label" value="{{ $product->label }}"  data-error="Invalid Input" />
        						<div class="help-block with-errors"></div>
        					</td>
        				</tr>
        				<tr>
        					<td><strong>Description</strong></td>
        					<td>
        						<textarea pattern="[a-zA-Z\s\+\.\'\n]+" class="form-control" name="description" data-error="Invalid Input">{{ $product->description }}</textarea>
        						<div class="help-block with-errors"></div>
        					</td>
        				</tr>
        				<tr>
        					<td><strong>Price</strong></td>
        					<td>
        						<input class="form-control" type="number" name="price" value="{{ number_format($product->price, 2) }}"  />
        						<div class="help-block with-errors"></div>
        					</td>
        				</tr>
        				<tr>
        					<td><strong>Product Updated At</strong></td>
        					<td>{{ $product->updated_at }}</td>
        				</tr>
        				<tr>
        					<td><strong>Product Created At</strong></td>
        					<td>{{ $product->created_at }}</td>
        				</tr>
        			</tbody>
        		</table>
        		
        		<table class="table table-striped">
        			<tbody>
        				<tr>
        					<td><strong>Available In Stock</strong></td>
        					<td>{{ ($saleReport['Available'])?? 0 }}</td>
        				</tr>
        				<tr>
        					<td><strong>No. of Ordered</strong></td>
        					<td>{{ ($saleReport['Assigned'])?? 0 }}</td>
        				</tr>
        			</tbody>
        		</table>
        		
        		<div class="form-group text-right">
        			<input type="submit" class="btn btn-info" value="Update" />
        		</div>
        	</div>
</form>
<form id="addNewItem" method="post" action="{{ url('product/addItem') }}" data-toggle="validator">
	{{ csrf_field() }}
        	<div class="panel-footer">
        		<h4>Create new Item for this Product</h4>
        		<table class="table">
        			<tbody>
        				<tr>
        					<td><strong style="display: inline-block; line-height: 35px; margin-right: 15px;">Quantity </strong> x </td>
        					<td class="col-sm-5">
        						<input required class="form-control" type="number" name="quantity" value="" />
        						<div class="help-block with-errors"></div>
        					</td>
        					<td>
        						<input class="form-control" type="hidden" name="id" value="{{ $product->id }}" />
        						<input type="submit" class="btn btn-success" value="Add" />
        					</td>
        				</tr>
        			</tbody>
        		</table>
        	</div>
</form>
    	</div>
    </div>
    <div class="col-sm-8">
    	<div class="panel panel-default">
    		<div class="panel-heading">
    			Product Properties
    		</div>
    		<div class="panel-body">
    			<ui id="properties-list">
    				@foreach($product->properties()->orderBy('name')->get() as $property)
    					<li>
    						@if ($property->type === 'feature')
    							<i class="fa fa-puzzle-piece" aria-hidden="true"></i>
    						@else
    							<i class="fa fa-check-square-o" aria-hidden="true"></i>
    						@endif
    						<label>{{ $property->name }}</label>
    						<span class="property value">{{ $property->value }}</span>
    						<span class="property type">{{ $property->type }}</span>
    						<button class="delete-property btn btn-danger" data-propertyid="{{ $property->id }}">DELETE</button>
    					</li>
    				@endforeach
    			</ui>
    		</div>
        		<div class="panel-footer">
        			<form id="new_property_form" class="form" data-toggle="validator">
        			<table class="table">
        				<thead>
        					<tr>
        						<td>New Property Name</td>
        						<td>Value</td>
        						<td>Type</td>
        						<td></td>
        					</tr>
        				</thead>
        				<tbody>
            				<tr>
            					<td class="form-group">
            						<input required class="form-control" type="text" name="new_property_name" value=""  />
            						<div class="help-block with-errors"></div>
            					</td>
            					<td class="form-group">
            						<input required class="form-control" type="text" name="new_property_value" value=""  />
            						<div class="help-block with-errors"></div>
            					</td>
            					<td class="form-group">
            						<select required class="form-control" name="new_property_type"  >
            							<option value="feature">Feature</option>
            							<option value="option">Option</option>
            						</select>
            						<div class="help-block with-errors"></div>
            					</td>
            					<td class="text-right">
            						<button id="addNewProperty" type="submit" class="btn btn-success" data-productid="{{ $product->id }}" >Add</button>
            					</td>
            				</tr>
            			</tbody>
        			</table>
        			</form>
        			
            		<div class="alert alert-warning">
            			* Assumption [8] The current data structure is assuming one SKU per variant set/ combination. But...
            		</div>
        		</div>
        	
    	</div>
    </div>
</div>
@endsection

@section('footer')
<script>
jQuery(document).ready(function(){
	$('#new_property_form').validator();
	$('#updateProductForm').validator();
	$('#addNewItem').validator();
	
	$(document).on('click', '.delete-property', function() {
		var propertyId = $(this).data('propertyid');
		var currentProperty = $(this).closest('li');
		$.ajax({
			url: "{{ url('/api/product/property/delete') }}/"+propertyId,
			type: "GET",
			success: function(response){
				if (response.success){
					currentProperty.fadeOut(200, function(){
						$(this).remove();
					});
				} else {
					alert(response.error);
				}
			},
			error: function(e) {
				alert(e.error);
			}
		});
	});
	
	$('#addNewProperty').on('click', function(e) {
		e.preventDefault();
		$('#new_property_form').validator();
// 		if (!$('#new_property_form').validator().hasErrors()) {
    		var propertyName = $('input[name=new_property_name]').val();
    		var propertyValue = $('input[name=new_property_value]').val();
    		var propertyType = $('select[name=new_property_type] option:selected').val();
    		var productId = $(this).data('productid');
    
    		$.ajax({
    			url: "{{ url('/api/product/property/add') }}",
    			type: "POST",
    			dataType: "json",
    			data: {
    				product_id: productId,
    				name: propertyName,
    				value: propertyValue,
    				type: propertyType,
    				updated_by: 'user'
    			},
    			success: function(response){
    				if (response.success){
    					var property = response.data;
    					insertPropertyToTable(property);
    				} else {
    					alert(response.error.type);
    				}
    			},
    			error: function(e){
    				alert('Something goes wrong... please try again or contact our system admin.');
    			}
    		});
// 		}
	});
});

function insertPropertyToTable(data)
{	
	var propertyIcon = '<i class="fa fa-check-square-o" aria-hidden="true"></i> ';
	if (data.type.match('feature')){
		propertyIcon = '<i class="fa fa-puzzle-piece" aria-hidden="true"></i> ';
	}
	var htmlRow = '<li>'+
					propertyIcon+
                  	'<label>'+data.name+'</label>'+
                    '<span class="property value">'+data.value+'</span>'+
                    '<span class="property type">'+data.type+'</span>'+
                    '<button class="delete-property btn btn-danger" data-propertyid="'+data.id+'">DELETE</button>'+
                  '</li>';
	$('#properties-list').append(htmlRow);
}
</script>
@endsection
