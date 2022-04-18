<x-datepicker/>
<table id="sweetpost" class="table mt-20">
    <thead>
    <tr>
        <th>Marketing cost (as percentage of sales)</th>
        <th>Revenue needed</th>
        <th class="bold">3rd change in difference</th>
        <th>Allowable marrketing costs</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item['marketing_cost'] }}</td>
            <td>${{ number_format($item['revenue_needed'], 0, '.', ',') }}</td>
            <td class="bold">@if(isset($item['optimal_coefficient'])){{$item['optimal_coefficient']}}%@endif</td>
            <td>${{ number_format($item['allowable_marketing_cost'], 0, '.', ',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
