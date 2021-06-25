@foreach ($adjustment['cat'] as $cat)
	@if ($loop->index > 0)
	<tr>
	@endif
	<td >{{ $cat['title'] }}</td>
	<td >{{ number_format($cat['totalbet'],0) }}</td>
	<td >{{ number_format($cat['totalwin'],0)}}</td>
	<td >{{ number_format($cat['totalbet'] - $cat['totalwin'],0)}}</td>
	<td >{{ number_format($cat['totalcount'])}}</td>
	<td >{{ number_format($cat['total_deal'],0)}}</td>
	<td >{{ number_format($cat['total_mileage'],0)}}</td>
	<td >{{ number_format($cat['total_deal'] - $cat['total_mileage'],0)}}</td>

	@if ($loop->index > 0)
	</tr>
	@endif
@endforeach