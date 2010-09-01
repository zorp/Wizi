<?php
require_once('../util/dba.php');
require_once('../util/newsFeeds.php');
require_once('../util/rss_parser.php');
require_once('../util/rdf_parser.php');

$feed = new newsFeeds( new dba() );

$id = $_GET["id"];
if (!$id) $id = $_POST["id"];
if( $id ) 
{
  $feed->newsFeed( $id );
  $news = $feed->fetch();
}
?>
<html>
    <head>
        <title>News feed</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
        <form name="my_form" method="post" action="<?=$PHP_SELF?>">
        <input type="hidden" name="id" value="<?=$id?>">

        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td><img src="../graphics/transp.gif"></td>
              <td><img src="../graphics/horisontal_button/left_selected.gif"></td>
              <td class="faneblad_selected"><?=$feed->name?> news feed</td>
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
                      <td class="header"><?=$feed->name?></td>
                    </tr> 
                    <tr>
                      <td align="center" class="alert_message"><?=$message?>&nbsp;</td>
                    </tr> 
                    <tr> 
                      <td bgcolor="#FFFFFF" class="plainText">

                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                          <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="15"></td> </tr>
                                    <?for($i=0;$i < count( $news["ITEM"] );$i++ ):?>
                                      <tr>
                                          <td>
                                            <a href="<?=$news["ITEM"][$i]["LINK"]?>" target="_blank" class="tdpadtext" style="color:#000000">
                                            <?=$news["ITEM"][$i]["TITLE"]?></a>&nbsp;
                                          </td>
                                      </tr>
                                      <tr>
                                          <td class="plainText" style="padding:10px">
                                            <p style="width:350px"><?=$news["ITEM"][$i]["DESCRIPTION"]?>&nbsp;</p>
                                          </td>
                                      </tr>
                                    <?endfor?>
                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="15"></td> </tr>
                                  </table>
                             </td>
                           </tr>
                         </table>
                          <table width="310" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td style="padding:10px">
                                <a href="index.php?pane=newsfeeds"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
                              </td>
                            </tr>
                          </table>

                         
                      </td>
                  </tr>
               </table>
              </td>
            </tr>
          </table>
      </form>
	      </body>
</html>