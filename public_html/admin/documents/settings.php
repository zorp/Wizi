<?php
require_once("../util/date_widget.php");
require_once("../util/document.php");
require_once("../util/topics.php");

$document  = new document( $dba, $id );
$topics    = new topics($dba);
$list      = $topics ->getTopics();

if( $newsCheck || $_POST["newsCheck"] ) $editproperties = 1;

if( $editproperties || $_POST["editproperties"] )
{
	if( !$nav )                $nav = $_POST["nav"];
	if( !$news )               $news = $_POST["news"];
	if( !$day_fromnews )       $day_fromnews = $_POST["day_fromnews"];
	if( !$month_fromnews )     $month_fromnews = $_POST["month_fromnews"];
	if( !$year_fromnews )      $year_fromnews = $_POST["year_fromnews"];
	
	if( !$day_tonews )         $day_tonews = $_POST["day_tonews"];
	if( !$month_tonews )       $month_tonews = $_POST["month_tonews"];
	if( !$year_tonews )        $year_tonews = $_POST["year_tonews"];
	
	if( !$publishSchedule )    $publishSchedule = $_POST["publishSchedule"];
	if( !$day_publish )        $day_publish = $_POST["day_publish"];
	if( !$month_publish )      $month_publish = $_POST["month_publish"];
	if( !$year_publish )       $year_publish = $_POST["year_publish"];
	
	if( !$unpublishSchedule )  $unpublishSchedule = $_POST["unpublishSchedule"];
	if( !$day_unpublish )      $day_unpublish = $_POST["day_unpublish"];
	if( !$month_unpublish )    $month_unpublish = $_POST["month_unpublish"];
	if( !$year_unpublish )     $year_unpublish = $_POST["year_unpublish"];
	
	if( !$publish )            $publish = $_POST["publish"];
	if( !$topic )              $topic = $_POST["topic"];
	
	if( !$showcomment )        $showcomment = $_POST["showcomment"];

	$document->setNews( $news );
	$document->setTopic( $topic );
	$document->setNav( $nav );
	$document->setComment( $showcomment );

	if( $day_fromnews )
	{
		$document->setFromnews( $day_fromnews, $month_fromnews, $year_fromnews );
		$document->setTonews( $day_tonews, $month_tonews, $year_tonews );
	}
	//the order of this statements is important
	if( $publishSchedule   )
	{
		$document->setPublishDate( $day_publish, $month_publish, $year_publish );
	}
	else
	{
		$document->setPublishDate();
	}
	if( $unpublishSchedule ) 
	{
		$document->setUnPublishDate( $day_unpublish, $month_unpublish, $year_unpublish );
	}
	else
	{
		$document->setUnPublishDate();
	}
	
	$document->setPublish( $publish );
	$message = '<br>Your last save was on '.date("H:i").' <img src="../graphics/yes.gif">';
}

$document->loadProperties();
$publishDate = new date_widget("publish");

if ( $document->publishDate["y"] ) 
{
	$publishDate->setDate( $document->publishDate["d"], $document->publishDate["m"], $document->publishDate["y"] );
}

$unpublishDate = new date_widget("unpublish");

if( $document->unpublishDate["y"] ) 
{
	$unpublishDate->setDate( $document->unpublishDate["d"], $document->unpublishDate["m"], $document->unpublishDate["y"] );
}

//if( $document->news == 'y' )
//{
	$fromNews = new date_widget("fromnews");
	$fromNews->setDate( $document->fromnews["d"], $document->fromnews["m"], $document->fromnews["y"] );
	$toNews   = new date_widget("tonews");
	$toNews->setDate( $document->tonews["d"], $document->tonews["m"], $document->tonews["y"] );
//}

if (!$document->showcomment) $document->showcomment = "n";
?>
<script language="javascript">
var hideStatus = '<?=($document->news == 'y')?'show':'hidden';?>';

