<td>{{ $shop->id }}</td>
<td>{{ $shop->name }} </td>
<td>{{ $shop->deal_percent }}</td>
<td>{{ $shop->table_deal_percent }}</td>
@if (auth()->user()->hasRole('admin'))
<td>
    @if ($shop->info)
        @foreach ($shop->info as $inf)
            @if ($inf->info->roles == 'slot')
            {{$inf->info->text}}
            @endif
        @endforeach
    @endif
</td>
<td>
    @if ($shop->info)
        @foreach ($shop->info as $inf)
            @if ($inf->info->roles == 'table')
            {{$inf->info->text}}
            @endif
        @endforeach
    @endif
</td>
@endif
<td>
    @if ($shop->slot_miss_deal>0)
        <span class="text-green">적용중</span>
        <a  class="btn btn-warning btn-sm" href="{{argon_route('argon.game.missrolestatus', ['type'=>'slot','id'=>$shop->id,'status'=>0])}}">금지</a>
    @else
        <span class="text-red">금지됨</span>
        <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.missrolestatus', ['type'=>'slot','id'=>$shop->id,'status'=>1])}}">적용</a>
    @endif
</td>

<td>
    @if ($shop->table_miss_deal>0)
        <span class="text-green">적용중</span>
        <a  class="btn btn-warning btn-sm" href="{{argon_route('argon.game.missrolestatus', ['type'=>'table','id'=>$shop->id,'status'=>0])}}">금지</a>
    @else
        <span class="text-red">금지됨</span>
        <a class="btn btn-success  btn-sm" href="{{argon_route('argon.game.missrolestatus', ['type'=>'table','id'=>$shop->id,'status'=>1])}}">적용</a>
    @endif
</td>