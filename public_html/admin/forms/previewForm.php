<!--<link href="../../styles/styles.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
<style>
LI,DT,DD,ADDRESS,PRE,TD,TR,TABLE
{	
	line-height: normal;
}
</style>-->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td bgcolor="#FFFFFF" colspan="3"><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Preview of form: '<?=$forms->name?>'</td>
  </tr> 
    <td bgcolor="#FFFFFF" class="save_message"><?=$msg?></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFFF" class="plainText">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
        <tr> <td colspan="3"><img src="../graphics/transp.gif" height="20"></td> </tr>
        <tr>
          <td width="5%">&nbsp;</td>
          <td>
              <table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                      <td class="tdpadtext">
                        <?$forms->fields = &$fields;?>
                        <?=$forms->render( $fields->getFields() )?>
                      </td>
                  </tr>
              </table>
          </td>
          <td width="5%">&nbsp;</td>
        </tr>
        <tr> <td colspan="3"><img src="../graphics/transp.gif" height="20"></td> </tr>
      </table>
    </td>
  </tr>
</table>
