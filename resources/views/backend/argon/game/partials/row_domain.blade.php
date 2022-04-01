@foreach ($site->categories as $category)
    @if ($loop->index > 0)
	<tr>
	@endif
    <td>
        {{$category->title}}
    <td>{{$category->position}}</td>
    <td>{{$category->provider??'자체버전'}}</td>

    <td>
        @if ($category->view == 1)
        <span class="text-green">활성</span>
        @else
        <span class="text-red">비활성</span>
        @endif
    </td>
    <td class="text-right">
        <a class="btn btn-success  btn-sm" href="#">활성</a>
        <a  class="btn btn-warning btn-sm" href="#">비활성</a>
    </td>
    @if ($loop->index > 0)
	</tr>
	@endif
@endforeach
