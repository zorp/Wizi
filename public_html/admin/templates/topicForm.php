<?
  if( $choosenIcon || $_POST["choosenIcon"] )
  {
    if( !$choosenIcon ) $choosenIcon = $_POST["choosenIcon"];
    if( !$choosenIconName ) $choosenIconName = $_POST["choosenIconName"];
    if( !$choosenIconFormat ) $choosenIconFormat = $_POST["choosenIconFormat"];
    $topic->iconId= $choosenIcon;
    $topic->iconName = $choosenIconName;
    $topic->iconFormat = $choosenIconFormat;
  }
?>
<html>
    <head>
        <title><?=$title?></title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
        <script language="javascript">
          function clearIconField()
          {
            document.tree.iconId.value ='';
            document.tree.iconFormat.value='';
            document.tree.iconName.value ='';
          }
        </script>
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
           <form name="tree" method="post" action="<?=$PHP_SELF?>">
           <input type="hidden" name="id" value="<?=$topic->id?>">
            <input type="hidden" name="toggle">
            <input type="hidden" name="choosenIcon">
            <input type="hidden" name="choosenIconName">
            <input type="hidden" name="choosenIconFormat">

            <table cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td><img src="../graphics/transp.gif"></td>
                    <td><img src="../graphics/horisontal_button/left_selected.gif"></td>
                    <td class="faneblad_selected"><?=$title?></td>
                    <td><img src="../graphics/horisontal_button/right_selected.gif"></td>
                  </tr>
            </table>
        

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content" valign="top">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr> 
                        <td bgcolor="#FFFFFF" colspan="3"><img src="../graphics/transp.gif" height="20"></td>
                      <tr> 
                      <tr>
                        <td class="header"><?=$title?></td>
                      <tr> 
                        <td bgcolor="#FFFFFF"><img src="../graphics/transp.gif" height="15"></td>
                      <tr>
                      <tr> 
                        <td valign="top" bgcolor="#FFFFFF" class="plainText">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                                <tr>
                                    <td valign="top">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                              <td class="tdpadtext">Name</td>
                                            </tr>
                                            <tr>
                                              <td class="tdpadtext"><input type="text" name="name" class="input"  value="<?=$topic->name?>"></td>
                                            </tr>
                                            <tr>
                                                <td class="tdpadtext" valign="top">Description</td>
                                            </tr>
                                                <td class="tdpadtext"><textarea name="description" rows="4" cols="53" class="input"><?=$topic->description?></textarea></td>
                                            </tr>
                                            <tr>
                                                <td class="tdpadtext" valign="top">Icon</td>
                                            </tr>
                                            </tr>
                                                <td class="tdpadtext">
                                                <input type="hidden" name="iconId" value="<?=$topic->iconId?>">
                                                <input type="hidden" name="iconFormat" value="<?=$topic->iconFormat?>">
                                                  <input type="text" name="iconName" readonly="true" class="input" style="width:195"  value="<?=$topic->iconName?>">
                                                  <input type="button" value="Clear" onclick="clearIconField()" class="knap">
                                                </td>
                                            </tr>
                                            <tr>
                                              <td class="plainText"><img src="../graphics/transp.gif" height="15"></td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td valign="top" align="left" class="tdpadtext">
                                      <?require_once("includesMediaTree.php")?>
                                      <br><br>
                                    </td>
                                    <td>
                                      &nbsp;
                                    </td>
                                </tr>
                            </table>
                            <table width="310" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td align="right"><input type="button" value="Cancel" onclick="document.location.href='index.php?pane=topics'" class="knap"> <input type="submit" value="OK" name="submited" class="knap"></td>
                                </tr>
                          </table>
                    </td>
            </tr>
        </table>
        </form>
    </body>
</html>
