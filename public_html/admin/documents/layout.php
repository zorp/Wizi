<?php
require_once('../util/dba.php');
require_once('../util/layout.php');
require_once('../util/forms.php');
require_once('../util/fields.php');
require_once('../util/date_widget.php');
require_once('../util/postcode_widget.php');
require_once('../util/country_widget.php');

$dba       = new dba();
if( ! $id ) $id = $_GET["id"];
if( ! $id ) $id = $_POST["id"];
if( ! $id ) $id = 1;

$layout    = new layout( $dba, $id, true ); 
$layouts   = $layout->getLayouts();

if( $selectedLayout || $_POST["selectedLayout"] )
{
	if( !$selectedLayout ) $selectedLayout = $_POST["selectedLayout"];
	$document->chooseLayout( $selectedLayout );
}

$selectedLayout = $document->layout;
if( !$selectedLayout ) $selectedLayout = "single.TMP1";

$e = explode(".", $selectedLayout );
$imgName = "../../layouts/". $e[0] .".gif";
?>
<script language="javascript">
	function changeLayout()
	{
		document.tree.submit();
	}
</script>
<style>
	.tmp_cell
	{
		border:1px solid #cdcdcd;
	}
</style>
<link href="../../styles/shared.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
<form name="tree" action="<?=$PHP_SELF?>" method="post">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="pane" value="layout">
	<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
		<tr> 
			<td><img src="../graphics/transp.gif" height="20"></td>
		</tr> 
		<tr>
			<td class="header">Layout for document "<?=$document->name?>"</td>
		</tr>
   	<tr> 
			<td align="center" class="alert_message"><?=$message?>&nbsp;</td>
		</tr>
   	<tr class="color1"> 
			<td style="padding-left:10px;padding-top:5px;padding-bottom:5px;" valign="middle">
				<table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%">
					<tr class="color1"> 
						<td valign="middle" class="plainText">
							<b>Choose a layout:</b><br>
							<select name="selectedLayout" onchange="changeLayout()" class="select_list">
								<?for( $i = 0; $i < count( $layouts ); $i++ ):?>
								<?$f = explode( ".",$layouts[$i] )?>
								<option value="<?=$layouts[$i]?>" <?=( $layouts[$i] == $selectedLayout )?"selected":"";?> ><?=$f[0]?></option>
								<?endfor?>
							</select>
						</td>
						<td style="padding-left:10px;" valign="middle"><?if( file_exists( $imgName ) ):?><img src="<?=$imgName?>"><?else:?><img src="../../layouts/noIcon.gif"><?endif?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="tdpadtext" style="padding-right:15px;padding-top:20px" valign="top">
				<?=$layout->buildLayout( $selectedLayout )?>
			</td>
		</tr>
	</table>
</form>
<br />
<table width="310"  cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="tdpadtext">
          <?if( $referer ):?>
            <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
          <?else:?>
            &nbsp;
          <?endif?>
    </td>
  </tr>
</table>
