
@extends('layouts.winMoneyPrize')



@section('customers')
    @foreach($customers as $customer)
        <option value="{{  $customer->cusid }}">{{ $customer->name }}</option>
        @endforeach

@endsection


<script type="text/javascript">

</script>

