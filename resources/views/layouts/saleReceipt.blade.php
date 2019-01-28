@section('htmlhead')
	@show


	<style type="text/css" scoped="scoped">
		@page { size: 5.83in 8.27in; margin: 0.3in }
		p { 
			margin:0in;
		 }
		td p { margin-bottom: 0in }
		
		body {
			font-family: "Khmer OS" , serif ,"Times New Roman"; 
			color: black; 
		}

		table, th, td{
			padding: 0px;
			border-collapse: collapse;
			border-spacing: 0px;
		}

		th{
			border: solid 1px black;
			font-weight: normal;
		}

		.shop-name { 
			font-size: 14px;
			text-align: center;
			/*font-weight: bold;*/
		}
		.shop-des { 
			font-size: 12px;
			text-align: center;
			/*font-weight: bold;*/
		}
		.receipt{
			font-size: 12px;
			text-align: center;
			/*font-weight: bold;*/
		}
		.table-contact{
			font-size: 11px;
			text-align: center;
			width: 100%;

		}
		.td-contact{

			colspan:4;
			
		}
		.td-4{
			width: 25%;
		}
		.td-align-left{
			text-align: left;
		}
		.td-align-right{
			text-align: right;
		}

		.table-orderline{
			font-size: 11px;
			width: 100%;
		}
		.td-num{
			width: 5%;
		}

		.td-name{
			width: 45%;
		}

		.td-qty{
			width: 14%;
		}

		.td-price{
			width: 21%;
		}
		.td-subtotal{
			width: 15%;
		}

		.table-orderline td{
			border-left:solid 1px black;
			border-right:solid 1px black;
			border-top: none;
			border-bottom: none;
			text-align: center;
			vertical-align: top;
			padding: 1px;
		}


		.table-payment {
			font-size: 11px;
			width: 100%;
		}

		.table-orderline td:nth-child(2){
			text-align: left;
			padding-left: 3px;
		}		

		.table-orderline td:nth-child(4){
			text-align: right;
			padding-right: 3px;
		}


		.table-orderline td:nth-child(5){
			text-align: right;
			padding-right:3px;
		}

		.td-labelpayment{
			text-align: right;
			padding-right: 3px;
			border-right: solid 1px black;
			width: 69;
		}


		.td-labeltotal{
			text-align: right;
			padding-right: 3px;
			border-right: solid 1px black;
			border-top: solid 1px black;
			width: 70%;

		}
		.td-currencyr{
			text-align: right;
			padding-right: 3px;
			border: solid 1px black;
			width: 15%;
		}
		.td-currencyd{
			text-align: right;
			padding-right: 3px;
			border: solid 1px black;
			width: 15%;
		}

		.table-sign{
			font-size: 12px;
			width: 100%;
			position: static;
			bottom: 0px;
			margin-bottom: 10px
		}
		.td-buyer{
			text-align: left;
			padding-left: 50px;
		}
		.td-seller{
			text-align: right;
			padding-right: 50px;
		}

		.td-grandtotal{
			font-weight: bold;
		}

	</style>

	<p class='shop-name'>ម៉េង ហេង</p>
	<p class='shop-des'>លក់​ដំុ និង​រាយភេសជ្ជៈគ្រប់ប្រភេទ</p>
	<table class='table-contact'>
		<tr>
			<td colspan="4">
				<p>
					ផ្ទះលេខ 11A​​ ផ្លូវលេខ 369 សង្កាត់ព្រែកប្រា ខ័ណ្ឌ​ច្បារអំពៅ Tel: 095 555 687 / 015 626 568
				</p>
			</td>
		</tr>
		<!-- <tr>
			<td colspan='4'>
				<p>
					
				</p>
			</td>
		</tr> -->

		<tr>
			<td class="td-align-left">
				<p>លេខៈ {{ $sale->saleid }}</p>
			</td>
			<td >
				<p>អ្នកលក់ៈ {{ $user }}</p>
			</td>

			<td class="td-align-right">
				<p>អ្នកទិញៈ {{ $sale->name }}</p>
			</td>
			
		</tr>
		<tr>
			<td class="td-align-left">
				<p>អត្រាៈ {{ $sale->exchangerate }}៛</p>
			</td>
			<td >
				<p></p>
			</td>
			<td class="td-align-right">
				<p>ពេលៈ {{ $sale->created_at }}</p>
			</td>
		</tr>
	</table>
	<p class="receipt">វិក័យប័ត្រ</p>
	<table class="table-orderline">
		<tr>
			<th class="td-num">ល.រ</th>
			<th class="td-name">រាយឈ្មេាះទំនិញ</th>
			<th class="td-qty">ចំនួន</th>
			<th class="td-price">តំលៃ $</th>
			<th class="td-subtotal">តំលៃសរុប $</th>
		</tr>

		@php
			$i = 0;
		@endphp
		@foreach($orderlines as $orderline)
			
			@php
				$quantity = "";
				$price = "";
				$i ++;

				if ($orderline->unitquantity != 0){
					$quantity .= $orderline->unitquantity . " ";
					$price .= $orderline->salepriceunit . " ";
				}

				if ($orderline->packquantity != 0){
					$quantity .= $orderline->packquantity . "x" . $orderline->unitperpack . " ";
					$price .= $orderline->salepricepack . " ";
				}


				if ($orderline->boxquantity != 0){
					if (stristr($orderline->category,'cigaret')){
						$quantity .= $orderline->boxquantity . "សុង" ;
					}else{
						$quantity .= $orderline->boxquantity . "កេស" ;
					}
					//$quantity .= $orderline->boxquantity . "x" . $orderline->unitperbox . " ";
					$price .= $orderline->salepricebox . " ";
				}

				

			@endphp

			
		<tr >
			<td >
				<p>{{$i}}</p>
			</td>
			<td ><p>{{$orderline->name}}</p>
			</td>
			<td>
				<p>{{$quantity}}</p>
			</td>
			<td >
				<p>{{$price}}</p>
			</td>
			<td >
				<p >{{$orderline->subtotal}} </p>
			</td>
		</tr>

		@endforeach

	</table>
	<table class='table-payment' >
				
		<tr >
			<td class="td-labeltotal">
				<p>សរុប:</p>
			</td>
			<td class="td-currencyr">
				<p>{{ intval($sale->totalr) }}៛</p>
			</td>
			<td class="td-currencyd">
				<p>{{ $sale->total }}$</p>
			</td>
			
		</tr>

		</tr>
		@if ($sale->discountd>0)
		<tr >
			<td class="td-labelpayment">
				<p>បញ្ចុះតំលៃៈ</p>
			</td>
			
			<td class="td-currencyr">
				<p>{{ intval($sale->discountr) }}៛</p>
			</td>
			<td class="td-currencyd">
				<p> {{ $sale->discountd }}$ </p>
			</td>
		</tr>
		@endif

		<tr >
			<td class="td-labelpayment td-grandtotal">
				<p>សរុបចុងក្រោយៈ</p>
			</td>
			
			<td class="td-currencyr">
				<p>{{ intval($sale->ftotalr)}}៛</p>
			</td>
			<td class="td-currencyd">
				<p> {{ $sale->ftotal }}$</p>
			</td>
		</tr>

		<tr >
			<td class="td-labelpayment">
				<p>បង់រួចៈ</p>
			</td>
			<td class="td-currencyr">
				<p> {{ $sale->recievedr }}៛</p>
			</td>
			<td class="td-currencyd">
				<p> {{ $sale->recievedd }}$ </p>
			</td>
			
		</tr>		

		@if($sale->loand>0)
		<tr >
			<td class="td-labelpayment">
				<p>នៅសល់ៈ</p>
			</td>
			<td class="td-currencyr">
				<p>{{ intval($sale->loanr) }}៛</p>
			</td>
			<td class="td-currencyd">
				<p>{{ $sale->loand }}$</p>
			</td>
			
		</tr>		
		@endif

		@if($sale->changed > 0)
		<tr >
			<td class="td-labelpayment">
				<p>អាប់ៈ</p>
			</td>
			<td class="td-currencyr">
				<p> {{ intval($sale->changer) }}៛ </p>
			</td>
			<td class="td-currencyd">
				<p> {{ $sale->changed }}$ </p>
			</td>
			
		</tr>
		@endif

		@if($sale->changertotal>0)
		<tr >
			<td class="td-labelpayment">
				<p>អាប់ៈ</p>
			</td>
			<td class="td-currencyr">
				<p> {{ intval($sale->changertotal) }}៛ </p>
			</td>
			<td class="td-currencyd">
				<p> </p>
			</td>
			
		</tr>	
		@endif	

	</table>

	<table class='table-sign'>
		<tr>
			<td class="td-buyer">
				<p>អ្នកលក់</p>
			</td>
			<td class="td-seller">
				<p>អ្នកទិញ</p>
			</td>
		</tr>
	</table>

@section('script')
        
    @show

@section('htmlend')
	@show