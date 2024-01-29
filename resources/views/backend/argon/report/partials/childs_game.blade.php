
@foreach ($adjustment['cat'] as $cat)
    @if (auth()->user()->hasRole('admin') && Request::get('partner') == '' && Request::get('cat') == '')
    <tr data-tt-id="{{$cat['category_id'] }}~{{$cat['date']}}~{{$cat['type']}}" data-tt-parent-id="{{isset($parent_id)?$parent_id:$cat['category_id']}}" data-tt-branch="true">            
    @if ($loop->index == 0)
        <td rowspan="{{count($adjustment['cat'])}}"> {{ $adjustment['date'] }}</td>
    @endif
        @include('backend.argon.report.partials.row_game')
    </tr>
    @else
    <tr data-tt-id="-10" data-tt-parent-id="{{isset($parent_id)?$parent_id:0}}">            
        @include('backend.argon.report.partials.row_game')
    </tr>
    @endif
@endforeach