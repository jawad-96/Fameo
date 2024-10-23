

<!DOCTYPE html>
<html>


{{--    <!--<h1>Hi Dear, {{ $name }}</h1>-->--}}

{{--<!--<p>Hi how are you, {{ $data[0] }}</p>-->--}}
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 50%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>


<p>Hi Biotech,<br />
<br />
<br />
<br />


<h2>Biocos Assembly Reminder</h2>




<table>
  <tr>
    <th  >Item Name</th>
    <th>Carton Qty</th>
    <th>Date</th>
  </tr>

{{--  @foreach($data as $item)--}}
{{--  <tr>--}}
{{--    --}}
{{--       --}}
{{--         <td>{{ $item->product_name }}</td>  --}}
{{--                  <td>{{ $item->cart_qty }}</td>  --}}
{{--     --}}
{{--     <td>{{date('d-m-y',strtotime($item->created_at))}}</td> --}}
{{--     --}}
{{--     --}}
{{--     --}}
{{--  </tr>--}}
{{--  --}}
{{--  @endforeach--}}

</table>

</body>
</html>
