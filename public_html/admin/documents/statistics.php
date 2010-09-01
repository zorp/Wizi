<?php
require_once("../util/statistics.php");

$statistics = new statistics( $dba );
$stats = $statistics->getPermanentStats( $id );
$startStatisticData = $statistics->getStartStatisticData();
$usersOnline = $statistics->getUsersOnline( $id );
$hitsOverview = $statistics->getVisitsOverview( $id );
if( !$usersOnline ) $usersOnline = 0;

?>
<form name="my_form" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">
      Statistics for "<?=$document->name?>" <span class="alert_message"><?=$message?></span>
    </td>
  </tr>   
  <tr> 
    <td align="center">&nbsp;</td>
  </tr> 
  <tr> 
      <td>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
            <tr>
                <td class="tdpadtext">&nbsp;</td>
                <td class="tdpadtext"><img src="../graphics/transp.gif" height="20" width="250"></td>
            </tr>	
            <tr>
                <td class="tdpadtext">Total visits to this page :</td>
                <td class="alert_message">[<?=$stats["visits"]?>]</td>
            </tr>	
            <tr>
                <td class="tdpadtext">Last visit to this page:</td>
                <td class="alert_message">[<?=$stats["last_visit"]?>]</td>
            </tr>	
            <tr>
                <td class="tdpadtext">Current number of visitors for this page:</td>
                <td class="alert_message">[<?=$usersOnline?>]</td>
            </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext">The last 30 days</td>
                <td class="alert_message">&nbsp;</td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?php
                  $n = count( $hitsOverview );

                  //find the highest total
                  for( $i = 0; $i < $n; $i++ ) if( $hitsOverview[$i]["total"] > $max ) $max = $hitsOverview[$i]["total"];
                  $graphHeight = 200;

                  //check for division by cero
                  if( $max ) $unit = $graphHeight / $max;
                  else $unit = 1;

                  //values for left column
                  $m = 11;
                ?>
                <table cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td align="right" valign="bottom">
                      <table cellpadding="0" cellspacing="0" border="0">
                        <?for( $i = 0; $i < $m; $i++ ):?>
                        <tr>
                          <td class="plainText" style="color:#666666;font-size:9px;font-style:verdana" align="right"> 
                            &nbsp;
                          </td>
                          <td>
                            <?if( $i !=( $m - 1 ) ):?>
                            <img src="../graphics/transp.gif" width="10" height="2"><br>
                            <?endif?>
                            <img src="../graphics/dark_gray.gif" width="5" height="1">
                          </td>
                        </tr>
                        <?endfor?>
                      </table>
                    </td>
                    <td valign="bottom">
                      <table cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #666666">
                        <tr>
                          <?for( $i = 0; $i < $n; $i++ ):?>
                            <td valign="bottom" class="plainText" style="font-size:9px" align="center"><?=$hitsOverview[$i]["total"]?><br><img src="../graphics/<?=( $i%2==0 )?'':'dark_'?>orange.gif" width="15" height="<?=( ($hitsOverview[$i]["total"] ) * $unit )?>" alt="<?=$hitsOverview[$i]["date"]?>"></td>
                          <?endfor?>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>	
            <tr>
                <td class="tdpadtext">&nbsp;</td>
                <td class="tdpadtext">&nbsp;</td>
            </tr>	
          </table>
  </tr>
  <tr> 
    <td class="tdpadtext">
        <?if( $referer ):?>
          <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
        <?endif?>
    </td>
  <tr>
  <tr> 
    <td ><img src="../graphics/transp.gif" height="15"></td>
  <tr>
</table>
</form>
