<x-datepicker/>
<table id="sweetpost" class="table mt-20">
    <thead>
    <tr>
        <th>Marketing cost (as percentage of sales)</th>
        <th>Revenue needed</th>
        <th class="bold">Scale Difficulty</th>
        <th>Allowable marrketing costs</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ $item['marketing_cost'] }}</td>
            <td>{{ $item['revenue_needed'] }}</td>
            <td class="bold">
                @if(isset($item['optimal_coefficient']))<span>{{ $item['optimal_coefficient'] }}</span>@endif
                @if(isset($item['optimal_coefficient_full']))<div class="help">{{ $item['optimal_coefficient_full'] }}</div>@endif
            </td>
            <td>{{ $item['allowable_marketing_cost'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
