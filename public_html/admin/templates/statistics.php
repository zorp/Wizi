<?php
require_once("../util/statistics.php");

$statistics = new statistics( $dba );
$stats = $statistics->getPermanentStats( );
$usersOnline = $statistics->getUsersOnline();
if( !$usersOnline ) $usersOnline = 0;
$hitsOverview = $statistics->getVisitsOverview( );
$topVisited = $statistics->getTopVisitedPages();
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
      Site statistics <span class="alert_message"><?=$message?></span>
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
                <td class="tdpadtext">Total visits:</td>
                <td class="tdpadtext"><?=$stats["visits_total"]?></td>
            </tr>
            <!--<tr>
                <td class="tdpadtext">Total sessions ( unique visits ):</td>
                <td class="alert_message">[<?=$stats["unique_visits_total"]?>]</td>
            </tr>	-->
            <tr>
                <td class="tdpadtext">Current number of visitors:</td>
                <td class="tdpadtext"><?=$usersOnline?></td>
            </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext">Visits within the last 30 days</td>
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
                          <td class="plainText" style="color:#666666;font-size:9px;font-style:verdana" align="right">&nbsp; 
                            
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
                            <td valign="bottom" class="plainText" style="font-size:9px;font-style:verdana" align="center"><?=$hitsOverview[$i]["total"]?><br><img src="../graphics/<?=( $i%2==0 )?'':'dark_'?>orange.gif" width="15" height="<?=( ($hitsOverview[$i]["total"] ) * $unit )?>" alt="<?=$hitsOverview[$i]["date"]?>"></td>
                          <?endfor?>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2"><a name="topvisit">Top visited pages</td>
            </tr>	
            <tr>
                <td colspan="2">
                  <table width="100%" cellpadding="3" cellspacing="0" border="0">
                    <?if( !count( $topVisited ) ):?>
                       <tr class="color2" style="height:30px">
                          <td class="tabelText" colspan="2" align="center">No data available</td>
                       <tr> 
                    <?endif?>
                    <?for( $i = 0; $i < count( $topVisited ); $i++ ):?>
	                   <tr class="<?=($i%2==0)?"color1":"color2"?>">
                        <td style="padding-left:20px;">
                          <a href="../documents/index.php?id=<?=$topVisited[$i]["id"]?>&pane=statistics&referer=<?=urlencode( $PHP_SELF ."?pane=statistics#topvisit" )?>" class="plainText" style="text-decoration:none"><?=$topVisited[$i]["name"]?></a>
                        </td>
                        <td class="plainText"><?=$topVisited[$i]["total"]?></td>
                     </tr>
                    <?endfor?>
                  </table>
                </td>
            </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
          </table>
  </tr>
  <tr> 
    <td class="tdpadtext">
      <!--* available data since <?=$startStatisticData?>-->
    </td>
  <tr>
  <tr> 
    <td ><img src="../graphics/transp.gif" height="15"></td>
  <tr>
</table>
</form>
