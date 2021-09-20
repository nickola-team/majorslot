@foreach ($adjustment['cat'] as $cat)
	@if ($loop->index > 0)
	<tr>
	@endif
	<td >
	<span class="{{$total?'text-red':''}}">
	@if (!$total && auth()->user()->hasRole('admin') && Request::get('partner') == '' && Request::get('cat') == '')
	<a href="{{route($admurl.'.adjustment_game', ['cat'=>$cat['category_id'], 'date' => $cat['date'], 'type'=>$cat['type']] ) }}"> {{$cat['title'] }} </a>
	@else
	{{$cat['title'] }}
	@endif
	</span>
	</td>
	<td ><span class="{{$total?'text-red':''}}"> {{ number_format($cat['totalbet'],0) }} </span></td>
	<td ><span class="{{$total?'text-red':''}}"> {{ number_format($cat['totalwin'],0)}}</span></td>
	<td ><span class="{{$total?'text-red':''}}"> {{ number_format($cat['totalbet'] - $cat['totalwin'],0)}}</span></td>
	<td ><span class="{{$total?'text-red':''}}"> {{ number_format($cat['totalcount'])}}</span></td>
	<td ><span class="{{$total?'text-red':''}}"> {{ number_format($cat['total_deal'],0)}}</span></td>
	<td ><span class="{{$total?'text-red':''}}"> {{ number_format($cat['total_mileage'],0)}}</span></td>
	<td ><span class="{{$total?'text-red':''}}"> {{ number_format($cat['total_deal'] - $cat['total_mileage'],0)}}</span></td>

	@if ($loop->index > 0)
	</tr>
	@endif
@endforeach