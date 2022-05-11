<x-datepicker/>
<table id="sweetpost" class="table mt-20">
    <thead>
    <tr>
        <th>Marketing cost (as percentage of sales)</th>
        <th>Revenue needed</th>
        <th class="bold">Optimal coefficient</th>
        <th>Allowable marrketing costs</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item['marketing_cost'] }}</td>
            <td>{{ $item['revenue_needed'] }}</td>
            <td class="bold">@if(isset($item['optimal_coefficient'])){{ $item['optimal_coefficient'] }}%@endif</td>
            <td>{{ $item['allowable_marketing_cost'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
