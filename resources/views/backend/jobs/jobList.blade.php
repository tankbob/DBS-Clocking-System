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

    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
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
                        <th>On Screen Name</th>
                        <th>Main Contractor</th>
                        <th>Foreman</th>
                        <th>Active</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                        <tr>
                            <td class="paddingLeft">
                                <a href="#" id="{{$job->id}}-number" class="editable" data-type="text" data-pk="1" data-url="/ajaxeditjobs" data-title="Enter value">
                                    {{$job->number}}
                                </a>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $("#{{$job->id}}-number").editable({
                                            value: "{{$job->number}}",
                                            params: function( params ) {
                                                params.job_id = "{{$job->id}}";
                                                params.field = "number";
                                                return params;
                                            },
                                            success: function(response, newValue) {
                                                if(response.success){
                                                   
                                                }
                                            }
                                        })
                                    });
                                </script>
                            </td>
                            <td class="paddingLeft">
                                <a href="#" id="{{$job->id}}-screen_name" class="editable" data-type="text" data-pk="1" data-url="/ajaxeditjobs" data-title="Enter value">
                                    {{$job->screen_name}}
                                </a>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $("#{{$job->id}}-screen_name").editable({
                                            value: "{{$job->screen_name}}",
                                            params: function( params ) {
                                                params.job_id = "{{$job->id}}";
                                                params.field = "screen_name";
                                                return params;
                                            },
                                            success: function(response, newValue) {
                                                if(response.success){
                                                   
                                                }
                                            }
                                        })
                                    });
                                </script>
                            </td>
                            <td class="paddingLeft">
                                <a href="#" id="{{$job->id}}-contractor" class="editable" data-type="text" data-pk="1" data-url="/ajaxeditjobs" data-title="Enter value">
                                    {{$job->contractor}}
                                </a>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $("#{{$job->id}}-contractor").editable({
                                            value: "{{$job->contractor}}",
                                            params: function( params ) {
                                                params.job_id = "{{$job->id}}";
                                                params.field = "contractor";
                                                return params;
                                            },
                                            success: function(response, newValue) {
                                                if(response.success){
                                                   
                                                }
                                            }
                                        })
                                    });
                                </script>
                            </td>
                            <td class="paddingLeft">
                                <a href="#" id="{{$job->id}}-foreman" class="editable" data-type="text" data-pk="1" data-url="/ajaxeditjobs" data-title="Enter value">
                                    {{$job->foreman}}
                                </a>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $("#{{$job->id}}-foreman").editable({
                                            value: "{{$job->foreman}}",
                                            params: function( params ) {
                                                params.job_id = "{{$job->id}}";
                                                params.field = "foreman";
                                                return params;
                                            },
                                            success: function(response, newValue) {
                                                if(response.success){
                                                   
                                                }
                                            }
                                        })
                                    });
                                </script>
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