<div class="menu text-center">
	<a href="/admin/users" class="menuLink usersLink @if(isset($page) && $page == 'users') usersSelected @endif"></a>
	<a href="/admin/operatives" class="menuLink operativeLink @if(isset($page) && $page == 'operatives') operativeSelected @endif"></a>
	<a href="/admin/hours" class="menuLink hoursLink @if(isset($page) && $page == 'hours') hoursSelected @endif"></a>
	<a href="/admin/payment" class="menuLink paymentLink @if(isset($page) && $page == 'payment') paymentSelected @endif"></a>
	<a href="/admin/jobs" class="menuLink jobLink @if(isset($page) && $page == 'jobs') jobSelected @endif"></a>
</div>