<!DOCTYPE html>
<html>
<head>
    <title>Print Stock Reminder</title>

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
            border-bottom: 1px solid black;
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
            width: 60%;
        }

        .td-qty{
            width: 3%;
        }

        .td-price{
            width: 5%;
        }
        .td-importer{
            width: 27%;
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

        .table-orderline td:nth-child(4){
            text-align: left;
        }

        .table-orderline td:nth-child(5){
            text-align: right;
        }
    </style>

</head>
<body>

    
    <p class="receipt">Stock Reminder {{$date}}
    <table class="table-orderline">
        <tr>
            <th class="td-num">#</th>
            <th class="td-name">Product</th>
            <th class="td-qty">BoxInStock</th>
            <th class="td-importer">Importer</th>
            <th class="td-price">BoxPrice</th>
        </tr>

        @php
            $i = 0;
        @endphp
        @foreach($products as $product)
            
            @php
                $i ++;
            @endphp

            
        <tr >
            <td >
                <p>{{$i}}</p>
            </td>
            <td ><p>{{$product->name}}</p>
            </td>
            <td>
                <p>{{number_format($product->totalboxinstock,2)}}</p>
            </td>
            <td >
                <p>{{$product->importer}}</p>
            </td>
            <td >
                <p >{{number_format($product->buypricebox,2)}} </p>
            </td>
        </tr>

        @endforeach

    </table>
</body>
</html>