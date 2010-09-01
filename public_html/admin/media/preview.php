<?php
    /*********************************************************************/
    /*   preview.php                                                     */
    /*   description                                                     */
    /*                                                                   */
    /*********************************************************************/
    /*   comments                                                        */
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   Ronald Jaramillo   -   DATE                                     */
    /*                                                                   */
    /*   V I Z I O N   F A C T O R Y   N E W M E D I A                   */
    /*   Vermundsgade 40C - 2100 København Ø - Danmark                   */
    /*   Tel : +45 39 29  25 11 - Fax: +45 39 29 80 11                   */
    /*   ronald@vizionfactory.dk - www.vizionfactory.dk                  */
    /*                                                                   */
    /*********************************************************************/
  $media->loadProperties();
	$format = $media->format;
	$mediaid = $media->id;
	$medianame = $media->name;
	$mediaalt = $media->alt;
	$mediawidth = $media->width;
	$mediaheight = $media->height;
	
	if ( ereg( 'MSIE ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version) ) { $BROWSER_VER=$log_version[1]; $BROWSER_AGENT='Explorer'; } 
?>
Preview<br><br>

<div class="plainText">
	<? if ($format == 'gif' || $format == 'jpg' || $format == 'png' ): ?>
		<img src="../../media/<?=$mediaid?>.<?=$format?>" alt="<?=$mediaalt?>" width="<?=($mediawidth > 200)?"200":$mediawidth?>" border="0">
		<? if($mediawidth > 200):?>
			<br><br>
			Picture has been downsampled,<br><a href="../../media/<?=$mediaid?>.<?=$format?>" target="_blank">Click here</a> to view in full size.
		<? endif?>
		<br /><br /><strong>Picture information:</strong><br />
		Width: <?=$media->width?> pixel<br />
		Height: <?=$media->height?> pixel<br />
		File type: <?=$media->format?><br />
		Size: <?=$media->size?> kb
	<? elseif ($format == 'swf'): ?>
		<?
		if ($mediawidth > 200)
		{
			$ratio = $media->width / 200;
	  	$h     = $media->height / $ratio;
			$w = 200;
		}
		else
		{
			$h = $mediaheight;
			$w = $mediawidth;
		}
		?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="<?=$w?>" height="<?=$h?>">
	  <param name="movie" value="../../media/<?=$mediaid?>.<?=$format?>">
	  <param name="quality" value="high">
	  <embed src="../../media/<?=$mediaid?>.<?=$format?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="<?=$w?>" height="<?=$h?>"></embed>
		</object>
		<? if($mediawidth > 200):?>
			<br><br>
			Flash movie has been downsampled,<br><a href="../../media/<?=$mediaid?>.<?=$format?>" target="_blank">Click here</a> to view in full size.
		<? endif?>
		<br /><br /><strong>Flash file information:</strong><br />
		Width: <?=$media->width?> pixel<br />
		Height: <?=$media->height?> pixel<br />
		File type: <?=$media->format?><br />
		Size: <?=$media->size?> kb
	<? else: ?>
		<? if ( $media->isMedia() ): ?>
			<? $filename = "../../media/". $mediaid . ".". $format; ?>
			<? if ($format == "mov" || $br->Platform == "MacIntosh" ):?>
				<OBJECT CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" WIDTH="285" HEIGHT="260"
				CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab">
				<PARAM name="SRC" VALUE="<?=$filename?>">
				<PARAM name="AUTOPLAY" VALUE="false">
				<PARAM name="CONTROLLER" VALUE="true">
				<EMBED SRC="<?=$filename?>" WIDTH="285" HEIGHT="260" AUTOPLAY="false" CONTROLLER="true" PLUGINSPAGE="http://www.apple.com/quicktime/download/">
				</EMBED>
				</OBJECT>
			<? else: ?>
				<OBJECT ID="MediaPlayer"
				classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95"
				CODEBASE="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715"
				standby="Loading Microsoft Windows Media Player components..."
				TYPE="application/x-oleobject" width="240" height="200">
				<PARAM NAME="FileName" VALUE="<?=$filename?>">
				<PARAM NAME="AnimationatStart" VALUE="false">
				<PARAM NAME="TransparentatStart" VALUE="false">
				<PARAM NAME="AutoStart" VALUE="false">
				<PARAM NAME="ShowControls" VALUE="1">
				<Embed TYPE="application/x-mplayer2"
				pluginspage="http://www.microsoft.com/isapi/redir.dll?prd=windows&sbp=mediaplayer&ar=Media&sba=Plugin&"
				SRC="<?=$filename?>"
				Name="MediaPlayer"
				ShowControls="1"
				Width="240"
				Height="200"
				</embed>
				</OBJECT>
			<? endif ?>
		<? else: ?>
			Media can not be shown in the browser window<br>please download and open to preview<br/><br/>
			<a href="../../media/<?=$mediaid?>.<?=$format?>" target="_blank">Click here to open <b><?=$medianame?></b></a>
			<br /><br /><strong>File information:</strong><br />
			File type: <?=$media->format?><br />
			Size: <?=$media->size?> kb
		<? endif ?>
	<? endif ?>
</div>