function updateCheckbox(state)
{
	if (hideStatus == "hidden")
	{
		document.my_form.newsCheck.value= 0;
		document.getElementById('newssetting').style.display = "block";
		hideStatus = "show"
	}
	else
	{
		document.my_form.newsCheck.value= 1;
		document.getElementById('newssetting').style.display = "none";
		hideStatus = "hidden"
	}
}
</script>
<form name="my_form" action="<?=$PHP_SELF?>" method="post">
	<input type="hidden" name="id" value="<?=$document->id?>">
	<input type="hidden" name="pane" value="settings">
	<input type="hidden" name="referer" value="<?=$referer?>">
	<input type="hidden" name="newsCheck">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr> 
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr> 
	<tr>
		<td class="header">
			Properties for document "<?=$document->name?>"
		</td>
	</tr>
	<?if (message):?>
	<tr>
		<td class="save_message"><?=$message?></td>
	</tr>
	<?endif?>
	<tr> 
		<td align="center">&nbsp;</td>
	</tr> 
	<tr> 
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
				<tr>
					<td class="tdpadtext">
						<input type="checkbox" name="nav" class="plainText" <?=( $document->nav )? "checked":""?> >Display document on navigation
					</td>
				</tr>
				<tr>
					<td class="tdpadtext">
						<input type="checkbox" name="showcomment" class="plainText" <?=( $document->showcomment != 'n' )? "checked":""?> >Show comment field on page <a href="editcomments.php?id=<?=$document->id?>" class="tabelText">(Edit comments)</a>
					</td>
				</tr>
				<?if( $document->id != 1 ):?>
				<tr>
					<td class="tdpadtext">
						<input type="checkbox" onclick="updateCheckbox()" name="news" class="plainText" <?=( $document->news != 'n' )? "checked":""?> >Display document as "News"
					</td>
				</tr>
				<tr name="newssetting" id="newssetting" style="display: <?=($document->news == 'y')?'block':'none';?>;">
					<td class="tdpadtext"  valign="top">
						<table cellpadding="0" cellspacing="0" border="0" style="margin-left:132px">
							<? if ($list):?>
							<tr>
								<td class="tdpadtext" align="right">As topic</td>
								<td class="tdpadtext">
									<select name="topic" style="width:135px">
										<?for( $i = 0; $i < count( $list ); $i++ ):?>
											<option value="<?=$list[$i]["id"]?>" <?=( $list[$i]["id"] == $document->topic )?"selected":""?>><?=$list[$i]["name"]?></option>
										<?endfor?>
									</select>
								</td>
							</tr>
							<? endif?>
							<tr>
								<td class="tdpadtext" align="right">From</td>
								<td class="tdpadtext"><?=$fromNews->render();?></td>
							</tr>
							<tr>
								<td class="tdpadtext" align="right">Until</td>
								<td class="tdpadtext"><?=$toNews->render();?></td>
							</tr>
						</table>
					</td>
				</tr>
				<?endif?>
				<tr>
					<td class="tdpadtext">
						<input type="checkbox" name="publish" class="plainText" <?=( $document->publish )?"checked":""?> >Publish the document
					</td>
				</tr>
				<tr>
					<td>
						<table  cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="tdpadtext" width="206">
									<input type="checkbox" name="publishSchedule" class="plainText" <?=( $document->publishDate["y"] )?"checked":""?>>Publish on date [ d.m.y ]
								</td>
								<td>
									<?=$publishDate->render()?>
								</td>
							</tr>
							<tr>
								<td class="tdpadtext" width="206">
									<input type="checkbox" name="unpublishSchedule" class="plainText" <?=( $document->unpublishDate["y"] )?"checked":""?>>Unpublish on date [ d.m.y ]
								</td>
								<td>
									<?=$unpublishDate->render()?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr> 
					<td ><img src="../graphics/transp.gif" height="15"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<br>
			<table width="349" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="tdpadtext">
						<?if( $referer ):?>
							<a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
						<?else:?>
							&nbsp;
						<?endif?>
					</td>
					<td  align="right">
						<input type="submit" value="Cancel" name="cancel" class="knapred">
						<input type="submit" value="Save" name="editproperties" class="knapgreen">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr> 
		<td ><img src="../graphics/transp.gif" height="15"></td>
	<tr>
</table>
</form>
