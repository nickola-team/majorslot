<tr>        
    <td>{{$notice->title}}</td>
	<td>{{ $notice->date_time }}</td>
    <td>
        {{\VanguardLTE\Notice::lists()[$notice->type]}}
    </td>
    <td>{{ $notice->active==1?'활성':'비활성' }} </td>
    @if (auth()->user()->hasRole('admin'))
    <td>{{$notice->writer?$notice->writer->username:'알수없음'}}</td>
    @endif
    <td>
    <a href="{{ route($admurl.'.notice.edit', $notice->id) }}">
        <button type="button" class="btn btn-block btn-success btn-xs">편집</button>
    </a>
    </td>
</tr>