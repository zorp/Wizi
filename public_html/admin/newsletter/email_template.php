<?
	$htmlbody = '
		<html>
			<head>
				<title>'.$mailSubject.'</title>
				<link href="http://'.$_SERVER['HTTP_HOST'].'/styles/shared.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
				<link href="http://'.$_SERVER['HTTP_HOST'].'/styles/styles.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
			</head>
			<body>
				'.$mailBody.'
			</body>
		</html>
	';
?>