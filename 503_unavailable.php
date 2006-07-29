<?php header($_SERVER['REQUEST_PROTOCOL']." 503 Service Unavailable"); ?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>503 Service Unavailable</title>
</head><body>
<h1>Service Unavailable</h1>
<p>This site is currently unavailable.</p>
<hr>
<?=$_SERVER['SERVER_SIGNATURE']?>
</body></html>
