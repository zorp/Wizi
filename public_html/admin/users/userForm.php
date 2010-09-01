<?
if (!$name) $name = $editUser->name;
if (!$full_name) $full_name = $editUser->full_name;
if (!$mail) $mail = $editUser->mail;

?>
<html>
    <head>
        <title><?=$title?></title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
        <form name="my_form" method="post" action="<?=$PHP_SELF?>">
        <input type="hidden" name="id" value="<?=$id?>">
        <input type="hidden" name="referer" value="<?=$referer?>">

        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td><img src="../graphics/transp.gif"></td>
              <td><img src="../graphics/horisontal_button/left_selected.gif"></td>
              <td class="faneblad_selected">Wizi user</td>
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
                    </tr> 
                    <tr>
                      <td class="header"><?=$title?></td>
                    </tr> 
                    <tr>
                      <td align="left" class="alert_message" <?if ($message):?>style="padding-bottom:20px;padding-top:20px;"<?endif?>><?=$message?>&nbsp;</td>
                    </tr> 
                    <tr> 
                      <td bgcolor="#FFFFFF" class="plainText">

                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                          <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td class="tdpadtext">Name</td>
                                    </tr>
                                    <tr>
                                      <td class="tdpadtext"><input type="text" name="name" class="input"  value="<?=$name?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Full name</td>
                                    </tr>
                                        <td class="tdpadtext"><input type="text" name="full_name" class="input" value="<?=$full_name?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Mail</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext"><input type="text" name="mail" class="input" value="<?=$mail?>"></td>
                                    </tr>
                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="20"></td> </tr>
                                    <tr>
                                        <td class="tdpadtext">Password</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext"><input type="password" name="password" class="input"></td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Confirm password</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext"><input type="password" name="confirm_password" class="input"></td>
                                    </tr>
                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="15"></td> </tr>
                                  </table>
                             </td>
                           </tr>
                          </table>

                          <table width="310" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td class="tdpadtext">
                                <?if( $referer ):?>
                                  <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
                                <?endif?>
                              </td>
                              <td align="right"><input type="hidden" name="prevname" class="input"  value="<?=$editUser->name?>"><input type="button" value="Cancel" onclick="document.location.href='index.php'" class="knapred"> <input type="submit" value="OK" name="submited" class="knapgreen"></td>
                            </tr>
                          </table>
                          <br>
                      </td>
                  </tr>
               </table>
              </td>
            </tr>
          </table>
      </form>
    </body>
</html>
