@if (!Auth::check())
    @include('frontend.k1slot.auth.login')
@else
    @include('frontend.k1slot.layouts.app')
@endif