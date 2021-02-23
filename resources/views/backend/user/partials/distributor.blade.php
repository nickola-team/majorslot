<td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
@if( auth()->user()->hasRole(['admin','agent']) )

    <a href="{{ route('backend.user.edit', $distributor->id) }}">
        {{ $distributor->username ?: trans('app.n_a') }}
    </a>
@else
{{ $distributor->username ?: trans('app.n_a') }}
@endif    
</td>
<td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
    {{ number_format($distributor->balance,2)}}원 / {{ number_format($distributor->deal_balance - $distributor->mileage,2)}}원 / {{ $distributor->deal_percent }}%
</td>

@if( count($distributor->shops()) )
    @if($shops = $distributor->rel_shops)
        @foreach($shops AS $shop)
            <?php $index = $loop->index; ?>
            @if($shop = $shop->shop)
                <td>
                @if ( auth()->check() && auth()->user()->hasRole(['admin','agent', 'distributor']) )
                    <a href="{{ route('backend.shop.edit', $shop->id) }}">{{ $shop->name }}</a>
                @else
                    {{ $shop->name }}
                @endif
                </td>
                <td>
                    {{ number_format($shop->balance,2)}}원 / {{ number_format($shop->deal_balance - $shop->mileage,2)}}원 / {{ $shop->deal_percent}}%
                </td>

                @if( $managers = $shop->getUsersByRole('manager') )
                    @if(count($managers))
                        @foreach($managers AS $manager)
                            <td>
                                <a href="{{ route('backend.user.edit', $manager->id) }}">
                                    {{ $manager->username ?: trans('app.n_a') }}
                                </a>
                            </td>

                            {{--@if( $cashiers = $manager->getInnerUsers() )
                                @foreach($cashiers AS $cashier) --}}
                                    {{-- <td>
                                        <a href="{{ route('backend.user.edit', $cashier->id) }}">
                                            {{ $cashier->username ?: trans('app.n_a') }}
                                        </a>
                                    </td> --}}
                                    @if ($index==0)
                                    @if( auth()->user()->hasRole('agent') )
                                        <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                                        <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $distributor->id }}" >
                                        <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
                                        </a>
                                        </td>
                                        <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                                        <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $distributor->id }}" >
                                        <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
                                        </a>
                                        </td>
                                    @elseif( auth()->user()->hasRole('admin') )
                                        @if ($agentCount == 0)
                                            <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
                                            <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $agentID }}" >
                                            <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
                                            </a>
                                            </td>
                                            <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
                                            <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $agentID }}" >
                                            <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
                                            </a>
                                            </td>
                                        @endif
                                    @else
                                        <td colspan="2"></td>
                                    @endif
                                    @endif
                                </tr>
                                <tr>

                             {{--   @endforeach 
                            @else
                                <td colspan="1"></td></tr><tr>
                            @endif --}}
                        @endforeach
                    @else
                        <td colspan="1"></td>
                        @if( auth()->user()->hasRole('agent') )
                            <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                            <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $distributor->id }}" >
                            <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
                            </a>
                            </td>
                            <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                            <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $distributor->id }}" >
                            <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
                            </a>
                            </td>
                        @elseif( auth()->user()->hasRole('admin') )
                            @if ($agentCount == 0)
                                <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
                                <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $agentID }}" >
                                <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
                                </a>
                                </td>
                                <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
                                <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $agentID }}" >
                                <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
                                </a>
                                </td>
                            @endif
                        @else
                            <td colspan="2"></td>
                        @endif
                        </tr><tr>
                    @endif
                @endif
            @else
                <td colspan="2"></td>
                @if( auth()->user()->hasRole('agent') )
                    <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                    <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $distributor->id }}" >
                    <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
                    </a>
                    </td>
                    <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
                    <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $distributor->id }}" >
                    <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
                    </a>
                    </td>
                @elseif( auth()->user()->hasRole('admin') )
                    @if ($agentCount == 0)
                        <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
                        <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $agentID }}" >
                        <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
                        </a>
                        </td>
                        <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
                        <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $agentID }}" >
                        <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
                        </a>
                        </td>
                    @endif
                @else
                    <td colspan="2"></td>
                @endif
                </tr><tr>
            @endif
        @endforeach
    @endif
@else

    <td colspan="3"></td>
    @if( auth()->user()->hasRole('agent') )
        <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
        <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $distributor->id }}" >
        <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
        </a>
        </td>
        <td rowspan="{{ $distributor->getRowspan() }}" style="vertical-align : middle;">
        <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $distributor->id }}" >
        <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
        </a>
        </td>
    @elseif( auth()->user()->hasRole('admin') )
        @if ($agentCount == 0)
            <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
            <a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $agentID }}" >
            <button type="button" class="btn btn-block btn-success btn-xs">@lang('app.in')</button>
            </a>
            </td>
            <td rowspan="{{ $agentRowSpan }}" style="vertical-align : middle;">
            <a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $agentID }}" >
            <button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
            </a>
            </td>
        @endif
    @else
        <td colspan="2"></td>
    @endif
    </tr><tr>
    
@endif
