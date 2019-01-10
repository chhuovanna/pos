<!DOCTYPE html>
<html>
<head>
	<title>Win money prize view detail</title>

	<style type="text/css" scoped="scoped">
		@page { size: 5.83in 8.27in; margin: 0.3in }
		p { 
			margin:0in;
		 }
		td p { margin-bottom: 0in }
		
		body {
			font-family: Calibri, serif ,"Khmer OS" , "Times New Roman"; 
			color: black; 
		}

		table, th, td{
			padding: 3px;
			border-collapse: collapse;
			border-spacing: 0px;
		}

		th{
			border: solid 1px black;
			font-weight: bold;
		}

		
		.receipt{
			font-size: 16px;
			text-align: center;
			/*font-weight: bold;*/
		}
		
		

		.table-orderline{
			font-size: 14px;
			width: 100%;
		}
		.td-num{
			width: 5%;
		}

		.td-name{
			width: 34%;
		}

		.td-qty{
			width: 6%;
		}

		.td-price{
			width: 11%;
		}
		.td-subtotal{
			width: 11%;
		}

		.table-orderline td{
			border-left:solid 1px black;
			border-right:solid 1px black;
			border-top: none;
			border-bottom: none;
			text-align: center;
			vertical-align: top;
			padding: 2px;
		}


		.table-payment {
			font-size: 14px;
			width: 100%;
		}

		.table-orderline td:nth-child(2){
			text-align: left;
		}		

		.table-orderline td:nth-child(3){
			text-align: right;
		}

		.table-orderline td:nth-child(4){
			text-align: right;
		}


		.table-orderline td:nth-child(6){
			text-align: right;
		}

		.table-orderline td:nth-child(7){
			text-align: right;
		}

		.table-orderline td:nth-child(8){
			text-align: right;
		}

		.td-labelpayment{
			text-align: right;
			padding-right: 3px;
			border-right: solid 1px black;
			width: 61;
		}
		.td-exchangerate{
			text-align: right;
			padding-right: 3px;
			border-right: solid 1px black;
			border-top: solid 1px black;
			width: 84%;

		}
		.td-currencyr{
			text-align: right;
			padding-right: 3px;
			border: solid 1px black;
			width: 16%;
		}
		.td-currencyd{
			text-align: right;
			padding-right: 3px;
			border: solid 1px black;
			width: 15%;
		}

		.td-grandtotal{
			font-weight: bold;
		}

	</style>

</head>
<body>

	
	<p class="receipt">Win Money Prize : {{ $winmoneyprize->wmpid }}</p>
	<table class="table-orderline">
		<tr>
			<th class="td-num">#</th>
			<th class="td-name">Product</th>
			<th class="td-price">Pay amount</th>
			<th class="td-price">Win amount</th>
			<th class="td-qty">Unit</th>
			<th class="td-subtotal">Pay total</th>
			<th class="td-subtotal">Win total</th>
			<th class="td-subtotal">Left total</th>
		</tr>

		@php
			$i = 0;
		@endphp
		@foreach($winmoneyprizeproducts as $winmoneyprizeproduct)
			
			@php
				$i ++;
			@endphp

			
		<tr >
			<td >
				<p>{{$i}}</p>
			</td>
			<td ><p>{{$winmoneyprizeproduct->name}}</p>
			</td>
			<td>
				<p>{{$winmoneyprizeproduct->payamount}}</p>
			</td>
			<td >
				<p>{{$winmoneyprizeproduct->winamount}}</p>
			</td>
			<td >
				<p >{{$winmoneyprizeproduct->unit}} </p>
			</td>
			<td >
				<p >{{$winmoneyprizeproduct->paysubtotal}} </p>
			</td>
			<td >
				<p >{{$winmoneyprizeproduct->winsubtotal}} </p>
			</td>
			<td >
				<p >{{$winmoneyprizeproduct->leftsubtotal}} </p>
			</td>
		</tr>

		@endforeach

	</table>
	<table class='table-payment' >
		<tr >
			<td class="td-exchangerate" colspan='2'>
				<p>Rate</p>
			</td>

			<td class="td-currencyr" >
				<p>{{ $winmoneyprize->exchangerate }}៛</p>
			</td>
		</tr>		
		<tr >
			<td class="td-labelpayment">
				<p>Pay total:</p>
			</td>
			<td class="td-currencyd">
				<p>{{ $winmoneyprize->paytotal }}$</p>
			</td>
			<td class="td-currencyr">
				<p>{{ $winmoneyprize->paytotal * $winmoneyprize->exchangerate }}៛</p>
			</td>
		</tr>

		<tr >
			<td class="td-labelpayment">
				<p>Win total:</p>
			</td>
			<td class="td-currencyd">
				<p>{{ $winmoneyprize->wintotal }}$</p>
			</td>
			<td class="td-currencyr">
				<p>{{ $winmoneyprize->wintotal * $winmoneyprize->exchangerate }}៛</p>
			</td>
		</tr>
		<tr >
			<td class="td-labelpayment td-grandtotal">
				<p>Left total:</p>
			</td>
			<td class="td-currencyd">
				<p>{{ $winmoneyprize->lefttotal }}$</p>
			</td>
			<td class="td-currencyr">
				<p>{{ $winmoneyprize->lefttotal * $winmoneyprize->exchangerate }}៛</p>
			</td>
		</tr>


	</table>
</body>
</html>