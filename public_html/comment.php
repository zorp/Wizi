<?php
$comments = new comments( $dba, $page );

if ($_POST["addcomment"]){
	$comments->name = $_POST["commentname"];
	$comments->email = $_POST["commentemail"];
	$comments->comment = $_POST["commenttext"];

	if (!$comments->name) $nameError = true;
	if (!$comments->comment) $commentError = true;
	
	if (!$nameError && !$commentError){
		$comments->addComment();
		$comments->name = "";
		$comments->email = "";
		$comments->comment = "";
	}else{
		echo "<script type=\"text/javascript\">document.location.href = document.location.href+'#writecomment';</script>";
	}// if else
}//if

$comments->countComments();
$pagecomments = $comments->getComments();
?>

<?php if ($_GET["showcomments"]): ?>
	<p><a href="index.php?page=<?php echo $page; ?>&showcomments=0">Comment<?php echo ($comments->commentNumber > 1)?"s":""; ?> &raquo;</a></p>
	<p id="comments">
		<?php for( $i=0;$i< count($pagecomments); $i++ ): ?>
			<div class="<?=($i%2==0)?"even":"odd"?>">
				<p>Comment by <a href="mailto:<?php echo $pagecomments[$i]["email"]?>"><?php echo $pagecomments[$i]["name"]; ?></a> on <?php echo $pagecomments[$i]["datetime"]; ?></p>
				<p><?php $pagecomments[$i]["comment"]; ?></p>
			</div>
		<?php endfor ?>
		<hr noshade="noshade">
	</p>
	<p>
		<a name="writecomment"></a>
		<p>Write your own comment:<br><br>HTML tags is not permitted.</p>
		<form name="comment" action="<?php echo $_SERVER["PHP_SELF"]; ?>?page=<?php echo $page; ?>&showcomments=1" method="post">
			<input type="hidden" name="addcomment" value="1">
			<p>Your name: <font color="#CC0033">* <?php echo($nameError)?"Please fill in your name":""; ?></font><br>
			<input type="text" name="commentname" value="<?php echo $comments->name; ?>"></p>
			<p>Your email adress:<br>
			<input type="text" name="commentemail" value="<?php echo $comments->email; ?>"></p>
			<p>Your comment: <font color="#CC0033">* <?php echo ($commentError)?"Please fill in your comment":""; ?></font><br>
			<textarea name="commenttext"><?=$comments->comment?></textarea></p>
			<p><input type="submit" name="submit" value="Add comment"></p>
		</form>
	</p>
<?php else: ?>
	<p><a href="index.php?page=<?php echo $page; ?>&showcomments=1">Comment<?php echo ($comments->commentNumber > 1)?"s":""; ?> <?php echo ($comments->commentNumber)?"(".$comments->commentNumber.")":"";?></a></p>
<?php endif ?>