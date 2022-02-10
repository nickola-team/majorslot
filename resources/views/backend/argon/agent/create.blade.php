@extends('layouts.app')
@section('page-title',  __('New') )
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
                <div class="row">
                    @include('partners.partials.base', ['edit'=>false])
                </div>
                <div class="text-left">
                    <button type="submit" class="btn btn-success">{{__('Add')}}</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

</div>
@endsection