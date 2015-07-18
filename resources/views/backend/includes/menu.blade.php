<div class="menu text-center">
	<a href="/admin/users" class="menuLink usersLink @if(isset($page) && $page == 'users') usersSelected @endif"></a>
	<a href="/admin/operative" class="menuLink operativeLink @if(isset($page) && $page == 'operative') operativeSelected @endif"></a>
	<a href="/admin/hours" class="menuLink hoursLink @if(isset($page) && $page == 'hours') hoursSelected @endif"></a>
	<a href="/admin/payment" class="menuLink paymentLink @if(isset($page) && $page == 'payment') paymentSelected @endif"></a>
	<a href="/admin/job" class="menuLink jobLink @if(isset($page) && $page == 'job') jobSelected @endif"></a>
</div>