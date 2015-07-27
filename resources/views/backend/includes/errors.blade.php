@if (count($errors) > 0)
    <div class="col-xs-12">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
@if (Session::get('success'))
    <div class="col-xs-12">
        <div class="col-md-8 col-md-offset-2">
        	<div class="alert alert-success">
        		{{ Session::get('success') }}
        	</div>
        </div>
    </div>
@endif
@if (isset($msg) && $msg)
    <div class="col-xs-12">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-success">
                {{$msg}}
            </div>
        </div>
    </div>
@endif