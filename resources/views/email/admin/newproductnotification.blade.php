@extends('email.admin.master')

@section('body')
A new product has been created while we are processing a new order online. <br />
<br />
<table>
	<tbody>
		<tr>
			<td>Product ID:</td>
			<td>{{ $product->id }}</td>
		</tr>
		<tr>
			<td>Product SKU:</td>
			<td>{{ $product->sku }}</td>
		</tr>
	</tbody>
</table>
@endsection