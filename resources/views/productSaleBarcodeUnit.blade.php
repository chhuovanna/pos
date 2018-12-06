@extends('layouts.productSale')


@section('header', 'Scan Barcode 1 Unit by Default')


@section('search')
    <div class="box-body">
        <div class="form-inline pull-right">
       
            <fieldset>

                <div class="input-group input-group-sm">
                    <span class="input-group-addon">
                        <strong>Barcode</strong>
                    </span>
                    <input type="text" style="width: 140px" class="form-control" placeholder="Barcode" id="barcode" value="" >
                </div>

                <div class="btn-group btn-group-sm">
                    <a href="javascript:void(0);" class="btn btn-warning" id="clear"><i class="fa fa-eraser"></i></a>
                </div>

             </fieldset>
        </div>
    </div> 

@endsection


@section('customers')
    @foreach($customers as $customer)
        <option value="{{  $customer->cusid }}">{{ $customer->name }}</option>
        @endforeach

@endsection

@section('stockouttype')
    @foreach($stockouttypes as $stockouttype)
        <option value="{{  $stockouttype->sotid }}">{{ $stockouttype->type }}</option>
        @endforeach

@endsection


@section('exchangerate')
    <input style="width: 70px" type="number" min="0" id="exchangerate" name="exchangerate" value="{{$exchangerate}}" class="form-control amount" readonly="readonly" />
@endsection


