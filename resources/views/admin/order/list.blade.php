@extends('admin.master')

@section('htmlheader')
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script>
	Vue.config.delimiters = ['<[', ']>']
	var orders;
	var orderList = new Vue({
		el: "#order-list",
		data: orders,
		ready: function(){
			this.getOrderList();
		},
		methods: {
			getOrderList: function(){
				$.ajax({
					url: "{{ url('/api/order/list') }}",
					type: 'GET',
					success: function(response) {
						this.$set('orders', response.orders);
					},
					error: function(e){
					} 
				});
			}
		}
	});
</script>
@endsection

@section('body')
<div class="list page col-sm-12	">
	<table id="order-list" class="table table-striped table-bordered table-hover col-sm-8">
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
			<tr v-for="order in orders" v-bind:class="<[ line-through-danger: cancelled ]>">
				<td><[ order.id ]> </td>
				<td><[ order.customer_name ]> </td>
				<td><[ order.address ]> </td>
				<td><[ order.total ]> </td>
				<td><[ order.status ]> </td>
				<td><[ order.order_date ]> </td>
				<td><a href="{{ url() }}/<[ order.id ]>">View</a></td>
			</tr>
		</tbody>
	</table>
</div>
@endsection

@section('footer')
@endsection
