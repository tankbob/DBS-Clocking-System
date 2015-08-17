@extends('backend.includes.layout')

@section('title')
    Job
@stop

@section('scripts')
    <script type="text/javascript">
       function deleteJob(id){
            event.preventDefault();
            if(confirm("Are you sure you want to delete this job? All information will be lost and the times logged for this job will disappear.")){
                $('#deleteForm'+id).submit();
            }
        }
    </script>
@stop

@section('content')

    @include('backend.includes.errors')

    <div class="add-user-form col-sm-12">
        <div class="col-sm-5 no-side-padding col-sm-offset">
            <h1>
                ADD JOB:
            </h1>
        </div>
        <div class="col-sm-7 login-form">
            {!! Form::open(['url' => '/admin/jobs', 'method' => 'post', 'class' => 'form-horizontal']) !!}

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="number">Job Number:</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="number" value="{{ old('number') }}" class="login-input">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="screen_name">Screen Name:</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="screen_name" value="{{ old('screen_name') }}" class="login-input">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="address">Address:</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="address" value="{{ old('address') }}" class="login-input">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="postcode">Postcode:</label>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="postcode" value="{{ old('postcode') }}" class="login-input">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="contractor">Main Contractor:</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="contractor" value="{{ old('contractor') }}" class="login-input">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 no-side-padding">
                        <label for="foreman">Foreman:</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="foreman" value="{{ old('foreman') }}" class="login-input">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button class="submit-btn"></button>
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h1>JOB NUMBER LOCATIONS:</h1>

            <div>
                <div class="col-xs-6 table-next">
                    @if(Input::get('page') > 1)
                        <a href="{{ $jobs->previousPageUrl() }}">&lt; Previous Page</a>
                    @endif
                    @if($jobs->hasMorePages())
                        <a href="{{ $jobs->nextPageUrl() }}">Next Page &gt;</a>
                    @endif
                </div>
                <div class="col-xs-6 text-right table-select">
                    Page:
                    <select onchange="window.location = this.options[this.selectedIndex].value">
                    @for ($i = 1; $i <= $jobs->lastPage(); $i++)
                        <option value="{{ Request::url() }}?page={{ $i }}" @if(Input::get('page') == $i) selected="selected" @endif>{{ sprintf('%02d', $i) }} of {{ sprintf('%02d', $jobs->lastPage()) }}</option>
                    @endfor
                       
                    </select>
                </div>
            </div>

            <table class="table-responsive table-bordered table-hover dbs-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Job Number</th>
                        <th>Main Contractor</th>
                        <th>Foreman</th>
                        <th>On Screen Name</th>
                        <th>Active</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td class="paddingLeft">
                                {{$job->number}}
                            </td>
                            <td class="paddingLeft">
                                {{$job->contractor}}
                            </td>
                            <td class="paddingLeft">
                                {{$job->foreman}}
                            </td>
                            <td class="paddingLeft">
                                {{$job->screen_name}}
                            </td>
                            <td class="text-center">
                                {!! Form::open(['url' => "/admin/jobs/$job->id", 'method' => 'PUT']) !!}
                                    @if($job->active)
                                        <button class="success-btn"></button>
                                    @else
                                        <button class="cross-btn"></button>
                                    @endif
                                {!! Form::close() !!}

                            </td>                            
                            <td class="text-center">                    
                                <a class="cross-btn" onclick="deleteJob({{$job->id}})"></a> 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach($jobs as $job)
        {!! Form::open(['url' => "/admin/jobs/$job->id", 'method' => 'DELETE', 'id' => 'deleteForm'.$job->id]) !!}

        {!! Form::close() !!}
    @endforeach

@stop