<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Printvenlig - <?=$docTitle; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Description" content="<?=$docDescription; ?>"/>
	<meta http-equiv="Keywords" content="<?=$docKeyword; ?>"/>
	<link href="styles/styles.css" rel="stylesheet" rev="stylesheet" type="text/css" />
	<link href="styles/shared.css" rel="stylesheet" rev="stylesheet" type="text/css" />
	<style type="text/css">
		BODY
		{
			background-color:#FFFFFF;
			margin-top:10px;
			margin-left:10px;
		}
	</style>
	<script language="JavaScript" src="scripts/global_frontend.js"></script>
</head>
<body onLoad="SendToPrinter();" text="#000000" link="#000000" vlink="#000000" alink="#000000">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
		<tr>
			<td valign="top">
				<?=$docContent?>
				<?if( $includeTemplate ) require_once( $includeTemplate );?>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td valign="middle" align="center"><a href="javascript:self.close();">Close this window</a></td>
		</tr>
	</table>
</body>
</html>