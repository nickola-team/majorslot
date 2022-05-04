@if ($total)
<td>{{$user->username}}</td>
<?php
    $game = $stat->title;
    if ($gacmerge == 1)
    {
        $game = preg_replace('/GameArtCasino/', 'EvolutionVIP', $game);
    }
?>
<td>
@if (auth()->user()->hasRole('admin'))
    <a href="{{argon_route('argon.report.game.details', ['cat_id'=>$stat->category_id, 'dates' => Request::get('dates')])}}">{{$game}}</a>
@else
    {{$game}}
@endif
</td>
<td>{{number_format($stat->totalbet)}}</td>
<td>{{number_format($stat->totalwin)}}</td>
<td>{{number_format($stat->totalbet-$stat->totalwin)}}</td>
<td>{{number_format($stat->totaldeal)}}</td>
@else
@foreach ($category['cat'] as $stat)
    @if ($loop->index > 0)
	<tr>
	@endif
    <td>{{$user->username}}</td>
    <?php
        $game = $stat['title'];
        if ($gacmerge == 1)
        {
            $game = preg_replace('/GameArtCasino/', 'EvolutionVIP', $game);
        }
    ?>
    <td>
    @if (auth()->user()->hasRole('admin'))
        <a href="{{argon_route('argon.report.game.details', ['cat_id'=>$stat['category_id'],'dates'=>[$category['date'], $category['date']]])}}">{{$game}}</a>
    @else
        {{$game}}
    @endif
    </td>    
    <td>{{number_format($stat['totalbet'])}}</td>
    <td>{{number_format($stat['totalwin'])}}</td>
    <td>{{number_format($stat['totalbet']-$stat['totalwin'])}}</td>
	<td>{{number_format($stat['totaldeal'])}}</td>
    @if ($loop->index > 0)
	</tr>
	@endif
@endforeach
@endif
