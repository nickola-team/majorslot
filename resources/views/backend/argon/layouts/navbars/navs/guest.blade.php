<nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
    <div class="container px-4">
        <a class="navbar-brand" href="/">
        @if (isset($site))
            <img src="{{ url('/back/img/' . $site->frontend . '_logo.png') }}" style="height:70px;"></img>
        @endif
        </a>
    </div>
</nav>