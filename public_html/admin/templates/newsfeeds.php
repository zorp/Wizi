<?php
  require_once('../util/newsFeeds.php');

  if( !$deactivate ) $deactivate = $_GET["deactivate"];
  if( !$activate ) $activate = $_GET["activate"];
  if( !$remove ) $remove = $_GET["remove"];
  if( !$moveDownId ) $moveDownId = $_POST["moveDownId"];
  if( !$moveUpId ) $moveUpId = $_POST["moveUpId"];

  $newsfeed = new newsFeeds( $dba );
  $newsfeed->activate( $activate );
  $newsfeed->deactivate( $deactivate );
  $newsfeed->remove( $remove );
  $newsfeed->moveUp( $moveUpId );
  $newsfeed->moveDown( $moveDownId );
  $feeds = $newsfeed->getFeeds();
?>
  <script language="javascript">
      function removing( id, name )
      {
        if( confirm( 'Are you shure you want to remove '+ name +'?' ) )
        {
          document.location.href = '<?=$PHP_SELF?>?pane=<?=$pane?>&remove='+id;
        }
      }

    function movingUp( id )
    {
        document.my_form.moveUpId.value = id;
        document.my_form.submit();
    }
    function movingDown( id )
    {
        document.my_form.moveDownId.value = id;
        document.my_form.submit();
    }
  </script>
  <form name="my_form" action="<?=$_SERVER["PHP_SELF"]?>" method="post">
    <input type="hidden" name="pane" value="<?=$pane?>">
    <input type="hidden" name="moveDownId">
    <input type="hidden" name="moveUpId">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<tr>
			<td class="header" colspan="5">News feeds (RSS)</td>
		</tr>
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<? if( !count( $feeds ) ):?>
		<tr class="color1">
			<td align="center" class="tabelText" colspan="5">No feeds available</td>
		</tr>
		<? endif?>
		<? for( $i = 0; $i< count( $feeds ); $i++ ):?>
		<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
			<td>
        <a href="fetchfeed.php?id=<?=$feeds[$i]["id"]?>" class="tabelText" title="View feed"><?=$feeds[$i]["name"]?></a>
      </td>
      <td>
					<? if( $i != 0 ):?>
            <a href="#" onclick="movingUp( <?=$feeds[$i]["id"]?> )"><img src="../graphics/pil_up.gif" alt="Move up" border="0"></a>
					<? else:?>
					    	&nbsp;
					<? endif?>
          <? if( $i != ( count( $feeds ) - 1 ) ):?>
                <a href="#" onclick="movingDown( <?=$feeds[$i]["id"]?> )"><img src="../graphics/pil_down.gif" alt="Move down" border="0"></a>
          <? else:?>
                &nbsp;
          <? endif?>
      </td>
			<td>
        <a href="<?=$PHP_SELF?>?pane=<?=$pane?>&<?=( $feeds[$i]["active"] == 'n' )?'activate':'deactivate'?>=<?=$feeds[$i]["id"]?>" class="<?=( $feeds[$i]["active"] == 'n' )?'greenlink':'redlink'?>"><?=( $feeds[$i]["active"] == 'n' )?'Activate':'Deactivate'?></a>
      </td>
			<td>
        <a href="#" onclick="removing(<?=$feeds[$i]["id"]?>,'<?=$feeds[$i]["name"]?>' )" class="redlink">Delete</a>
      </td>
			<td>
        <a href="newsfeed.php?id=<?=$feeds[$i]["id"]?>" class="greenlink">Edit</a>
      </td>
		</tr>
		<? endfor?>
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr class="color1">
			<td align="left" class="tabelText" height="20" colspan="5"><a href="http://www.overskrift.dk" target="_blank" class="links">Browse Danish feeds here</a></td>
		</tr>
		<tr class="color1">
			<td align="left" class="tabelText" height="20" colspan="5"><a href="http://w.moreover.com/categories/category_list_rss.html" target="_blank" class="links">Browse MoreOver RSS feeds</a></td>
		</tr>
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr>
			<form name="my_name" action="addUser.php" method="post">
			<td class="small_header">&nbsp;</td>
			<td class="small_header">&nbsp;</td>
			<td class="small_header">&nbsp;</td>
			<td class="small_header">&nbsp;</td>
			<td class="header">					
					<input type="button" value="Add Feed" name="addFeed" onclick="document.location.href='newsfeed.php'" class="knapgreen">
			</td>
			</form>
		</tr>
		<tr>
			<td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	</table>
  </form>
