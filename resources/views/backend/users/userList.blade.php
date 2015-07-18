@extends('backend.includes.layout')

@section('title')
	@if($page = 'users')
		Users
	@else
		Operatives
	@endif
@stop