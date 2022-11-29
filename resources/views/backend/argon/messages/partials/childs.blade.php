@if (count($msgs) > 0)
    @foreach ($msgs as $msg)
    <?php
        $backcolor = '';
        $fontbold = '';
        if ($msg->user && $msg->user->id==auth()->user()->id && $msg->read_at==null)
        {
            $backcolor = 'lightgreen';
            $fontbold = 'bold';
        }
        foreach ($msg->refs as $ref)
        {
            if ($ref->user && $ref->user->id==auth()->user()->id && $ref->read_at==null)
            {
                $backcolor = 'lightgreen';
                $fontbold = 'bold';
            }
        }
    ?>
        <tr data-tt-id="{{$msg->id}}" data-tt-parent-id="{{$msg->ref_id}}" data-tt-branch="{{count($msg->refs)>0?'true':'false'}}" style="background-color:{{$backcolor}};font-weight:{{$fontbold}};">
            @include('backend.argon.messages.partials.row')
        </tr>
    @endforeach
@else
    <tr  data-tt-id="-10" data-tt-parent-id="{{isset($child_id)?$child_id:0}}" >
        <td colspan='8'>No Data</td>
    </tr>
@endif