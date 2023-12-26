
<thead class="bg-primary text-white">
    @foreach($res['result'] as $res1)
    <tr>
        <th class="text-white">{{$res1['date']}}</th>
        <th class="text-white">{{$res1['branchname']}}</th>
        <th class="text-white">{{$res1['leaguename']}}</th>
        <th class="text-white">{{$res1['bettype']}}</th>
        <th class="text-white">{{$res1['bettypename']}}</th>
        <th class="text-white">{{$res1['game']}}</th>
        <th class="text-white">{{$res1['odd']}}</th>
        <th class="text-white">{{$res1['yourbet']}}</th>
        <th class="text-white">{{$res1['score']}}</th>
        <th class="text-white">{{$res1['stat']}}</th>
    </tr>
    @endforeach
</thead>            
