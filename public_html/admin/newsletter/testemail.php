<?
if(!$usersubmited) $usersubmited = $_GET["usersubmited"];
if(!$usersubmited) $usersubmited = $_POST["usersubmited"];

$toadr = $_POST["toadr"];
if (!$toadr) $toadr = $_GET["toadr"];

$data = $newsletter->loadStandardData();
$newsletter->loadProperties();

if ($usersubmited && $toadr)
{
	$newsletterid = $newsletter->id;
	$mailSubject = $newsletter->subject;
	$mailBody = $newsletter->body;
	$mailPlainBody = $newsletter->plainbody;
	

	include("email_template.php");
	
	if (!$mailPlainBody) $mailPlainBody = 'This newsletter is sent formatted with HTML. Your email client does not support showing HTML formatted emails.';
	
	$to_name = $toadr;
	$to_email = $toadr;
	$from_name = $data[0];
	$from_email = $data[1];
	$bounce_email = $data[2];
	if (!$bounce_email) $bounce_email = from_email;
	$subject = $mailSubject;
	$body_simple = $mailPlainBody;
	$body_plain = $mailPlainBody;
	$body_html = $htmlbody;
		
		api_email($to_name, $to_email, $adr_bcc, $from_name, $from_email, $subject, $body_simple, $body_plain, $body_html, $bounce_email);
		$mailSent = true;
}
?>
<form enctype="multipart/form-data" name="my_form" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$newsletter->id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header"><?if (!$mailSent):?>Test if the newsletter is correct<?else:?>The Newsletter has been sent to <?=$toadr?><?endif?></td>
  </tr> 
  <tr>
    <td align="center" class="alert_message">&nbsp;</td>
  </tr> 
    <td align="left" class="tdpadtext"><?if (!$mailSent):?>E-mail adress to send the test to: <input type="text" name="toadr" value=""><?else:?>&nbsp;<?endif?></td>
  <tr>
  <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="left" width="310" style="padding-top: 10px;padding-left:10px;"><?if (!$mailSent):?><input type="submit" value="SEND" style="background-color:green;" name="usersubmited" class="knap"><?else:?><input type="button" value="Ok" onclick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=settings'" class="knap"><?endif?></td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  <tr>
</table>
</form>