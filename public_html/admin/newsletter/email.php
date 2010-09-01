<?
require_once("../util/forms.php");
	
if( !$formid )$formid		 = $_GET['selectedForm'];
if( !$formid )$formid		 = $_POST['selectedForm'];
if( !$formid )$formid		 = false;
if( !$antal ) $antal = $_POST['antal'];
if( !$antal ) $antal = $_GET['antal'];
if( !$antal ) $antal = 'all';

if( !$index ) $index = $_POST['index'];
if( !$index ) $index = $_GET['index'];
if( !$index ) $index = 0;

$forms = new forms( $dba );
$formList  = $forms->getForms();
$n = count( $formList );

if ($formid)
{
	$forms->form( $formid );
	$dbrecords = $forms->getFormData( $index, $antal );
	
	if(!$usersubmited) $usersubmited = $_GET["usersubmited"];
	if(!$usersubmited) $usersubmited = $_POST["usersubmited"];
	
	$newsletter->loadProperties();
	$data = $newsletter->loadStandardData();
}
if ($usersubmited)
{
	$newsletterid = $newsletter->id;
	$mailSubject = $newsletter->subject;
	$mailBody = $newsletter->body;
	$mailPlainBody = $newsletter->plainbody;
	
	if (count($dbrecords))
	{
		$tempcount = 0;
		$adrBcc = "";
		foreach( $dbrecords as $record )
		{
			for( $j = 0; $j < count( $record ); $j++ )
			{
				if ($record[$j]['fieldtype'] == 'Mail field')
				{
					//if ($tempcount != 0 ) $adrBcc.= ";";
					//$adrBcc.= $record[$j]['fieldvalue'];
					$recievers[$tempcount] = $record[$j]['fieldvalue'];
					$tempcount++;
				}
			}
		}
	}
	
	//echo "<xmp>";
	//print_r($recievers);
	//echo "</xmp>";

	include("email_template.php");
	
	if (!$mailPlainBody) $mailPlainBody = 'This newsletter is sent formatted with HTML. Your email client does not support showing HTML formatted emails.';
	
	$from_name = $data[0];
	if (!$from_name) $from_name = "WIZI Content Management System";
	$from_email = $data[1];
	if (!$from_email) $from_email = "newsletter@wizi.dk";
	$bounce_email = $data[2];
	if (!$bounce_email) $bounce_email = from_email;
	$subject = $mailSubject;
	$body_simple = $mailPlainBody;
	$body_plain = $mailPlainBody;
	$body_html = $htmlbody;
	
	for( $i = 0; $i < count( $recievers ); $i++ )
	{
		$to_name = $recievers[$i];
		$to_email = $recievers[$i];
		api_email($to_name, $to_email, $adrBcc, $from_name, $from_email, $subject, $body_simple, $body_plain, $body_html, $bounce_email);
		$mailSent = true;
		//echo $recievers[$i]."<br>";
	}
	
	$newsletter->setStatus();
}
?>
<form enctype="multipart/form-data" name="my_form" action="<?=$_SERVER["PHP_SELF"]?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$newsletter->id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr>
	<tr> 
    <td class="tabelText">
			Please select the list you want to send to:<br /><br /><select style="width:200px" name="selectedForm" onchange="document.my_form.submit();">
				<option value="">Select Mailinglist</option>
				<? for( $i = 0; $i < $n; $i++ ):?>
					<? if ($formList[$i]["action_type"] == "newsletter"):?>
						<option value="<?=$formList[$i]["id"]?>" <?=($formid == $formList[$i]["id"])?"selected":""?>><?=$formList[$i]["name"]?></option>
					<? endif ?>
				<? endfor?>
			</select>
		</td>
  </tr>
	<tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header"><? if (!$mailSent):?>By clicking <font color="green">yes</font> the newsletter is sent to everyone on the below list<? else:?>The Newsletter has been sent to all recipients<? endif?></td>
  </tr> 
	<? if( $newsletter->status ):?>
	<tr>
    <td class="alert_message">This newsletter has been email on: <?=$newsletter->status?></td>
  </tr>
	<? endif?>
  <tr>
    <td align="center" class="alert_message">&nbsp;</td>
  </tr>
	<tr>
    <td>
			<? if (count($dbrecords) && !$mailSent):?>
				<table width="100%">
				<? foreach( $dbrecords as $record):?>
					<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
						<? for( $j = 0; $j < count( $record ); $j++ ):?>
							<? if ($record[$j]['fieldtype'] == "Mail field"): ?><td class="tabelText"><?=$record[$j]['fieldvalue']?></td><? endif ?>
						<? endfor?>
					</tr>
					<? $i++?>
	      <? endforeach?>
				</table>	
			<? else:?>
				&nbsp;
			<? endif?>
    </td>
   </tr>
	 <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="left" width="310" style="padding-top: 10px;padding-left:10px;"><? if (!$mailSent):?><input type="button" value="NO" style="background-color:red;" onclick="document.location.href='<?=$_SERVER["PHP_SELF"]?>?id=<?=$id?>&pane=settings'" class="knap"> 
	<input type="submit" value="YES" style="background-color:green;" name="usersubmited" class="knap"><? else:?><input type="button" value="Ok" onclick="document.location.href='<?=$_SERVER["PHP_SELF"]?>?id=<?=$id?>&pane=settings'" class="knapgreen"><? endif?></td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  <tr>
</table>
</form>
<?
//echo "<xmp>";
//echo print_r($record);
//echo "</xmp>";
?>