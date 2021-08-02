<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('app.title')</label>
            <input type="text" class="form-control" id="title" name="name" placeholder="@lang('app.title')" required value="{{ $edit ? $shop->name : old('name') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        <label>딜비%</label>
        <input type="number"  step="0.01" class="form-control" id="deal_percent" name="deal_percent" value="{{ $edit ? $shop->deal_percent : '0' }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        <label>라이브딜비%</label>
        <input type="number"  step="0.01" class="form-control" id="table_deal_percent" name="table_deal_percent" value="{{ $edit ? $shop->table_deal_percent : '0' }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        <label>죽장%</label>
        <input type="number"  step="0.01" class="form-control" id="ggr_percent" name="ggr_percent" value="{{ $edit ? $shop->ggr_percent : '0' }}">
        </div>
    </div>    

    @if(auth()->user()->hasRole('admin'))
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('app.percent')%</label>
            @php
                $percents = array_combine(\VanguardLTE\Shop::$values['percent'], \VanguardLTE\Shop::$values['percent']);
            @endphp
            {!! Form::select('percent', $percents, $edit ? $shop->percent : old('percent')?:'90', ['class' => 'form-control']) !!}
        </div>
    </div>
    @endif
    

    <div class="col-md-6"  style="display: none;">
        <div class="form-group">
            <label> @lang('app.frontend')</label>
            {!! Form::select('frontend', $directories, $edit ? $shop->frontend : old('frontend')?:'Default', ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-6"  style="display: none;">
        <div class="form-group">
            <label> @lang('app.order')</label>
            @php
                $orders = array_combine(\VanguardLTE\Shop::$values['orderby'], \VanguardLTE\Shop::$values['orderby']);
            @endphp
            {!! Form::select('orderby', $orders, $edit ? $shop->orderby : old('orderby'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-6"  style="display: none;">
        <div class="form-group">
            <label> @lang('app.currency')</label>
            @php
                $currencies = array_combine(\VanguardLTE\Shop::$values['currency'], \VanguardLTE\Shop::$values['currency']);
            @endphp
            {!! Form::select('currency', $currencies, $edit ? $shop->currency : old('currency')?:'KRW', ['class' => 'form-control']) !!}
        </div>
    </div>
    @if($edit && count($blocks))
    <div class="col-md-6">
        <div class="form-group">
            <label for="device">
                @lang('app.status')
            </label>
            {!! Form::select('is_blocked', $blocks, $edit ? $shop->is_blocked : old('is_blocked'), ['class' => 'form-control']) !!}
        </div>
    </div>
    @endif
</div>


