<?php
    /*********************************************************************/
    /*   newsletter settings.php                                         */
    /*                                                                   */
    /*********************************************************************/

    if( $savesettings || $_POST["savesettings"] )
    {
        if( !$mailBody ) $mailBody = $_POST["content"];
				if( !$mailPlainBody ) $mailPlainBody = $_POST["plaincontent"];
        if( !$mailSubject ) $mailSubject = $_POST["mailSubject"];

        function translateLinks( $string )
    		{
        	$string  = stripslashes( $string );

        	//translate intern links
        	$pattern = 'href="'."[^\"]*index.php\?";
					$replace = 'href="http://'.$_SERVER["HTTP_HOST"].'/index.php?';
					$string  = ereg_replace( $pattern, $replace, $string );
					
					$pattern = 'href="'."[^\"]*\?";
					$replace = 'href="http://'.$_SERVER["HTTP_HOST"].'/index.php?';
					$string  = ereg_replace( $pattern, $replace, $string );
					
					$pattern2 = 'HREF="'."[^\"]*media.php\?";
    			$replace2 = 'href="http://'.$_SERVER["HTTP_HOST"].'/media.php?';
					$string  = ereg_replace( $pattern2, $replace2, $string );
					
					$pattern2 = 'HREF="'."[^\"]*\?";
    			$replace2 = 'href="http://'.$_SERVER["HTTP_HOST"].'/media.php?';
					$string  = ereg_replace( $pattern2, $replace2, $string );
					
					//translate intern media references
					$pattern = 'src="'."[^\"]*media\/([0-9]*\....)";
					$replace = 'src="http://'.$_SERVER["HTTP_HOST"].'/media/'."\\1";
					
					$string  = ereg_replace( $pattern, $replace, $string );
					
					$pattern = 'VALUE="'."[^\"]*media\/([0-9]*\....)";
					$replace = 'VALUE="http://'.$_SERVER["HTTP_HOST"].'/media/'."\\1";
	
					$string  = ereg_replace( $pattern, $replace, $string );
	        
					return $string;
  		  }
				
				$mailBody = translateLinks( $mailBody );
				$newsletter->setBody( $mailBody );
				$newsletter->setPlainBody( $mailPlainBody );
        $newsletter->setSubject( $mailSubject );

				$message = '<br>Your last save was on '.date("H:i").' <img src="../graphics/yes.gif">';
		
		    echo "<script>parent.parent.treefrmfrm.treefrm.location.href='../newsletter/tree.php';</script>";
    }
    
		$newsletter->loadProperties();
?>
<form enctype="multipart/form-data" name="my_form" action="<?=$_SERVER["PHP_SELF"]?>" method="post">
<input type="hidden" name="id" value="<?=$newsletter->id?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
    	<td colspan="2"><img src="../graphics/transp.gif" height="20"></td>
	</tr>
	  <tr>
    <td colspan="2" class="header"><?=$newsletter->status?>Edit newsletter: "<?=$newsletter->name?>"</td>
  </tr>
	<? if( $newsletter->status ):?>
	<tr>
    <td colspan="2" class="alert_message">This newsletter has been email on the <?=$newsletter->status?></td>
  </tr>
	<? endif?>
	<? if ($message):?>
	<tr>
		<td colspan="2" class="save_message"><?=$message?></td>
	</tr>
	<? endif?>
    <tr>
    	<td colspan="2"><img src="../graphics/transp.gif" height="15"></td> 
  </tr>
    <tr>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
		    <tr>
        	<td class="tdpadtext">Subject</td>
				</tr>
					<td class="tdpadtext"><input type="text" name="mailSubject" class="input" value="<?=$newsletter->subject?>"></td>
	   		</tr>
				<tr>
					<td class="tdpadtext">&nbsp;</td>
				</tr>
				<tr>
        	<td class="tdpadtext">Plain text Content (Will be visible if the reciever can not read HTML emails)</td>
				</tr>
					<td class="tdpadtext">
						<textarea name="plaincontent" class="input" rows="8" style="width:620px;"><? if (!$newsletter->plainbody):?>This newsletter is sent formatted with HTML, your email client does not support displaying the HTML formatted email.<? else:?><?=$newsletter->plainbody?><? endif?></textarea>
					</td>
	   		</tr>
				<tr>
					<td class="tdpadtext">&nbsp;</td>
				</tr>
				<tr>
        	<td class="tdpadtext">HTML content (Will be visible to most recievers)</td>
				</tr>
					<td class="tdpadtext">
					<?
						require_once("editor.php");
					?>
					</td>
	   		</tr>
        <tr>
					<td class="tdpadtext">&nbsp;</td>
				</tr>
		</table>
				<br>
				<table width="620"  cellpadding="0" cellspacing="0" border="0">
					<tr>
						<?if( $referer ):?>
            	<td class="tdpadtext">
	            	<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
            	</td>
						<?endif?>
						<td align="right"><input type="submit" value="Save" name="savesettings" class="knapgreen"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
   		</tr>
</table>
</form>