@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (Session::get('success'))
	<div class="alert alert-success">
		{{ Session::get('success') }}
	</div>
@endif
@if (isset($msg) && $msg)
    <div class="alert alert-success">
        {{$msg}}
    </div>
@endif