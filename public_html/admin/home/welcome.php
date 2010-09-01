<?
require_once("../util/browser_check.php");
$br = new Browser;
//echo "$br->Platform, $br->Name version $br->Version";
?>
<? if(!$SERVER_SOFTWARE) $SERVER_SOFTWARE = $_SERVER["SERVER_SOFTWARE"]?>
<? if(!$HTTP_USER_AGENT) $HTTP_USER_AGENT = $_SERVER["HTTP_USER_AGENT"]?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td colspan="2"><img src="../graphics/transp.gif" height="20"></td>
  </tr>
  <tr>
    <td class="header">Welcome <?=($user->full_name)? $user->full_name: $user->name?></td>
		<td align="right" style="padding-right:10px;"><img src="../graphics/company.png" border="0"></td>
	</tr>
  <tr> 
    <td colspan="2">
			
			<table width="600" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td height="20" colspan="2" align="left"><hr class="intropage"></td>
	</tr>
		<tr>
		<td width="65"><a href="http://www.wizi.dk/manual/Wizi_manual.pdf"><img src="../../admin/graphics/largeicons/webpdf_icon.png" border="0" align="left" hspace="5"></a></td>
		<td width="535" class="intropage"><span class="titl">&#8226; Open the Wizi Manual</span><br>
		The manual is located on Wizi.dk's server, and automaticaly updated.</td>
	</tr>
	<tr>
		<td height="20" colspan="2" align="left"><hr class="intropage"></td>
	</tr>
		<tr>
		<td width="65"><a href="../../index.php?frontdev=1"><img src="../../admin/graphics/largeicons/webeditpage_icon.png" border="0" align="left" hspace="5"></a></td>
		<td width="535" class="intropage"><span class="titl">&#8226; Open website with Edit functionality</span><br>
		You can open your website with edit functionality, and browse directly to the page you want to edit (A small orange dot is visible, click the icon to edit the page).</td>
	</tr>
	<tr>
		<td height="20" colspan="2" align="left"><hr class="intropage"></td>
	</tr>
	<tr>
		<td width="65"><a href="../../rss.php"><img src="../../admin/graphics/largeicons/webrss_icon.png" border="0" align="left" hspace="5"></a></td>
		<td width="535" class="intropage"><span class="titl">&#8226; Newsfeed for this website</span><br>
		News published on your website is automaticaly generated as XML feeds. Other websites can <br>
use these to show your news. Also the feeds can be read in a NewsReader program.</td>
	</tr>
	<tr>
		<td height="20" colspan="2" align="left"><hr class="intropage"></td>
	</tr>
</table>
			
			
			
			
			
			
<!--			<table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
				<tr>
					<td class="plainText" style="padding-left: 10px; padding-top: 10px;"><b>Wizi tools and tips:</b></td>
				</tr>
				<tr>
					<td class="plainText" style="padding-left: 10px; padding-right: 20px;"><br><a href="http://www.wizi.dk/manual/Wizi_manual.pdf" target="_blank" title="Wizi Manual" class="links"><img src="../graphics/pdf_icon.gif" border="0" align="left" hspace="5">Open the Wizi Manual</a><br>The manual is located on Wizi.dk's server, and automaticaly updated.</td>
				</tr>
				<tr>
					<td class="plainText" style="padding-left: 10px; padding-right: 20px;"><br><a href="../../index.php?frontdev=1" target="_blank" class="links" title="Open website with Edit functionality"><img src="../graphics/edit_icon.gif" border="0" align="left" hspace="5"></a>You can <a href="../../index.php?frontdev=1" target="_blank" class="links" title="Open website with Edit functionality">open your website with edit functionality</a>, and browse directly to the page you want to edit (A small orange dot is visible, click the icon to edit the page).</td>
				</tr>
				<tr>
					<td class="plainText" style="padding-left: 10px; padding-right: 25px; padding-bottom: 10px;"><br /><a href="../../rss.php" target="_blank" title="Newsfeed for this website" class="links"><img src="../graphics/rss_icon.gif" border="0" align="left" hspace="5"></a>News published on your website is automaticaly generated as XML feeds. Other websites can use these to show your news. Also the feeds can be read in a NewsReader program. <a href="../../rss.php" target="_blank" title="Newsfeed for this website" class="links">Click here</a> to view the XML feeds for your website.</td>
				</tr>
			</table>-->
    </td>
  </tr>
  <tr> 
    <td colspan="2"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td colspan="2">
			<table class="systeminfo" width="100%" cellpadding="3" cellspacing="0" border="0">
				<tr>
					<td class="plainText" style="padding-left: 10px; padding-top: 5px;"><strong>System details</strong></td>
				</tr>
				<tr>
					<td class="plainText" style="padding-left: 10px;">Wizi CMS version: 5.0</td>
				</tr>
				<tr>
					<td class="plainText" style="padding-left: 10px;">Server: <?=$SERVER_SOFTWARE?></td>
				</tr>
				<tr>
					<td class="plainText" style="padding-left: 10px;">Client Platform: <?=$br->Platform?> (Recommended: Windows)</td>
				</tr>
				<tr>
					<td class="plainText" style="padding-left: 10px; padding-bottom: 10px;">Client Browser: <?=$br->Name?> version: <?=$br->Version?> (Recommended: MSIE 5.5 or higher)</td>
				</tr>
			</table>
    </td>
  </tr>
  <tr> 
		<td colspan="2"><img src="../graphics/transp.gif" height="15" width="15"></td>
  </tr>
</table>
