<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		Dear {{$user->name}}<br>
		<br>
			Welcome and thank you for registering with DBS Contracts.<br>
		<br>
			Your account has now been created and you can log in by using your details below by visiting the following URL: <a href"{{\URL::to('/')}}">{{\URL::to('/')}}</a><br>
		<br>
		User Name: {{$user->email}}<br>
		Password: {{$password}}<br>
		<br>
		To setup quick access to your account follow the instructions related to your device below or in the PDF attached to this email.<br>
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
		For any further information please call the office on 01622 715 919<br>
		<br>
		Thank you from the DBS team.<br>
		<br>
		Confidentiality Notice: This e-mail message, including any attachments, is for the sole use of the intended recipient(s) and may contain confidential and privileged information. Any unauthorized review, use, disclosure or distribution is prohibited. If you are not the intended recipient, please contact the sender by reply e-mail and destroy all copies of the original message.
	</body>
</html>

