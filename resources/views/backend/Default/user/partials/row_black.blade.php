<tr>
    <td>{{$user->name}}</td>
	<td>{{$user->phone}}</td>
	<td>
		@if ($user->account_number != null)
			[{{$user->account_bank}}]{{$user->account_number}}
		@endif
	</td>
	<td>{{$user->account_name}}</td>
	<td>{{$user->memo}}</td>
	<td>{{$user->created_at}}</td>
</tr>