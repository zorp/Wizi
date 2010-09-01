<?
require_once("../util/dba.php");
require_once("../util/user.php");
require_once("../util/document.php");
require_once("../util/comment.php");

session_start();
$dba    = new dba();
$prefix = $dba->getPrefix();
$user   = new user( $dba );
if( !$user->isLogged() ) die("<script language=\"JavaScript\">top.document.location.href='../log.php';</script>");

if(!$id) $id = $_GET["id"];
if(!$id) $id = $_POST["id"];
if(!$id) die("Parameter spected id ");

if(!$edit) $edit = $_GET["edit"];
if(!$edit) $edit = $_POST["edit"];
if(!$delete) $delete = $_GET["delete"];
if(!$delete) $delete = $_POST["delete"];

if (!$PHP_SELF) $PHP_SELF = $_SERVER["PHP_SELF"];

$comments = new comments( $dba, $id );
$document  = new document( $dba, $id );

if ($_POST["editid"] && $_POST["submitted"])
{
	$comments->updateComment($_POST["editid"],$_POST["name"],$_POST["email"],$_POST["comment"]);
}

if ( $_GET["delete"] )
{
	$comments->deleteComment( $_GET["delete"] );
}

$document->loadProperties();
$pagecomments = $comments->getComments();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Edit Comments</title>
	<link rel="stylesheet" href="../style/style.css" type="text/css">
	<script language="JavaScript" src="../scripts/global_funcs.js"></script>
</head>

<body class="content_body">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="../graphics/transp.gif"></td>
		<td onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left_selected.gif"></td>
		<td onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>'" style="cursor:hand;" class="faneblad_selected">Edit comments</td>
		<td onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right_selected.gif"></td>
		<td><img src="../graphics/transp.gif" width="4"></td>
	</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td width="1"><img src="../graphics/transp.gif" border="0" width="1" height="350"></td>
		<td class="tdborder_content" valign="top">
			<!--include pane-->
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr> 
					<td><img src="../graphics/transp.gif" height="20"></td>
				</tr> 
				<tr>
					<td class="header">
						Edit comments for document "<?=$document->name?>"
					</td>
				</tr>
				<?if (message):?>
				<tr>
					<td class="save_message"><?=$message?></td>
				</tr>
				<?endif?>
				<tr> 
					<td align="center">&nbsp;</td>
				</tr> 
				<tr> 
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
							<tr>
								<td class="tdpadtext">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<?for( $i=0;$i< count($pagecomments); $i++ ):?>
										<tr>
											<td style="border-top: 1px solid #000000;padding-top:15px;padding-bottom:15px;">
												<?if ($pagecomments[$i]["id"] != $edit):?>
													<strong>Comment by <a href="mailto:<?=$pagecomments[$i]["email"]?>" class="links"><?=$pagecomments[$i]["name"]?></a> on <?=$pagecomments[$i]["datetime"]?></strong>
													<br><br>
													<?=$pagecomments[$i]["comment"]?>
												<?else:?>
													<form name="editcomment" action="<?=$PHP_SELF?>" method="POST">
														Name:<br>
														<input type="text" name="name" value="<?=$pagecomments[$i]["name"]?>" class="text_field"><br>
														Email adress:<br>
														<input type="text" name="email" value="<?=$pagecomments[$i]["email"]?>" class="text_field"><br>
														Comment:<br>
														<br>
														<textarea cols="60" rows="10" name="comment" class="text_area"><?=str_replace("<br>","\r",$pagecomments[$i]["comment"])?></textarea><br><br>
														<input type="hidden" name="editid" value="<?=$pagecomments[$i]["id"]?>">
														<input type="hidden" name="id" value="<?=$id?>">
														<input type="hidden" name="submitted" value="1">
														<input type="submit" name="submit" value="Save comment" class="knapgreen" style="width:125px;">
													</form>
												<?endif?>
											</td>
											<td style="border-top: 1px solid #000000;padding-top:15px;padding-bottom:15px;padding-left:15px;">
												<?if ($pagecomments[$i]["id"] != $edit):?>
													<a href="<?$PHP_SELF?>?edit=<?=$pagecomments[$i]["id"]?>&id=<?=$id?>" class="greenlink">Edit</a> | <a href="javascript:if( confirm('Delete Comment by: <?=$pagecomments[$i]["name"]?>. Comment cannot be restored.') ){document.location.href= '<?$PHP_SELF?>?delete=<?=$pagecomments[$i]["id"]?>&id=<?=$id?>' };" class="redlink">Delete</a>
												<?else:?>
													&nbsp;
												<?endif?>
											</td>
										</tr>
									<?endfor?>
									<?if (!count($pagecomments)):?>
										<br>No comments on page.<br><br>
									<?endif?>
									</table>
									<div height="1" style="border-top: 1px solid #000000;">&nbsp;</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="tdpadtext">
						<br>
						<a href="index.php?id=<?=$id?>&pane=settings"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a><br><br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


</body>
</html>
