<div class="header bg-gradient-primary pb-8 pt-md-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                <h6 class="h2 text-white d-inline-block mb-0">@yield('page-title')</h6>
                </div>
                @yield('content-right-header')
            </div>
            @include('backend.argon.layouts.headers.messages')
            @yield('content-header')
        </div>
    </div>
</div>