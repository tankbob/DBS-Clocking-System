<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Dear {{$user->name}}<br>
		<br>
			Your account has now been created for the DBS Electronic Timesheet System.<br><br>
		<br>
			You can log in by visiting <a href"{{\URL::to('/')}}">{{\URL::to('/')}}</a> and using the below User Name and Password:<br>
		<br>
		User Name: <a href="mailto:{{$user->email}}">{{$user->email}}</a><br>
		Password: {{$password}}<br>
		<br>
		To setup quick access to the system, please follow the instructions related to your device below or in the PDF attached to this email.<br>
		<br>
		For any further information or assistance please call the office on 01622 715919 
		<br>
		<br>
		Thank you
		<br>
		<br>
		<img src="{{\URL::to('/email/ScreenShot1.png')}}"><br>
		<br>
		<img src="{{\URL::to('/email/ScreenShot2.png')}}"><br>
		<br>
		<img src="{{\URL::to('/email/ScreenShot3.png')}}"><br>
		<br>
		<br>
		<a href="{{\URL::to('/email/instructions.pdf')}}">instructions.pdf</a><br>
		<br>
		<br>
		DBS Contracts Ltd
	</body>
</html>

