@extends('layouts.app')
@section('page-title',  __('Edit') )
@section('content')
<div class="container-fluid mt--8">
    <!-- Search -->
<div class="row">
    <div class="col">
        <div class="card shadow">
        <div class="card-header border-0">
            
        </div>
        <hr class="my-1">
        <div class="card-body">
            <form action="" method="POST" >
                @csrf
                <input  type="hidden" value="{{ $partner->id }}" id="id" name="id">
                <div class="row">
                    @include('partners.partials.base', ['edit' => true])
                </div>
                <div class="text-left">
                    <button type="submit" class="btn btn-success">{{__('Apply')}}</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

</div>
@endsection