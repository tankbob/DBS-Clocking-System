<div class="header text-center-sm">
	<div class="headerContainer container">
		
		<img class="headerLogo" src="/images/dbs_logo.jpg" width="178" height="107" alt="DBS Contracts" data-mu-svgfallback="images/dbslogo_poster_.png">
		@if(Auth::user())
			<div class="">
				{!! Form::open(['url' => '/auth/logout', 'method' => 'GET']) !!}
					<input type="text" class="hidden" name="page" value="admin">
					<button class="logout-btn" href="/auth/logout"></button>
				{!! Form::close() !!}
			</div>
		@endif
	</div>
</div>