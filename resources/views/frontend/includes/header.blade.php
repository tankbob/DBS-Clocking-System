<div class="header text-center-sm">
	<div class="headerContainer">
		@if(Auth::user())
			<div class="col-xs-4 col-xs-offset-8">
				<a class="logout-btn" href="/auth/logout"></a>
			</div>
		@endif
		<img class="col-xs-offset-2 headerLogo" src="/images/dbslogo.svg" width="173" height="104" alt="DBS Contracts" data-mu-svgfallback="images/dbslogo_poster_.png">
		<div class="hidden-xs headerPhone col-sm-offset-8">
			01622 715 919
		</div>
	</div>
</div>