

<style type="text/css" scoped="">
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
         margin: 0;
    }

    input[type=number] {
        -moz-appearance:textfield;
    }

table {
    border-collapse: collapse;
    width: 80%;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}




</style>
<div class="box">
 
    

    <form method="post" action="{{ url('/admin/category/setunitname/save') }}" accept-charset="UTF-8" class="form-horizontal" id="saveform">

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="box-body table-responsive no-padding">

        <table   id="setunitnametable">
            <!-- <thead> -->
                <tr>
                    <th>Category</th>
                    <th>Name for Pack</th>
                    <th>Name for box</th>
                </tr>
           <!--  </thead> -->
            <tbody id="setunitnametablebody">



        @php
            $i = 0;
        @endphp
        @foreach($categories as $category)

            <tr>
                <td>{{$category->catid}} {{$category->name}}</td>
                <td><input id='{{$category->catid}}_packname' name='{{$category->catid}}_packname' class='packname' value='' style='width: 200px; background-color:#def9fc' value='{{$category->packname}}' ></td>
                <td><input id='{{$category->catid}}_boxname' name='{{$category->catid}}_boxname' class='boxname' value='' style='width: 200px; background-color:#def9fc' value='{{$category->boxname}}' ></td>
            </tr>
        @endforeach
            
            </tbody>
        </table>
         <div class="btn-group pull-right" style="padding-top: 50px; padding-right:  400px; padding-bottom: 50px">

            <input type="button" class="btn btn-info  savesubmit" id='savesubmit' data-loading-text="<i class='fa fa-spinner fa-spin'></i> Submit" value="Save">
        </div>
</div>
    </form>
    
   
</div>
<script type="text/javascript">

    $('#savesubmit').off('click');
    $('#savesubmit').on('click', function (){
        $('#saveform').submit();
    })

</script>

