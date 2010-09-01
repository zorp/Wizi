<?php
// CREATE DOCTITEL
if (!$docTitle){
	$docTitle = 'MALLING ARKITEKTER';
	if($historyPath) {
		foreach( $historyPath as $key => $value ) {
			if($key > 1) $docTitle.= ' - '.$value;
		}
	
		$docTitle.= ' - '.$docName;
	}
}
else{
	$docTitle.= ' - MALLING ARKITEKTER';
}
?>
<!--
***********************************************************
*                                                         *
* THIS WEBSITE IS BASED ON WIZI CONTENT MANAGEMENT SYSTEM *
*                                                         *
* WIZI is developed and maintained by Verk ApS            *
* Visit http://www.verk.dk for more information.          *
*														  *
***********************************************************
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php echo $docTitle; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Description" content="<?php echo $docDescription; ?>">
	<meta http-equiv="Keywords" content="<?php echo $docKeyword; ?>">
	<link href="styles/shared.css" rel="stylesheet" rev="stylesheet" type="text/css">
	<link href="styles/styles.css" rel="stylesheet" rev="stylesheet" type="text/css">
	<script type="text/javascript" src="scripts/global_frontend.js"></script>
</head>
<body>
	<div id="container">
		<div id="header" align="center">
			<a href="index.php"><img src="graphics/malling_top.jpg" border="0" alt="MALLING ARKITEKTER"></a>
		</div>
			<div id="navigation">
            	<ul>
				<?php for ($i=0,$n=count($topMenu);$i<$n;$i++) : ?>
					<li <?php print ($topActive == $topMenu[$i]['id'])?'class="active"':''; ?>>
                    	<a href="?page=<?php print $topMenu[$i]["id"] ?>"><?php print $topMenu[$i]["name"] ?></a>
                    </li>
				<?php endfor; ?>
                </ul>
         	</div>
         	
            <div id="subnavigation">
                <ul>
                <?php for ($i=0,$n=count($siblingMenu);$i<$n;$i++) : ?>
                  	<li <?php print ($subActive == $siblingMenu[$i]['id'])?'class="active"':''; ?>>
						<a href="?page=<?php print $siblingMenu[$i]["id"] ?>"><?php if ($page != 1) print $siblingMenu[$i]["name"] ?></a>
                    </li>
                <?php endfor; ?>
                </ul>
            </div>
		
		<div id="middlebox">
			<div id="3">
				<?php
					echo $docContent;
					if ($showcomment == 'y') require_once( "comment.php" );
					if( $includeTemplate ) require_once( $includeTemplate );
					echo $stats;
				?>
			</div>
		</div>
		<div id="footer">MALLING ARKITEKTER maa/par aps | Gl&uuml;ckstadtsvej 2/1 sal tv | DK 2100 K&oslash;benhavn &Oslash;  |<br>Phone +45 35 24 16 15 | Fax +45 35 24 16 18 | <a href="mailto:mail@mallingarkitekter.dk">mail@mallingarkitekter.dk</a></div>
	</div>
</body>
</html>