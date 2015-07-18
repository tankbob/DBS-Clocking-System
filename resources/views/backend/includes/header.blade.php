<div class="header text-center-sm">
	<div class="headerContainer">
		@if(Auth::user())
			<div class="col-xs-4 col-xs-offset-8">
				<a class="logout-btn" href="/auth/logout"></a>
			</div>
		@endif
		<img class="col-xs-offset-2 headerLogo" src="/images/dbs_logo.jpg" width="173" height="104" alt="DBS Contracts" data-mu-svgfallback="images/dbslogo_poster_.png">
	</div>
</div>