<script type="text/javascript">

    
    $(document).off('click','#clear');
    $(document).on('click','#clear', function(){
        $('#barcode').val("");
    });

    $(document).off('keyup','#barcode');
    $(document).on('keyup','#barcode',function(e){
        if (e.keyCode == 13) {

            if ( $('#barcode').val() ){

                var barcodeEles = $('.barcode');
                var i;
                var length = barcodeEles.length;

               
                if ( length > 0 ){
                
                    for ( i = 0 ; i < length  ; i++){

                        if ( barcodeEles[i].value === $('#barcode').val() ){
                            var eleid = barcodeEles[i].id
                            var pid = eleid.substr(0, eleid.indexOf('b'));

                            $('#barcode').val("");
                            $('#barcode').focus();

                            if ( $('#' + pid + 'qu').val()  && $('#' + pid + 'qu').val() > 0){
                                $('#' + pid + 'qu').val(parseInt($('#' + pid + 'qu').val()) + 1);
                                $('#' + pid + 'qu').keyup();
                                return; 
                            }

                            if ( $('#' + pid + 'qp').val()  && $('#' + pid + 'qp').val() > 0){
                                $('#' + pid + 'qp').val(parseInt($('#' + pid + 'qp').val()) + 1);
                                 $('#' + pid + 'qp').keyup();
                                return; 
                            }

                            if ( $('#' + pid + 'qb').val()  && $('#' + pid + 'qb').val() > 0){
                                $('#' + pid + 'qb').val(parseInt($('#' + pid + 'qb').val()) + 1);
                                 $('#' + pid + 'qb').keyup();
                                return; 
                            }


                            $('#' + pid + 'qu').val(parseInt($('#' + pid + 'qu').val()) + 1);
                            $('#' + pid + 'qu').keyup();

                            return;
                        }

                    }
                }


                $.ajax({
                type:"GET",
                url:"searchproductbarcode",
                data:{searchKey:$('#barcode').val()},    // multiple data sent using ajax
                success: function (data) {
                    

                    //console.log(data);
                    if (data.length == 1){
                        var pid = data[0].pid;
                        var row = "<tr id='tr"+pid+"' name='tr"+pid+"' >";
                        var products;
                        var html = "";
       
                        if (!$('#tr'+pid).length){ 

                            products = $('#products').val() + "," + pid ;
                            $('#products').val(products);


                        
    row = row + "<td><input id='" + pid + "pid' name='"
        + pid + "pid' type='text' value='" + data[0].pid 
        + "' style='width: 60px' readonly= 'readonly'>";
    row = row + "<input id='" + pid + "barcode' name='"
        + pid + "barcode' type='hidden' class='barcode' value='" + data[0].barcode + "'></td>";

    row = row + "<td><input id='" + pid +"name' name='"
        + pid + "name' type='text' value='" + data[0].name 
        + "' readonly= 'readonly'></td>";
    //first scan unit quantity = 1
    row = row + "<input id='" +pid+ "su' name='" +pid+ "su' type='hidden' value='" + data[0].su + "'>" 
        + "<td><input id='" +pid+ "qu' name='" +pid+ "qu' class='quantity' value='1' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' ></td>" 
        + "<td><input id='" +pid+ "up' name='" +pid+ "up' class='price' value='" + data[0].up + "' style='width: 60px' ></td>";
    
    row = row + "<input id='" +pid+ "sp' name='" +pid+ "sp' type='hidden' value='" + data[0].sp + "'>"
        + "<input id='" +pid+ "upp' type='hidden' value='" +data[0].upp + "'>"
        + "<td><input id='" +pid+ "qp' name='" +pid+ "qp' class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' ></td>"
        + "<td><input id='" +pid+ "pp' name='" +pid+ "pp' class='price' value='" +data[0].pp + "' style='width: 60px' ></td>"
        ;


    row = row + "<input id='" +pid+ "sb' name='" +pid+  "sb' type='hidden' value='" + data[0].sb + "'>"
        + "<input id='" +pid+ "upb' type='hidden' value='" +data[0].upb + "'>"
        + "<td><input id='" +pid+ "qb' name='" +pid+ "qb' class='quantity' value='0' style='width: 60px; background-color:#def9fc' pattern='[-]?[0-9]+' autocomplete='off' ></td>"
        + "<td><input id='" +pid+ "bp' name='" +pid+ "bp' class='price' value='" +data[0].bp + "' style='width: 60px' ></td>"
        ;
    
    row = row + "<td><input id = '" +pid+   "stt' name='" + pid 
        + "stt' type='number' class='stt' value='0' style='width: 100px' readonly= 'readonly'>"
        + "<input type='hidden' id='" +pid+   "tstock' name='" +pid+   "tstock' value=''></td>";


    row = row + '<td><a title="Remove Orderline" href="javascript:void(0);" class="removeorderline" data-idtr="' + pid +'"><i class="fa fa-minus-circle"></i></a></td></tr>';

    /*row = row + "<input type='hidden' id='" +pid+   "tstock' name='" 
        + pid + "tstock' value=''>";
    */                        
                            $("#orderlinebody").append(row);

                            html = html + "<tr>"  
                            + "<td>" + data[0].pid         + "</td>"
                            + "<td>" + data[0].barcode     + "</td>"
                            + "<td>" + data[0].name        + "</td>"
                            + "<td>" + data[0].shortcut    + "</td>"
                            + "<td>" + data[0].up          + "</td>"
                            + "<td>" + data[0].pp          + "</td>"
                            + "<td>" + data[0].bp          + "</td>"
                            + "<td>" + data[0].su          + "</td>"
                            + "<td>" + data[0].sp          + "</td>"
                            + "<td>" + data[0].sb          + "</td>"
                            + "<td>" + data[0].upp         + "</td>"
                            + "<td>" + data[0].upb         + "</td>";

                            /*if (data[0].isdrugs){
                                html = html + "<td>YES</td>";
                            }else{
                                html = html + "<td>NO</td>";
                            }*/

                            html = html 
                            /*+ "<td>" + data[0].catname          + "</td>"
                            + "<td>" + data[0].mname            + "</td>"*/
                            + "</tr>";
                
                            $("#productbody").html(html);

                            $('#barcode').val("");
                            $('#barcode').focus();

                            $('#'+ pid+ 'qu').keyup();
                            
                        }else{
                            //alert(e.target.id);
                            toastr.error('Product has already been in the list. Please Change the quantity instead');
                        }
                    }else if( data.length > 1) {
                        alert("More than one product returned.");
                    }else {
                        alert("No product match");
                    }
                }, 
                error: function(data){
                    console.log(data);
                }
            });

            //close of ajax send 

            }else{
                alert('Please input barcode.');
            }
        }
    });

    

    $(document).ready(function() {
        $('#barcode').focus();
    });


   
</script>

