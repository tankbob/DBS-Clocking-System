@if (count($errors) > 0)
    <div class="danger-message">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (Session::get('success'))
    <div class="success-message">
        {{ Session::get('success') }}
    </div>
@endif
@if (Session::get('error'))
    <div class="danger-message">
        {{ Session::get('error') }}
    </div>
@endif