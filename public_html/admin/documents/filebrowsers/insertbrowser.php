<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Insert Internal Link</title>
<script language="JavaScript">
<!--
function resize_iframe()
{

	var height=window.innerWidth;//Firefox
	if (document.body.clientHeight)
	{
		height=document.body.clientHeight;//IE
	}
	//resize the iframe according to the size of the
	//window (all these should be on the same line)
	document.getElementById("tree").style.height=parseInt(height-
	document.getElementById("tree").offsetTop-8)+"px";
}

// this will resize the iframe every
// time you change the size of the window.
window.onresize=resize_iframe; 

//-->
</script>
</head>

<body>
<div style="float:left;width:100%;"><iframe src="<?php echo ($_GET["source"])?$_GET["source"]:'insert_media.php'; ?>" id="tree" width="100%" frameborder="0" onload="resize_iframe();"></iframe></div>
</body>
</html>
