<tr>
    <td><a href="{{ route($admurl.'.game.bonusbank', ['id'=>$bank->master_id]) }}">{{ $bank->master->username }}</a></td>
	<td>{{ $bank->games }}</td>
	<td>{{ number_format($bank->totalBank) }} </td>
</tr>