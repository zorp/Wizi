<?php
    require_once("../util/topics.php");
    $topics = new topics($dba);
    $list   = $topics ->getTopics();
    $imageFormats = array("jpg","gif","png");
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	  <?if( !count( $list ) ):?>
	     <tr class="color1" style="height:30px">
            <td class="tabelText" colspan="4" align="center">No topics available</td>
	     <tr> 
	  <?endif?>
    
		<?for( $i = 0; $i< count( $list ); $i++ ):?>
		<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
			<td><a href="editTopic.php?id=<?=$list[$i]["id"]?>" class="tabelText"><?=$list[$i]["name"]?></a></td>
			<td>
        <?if( in_array( $list[$i]["iconFormat"], $imageFormats ) ):?>
        <?
           if( !$list[$i]["iconHeight"] )
           {
	            $img = @GetImageSize( '../../media/'. $list[$i]["iconId"] .'.'. $list[$i]["iconFormat"] );
              $list[$i]["iconWidth"] = $img[0];
              $list[$i]["iconHeight"] = $img[1];
           }

           $ratio = $list[$i]["iconWidth"] / 75;
           if( $ratio )
           {
              $h = $list[$i]["iconHeight"] / $ratio; 
	            echo '<img src="../../media/'.$list[$i]["iconId"].'.'.$list[$i]["iconFormat"].'" width="75" height="'.$h.'">';
            }
            else
            {
              echo '<img src="../../media/'.$list[$i]["iconId"].'.'.$list[$i]["iconFormat"].'">';
            }
        ?>
        <?else:?>
          <a href="editTopic.php?id=<?=$list[$i]["id"]?>" class="tabelText"><?=$list[$i]["description"]?></a>
        <?endif?>
      </td>
			<td><a href="deleteTopic.php?id=<?=$list[$i]["id"]?>" class="tabelText">Delete</a></td>
		</tr>
		<?endfor?>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr>
			<form name="my_name" action="addTopic.php" method="post">
			<td class="small_header"><input type="submit" value="Add Topic" name="addUser" class="knap"></td>
			<td class="small_header">&nbsp;</td>
			<td class="header">&nbsp;</td>
			</form>
		</tr>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	</table>
