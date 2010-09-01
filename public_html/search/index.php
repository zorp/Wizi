<h1>Search the website</h1>
<p>Type your keyword(s) below and press search.</p>
<form name="searchform" action="index.php?action=search" method="get">
	<input type="hidden" name="searching" value="1">
	<input type="hidden" name="action" value="search">
	<input type="text" name="query">
	<input type="submit" value="Search" name="search">
</form>
<?php if ($searching || $print) require_once("search/results.php"); ?>