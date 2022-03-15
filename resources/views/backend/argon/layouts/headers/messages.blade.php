@if(isset ($errors) && count($errors) > 0)
    <div class="alert alert-danger">
        <h4>오류</h4>
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

@if(Session::get('success', false))
    <?php $data = Session::get('success'); ?>
    @if (is_array($data))
        @foreach ($data as $msg)
	        <div class="alert alert-success">
                <h4>조작성공</h4>
                <p>{{ $msg }}</p>
            </div>
        @endforeach
    @else
	        <div class="alert alert-success">
                <h4>조작성공</h4>
            <p>{{ $data }}</p>
            </div>
    @endif
@endif