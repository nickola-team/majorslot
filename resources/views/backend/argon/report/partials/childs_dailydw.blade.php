<?php
    $totalggr = 0;
?>
@if (count($summary))
    @foreach ($summary as $adjustment)
        <?php
            $ggr = 0;
            $comaster = $adjustment->user;
            while ($comaster && !$comaster->hasRole('comaster'))
            {
                $comaster = $comaster->referral;
            }
            if ($comaster)
            {
                $ggr = ($adjustment->totalbet - $adjustment->totalwin) * $comaster->money_percent / 100;
            }
            $totalggr = $totalggr + $ggr;
        ?>
        <tr data-tt-id="{{$adjustment->user_id }}~{{date('Y-m-d',strtotime($adjustment->date))}}" data-tt-parent-id="{{isset($parent_id)?$parent_id:$adjustment->user->parent_id}}" data-tt-branch="{{$adjustment->user->role_id>3?'true':'false'}}">
            @include('backend.argon.report.partials.row_dailydw', ['ggr' => $ggr])
        </tr>
    @endforeach
@else
    <tr  data-tt-id="-10" data-tt-parent-id="{{isset($parent_id)?$parent_id:0}}" >
        <td colspan='9'>No Data</td>
    </tr>
@endif