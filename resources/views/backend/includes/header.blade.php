<div class="header text-center-sm">
	<div class="headerContainer">
		@if(Auth::user())
			<div class="col-xs-4 col-xs-offset-8">
				{!! Form::open(['url' => '/auth/logout', 'method' => 'GET']) !!}
					<input type="text" class="hidden" name="page" value="admin">
					<button class="logout-btn" href="/auth/logout"></button>
				{!! Form::close() !!}
			</div>
		@endif
		<img class="col-xs-offset-2 headerLogo" src="/images/dbs_logo.jpg" width="173" height="104" alt="DBS Contracts" data-mu-svgfallback="images/dbslogo_poster_.png">
	</div>
</div>