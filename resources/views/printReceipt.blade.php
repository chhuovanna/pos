@extends('layouts.saleReceipt')

@section('htmlhead')
<!DOCTYPE html>
<html>
<head>
	<title>Print Receipt</title>
</head>
<body>
@endsection

@section('script')
<script>  
		window.print(); 
		window.close(); 
</script>
@endsection


@section('htmlend')
</body>
</html>
@endsection
