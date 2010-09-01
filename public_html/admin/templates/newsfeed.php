<?php
require_once('../util/dba.php');
require_once('../util/newsFeeds.php');

$feed = new newsFeeds( new dba() );

if( !$id ) $id = $_GET["id"];
if( $id ) $feed->newsFeed( $id );

if( $submited || $_POST["submited"] ) 
{
  if( !$id ) $feed->newsFeed( $feed->addFeed() ); 
  if( !$name ) $name = $_POST["name"];
  if( !$url  ) $url  = $_POST["url"];
  if( !$interval ) $interval = $_POST["interval"];
  if( !$displaynumber ) $displaynumber = $_POST["displaynumber"];

  $feed->setName( $name );
  $feed->setUrl( $url );
  $feed->setInterval( $interval );
  $feed->setDisplayNumber( $displaynumber );

  Header("Location:index.php?pane=newsfeeds");
}


$intervals = array( 
              array('value'=>86400,'label'=>'Once a day' ),
              array('value'=>43200,'label'=>'Twice a day' ),
              array('value'=>21600,'label'=>'Every six hours' ),
              array('value'=>10800,'label'=>'Every tree hours' ),
              array('value'=>3600,'label'=>'Every hour' ),
              array('value'=>1800,'label'=>'Every half hour' ),
              array('value'=>900,'label'=>'Every fifteen minutes' )
             );
                  
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
              <td class="faneblad_selected">News feed properties</td>
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
                      <td align="center" class="alert_message"><?=$message?>&nbsp;</td>
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
                                      <td class="tdpadtext"><input type="text" name="name" class="input"  value="<?=$feed->name?>"></td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Url (Must be to an RSS compatible file)</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext"><input type="text" name="url" class="input" value="<?=$feed->url?>"></td>
                                    </tr>

                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="15"></td> </tr>
                                    <tr>
                                        <td class="tdpadtext">Time interval between news feed retrieval</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">
                                        <select name="interval" class="input">
                                          <?for($i=0;$i < count($intervals);$i++):?>
                                            <option value="<?=$intervals[$i]["value"]?>" <?=($intervals[$i]["value"] == $feed->fetch_interval )?'selected':''?> ><?=$intervals[$i]["label"]?></option> 
                                          <?endfor?>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">Maximun number of news to display</td>
                                    </tr>
                                    <tr>
                                        <td class="tdpadtext">
                                        <select name="displaynumber" class="input">
                                          <?for($i=20;$i > 0 ;$i--):?>
                                            <option value="<?=$i?>" <?=($i == $feed->displaynumber )?'selected':''?> ><?=$i?></option> 
                                          <?endfor?>
                                        </select>
                                        </td>
                                    </tr>

                                    <tr> <td class="plainText"><img src="../graphics/transp.gif" height="15"></td> </tr>
                                  </table>
                             </td>
                           </tr>
                          </table>

                          <table width="310" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="right"><input type="button" value="Cancel" onclick="document.location.href='index.php?pane=newsfeeds'" class="knapred"> <input type="submit" value="Save" name="submited" class="knapgreen"></td>
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
