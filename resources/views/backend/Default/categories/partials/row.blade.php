<tr>
	<td>
		@if ($category->site) 
			{{$category->site->title}} ( {{$category->site->domain }} )
		@else
			도메인없음
		@endif
	</td>
	<td>
		<span class=" @if ($base) text-blue @else text-green @endif ">{{ $category->trans->trans_title }}</span>
	</td>
	<td>{{ $category->position }}</td>
	<td><span class=" @if ($category->view==1) text-green @else text-red @endif ">{{ $category->view==1?'활성':'점검' }}</span></td>
	<td>
		@if (!auth()->user()->hasRole('admin') && $category->site_id==0)
			<button type="button" class="btn btn-block btn-danger btn-xs" disabled>어드민에 요청</button>
		@else
			@if($category->view == 1)
				<a href="{{route($admurl.'.category.show', $category->id) . '?view=0'}}">
				<button type="button" class="btn btn-block btn-danger btn-xs">점검</button>
			@else
			<a href="{{route($admurl.'.category.show', $category->id) . '?view=1'}}">
				<button type="button" class="btn btn-block btn-success btn-xs">활성</button>
			@endif	
		</a>
		@endif
	</td>

</tr>