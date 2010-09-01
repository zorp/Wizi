<?php
ini_set('session.use_trans_sid', false);

require_once('admin/util/dba.php');
require_once('admin/util/menu.php');
require_once('admin/util/page.php');
require_once('admin/util/endUser.php');
require_once('admin/util/layout.php');
require_once('admin/util/forms.php');
require_once('admin/util/fields.php');
require_once('admin/util/date_widget.php');
require_once('admin/util/postcode_widget.php');
require_once('admin/util/country_widget.php');
require_once('admin/util/comment.php');

require_once('extra_wizi_functions.php');

//start session
session_start();

//don't allow this to be passes as a query parameter
unset( $includeTemplate );

//load variables
if(!$page) $page = $_GET["page"];
if(!$page) $page = $_POST["page"];
if(!is_numeric( $page ) ) $page = 1;
if(!$page) $page = 1;

if(!$action) $action = $_GET["action"];
if(!$action) $action = $_POST["action"];

if(!$referer) $referer = $_GET["referer"];
if(!$referer) $referer = $_POST["referer"];

if(!$searching) $searching = $_GET["searching"];
if(!$searching) $searching = $_POST["searching"];

if(!$print) $print = $_GET["print"];
if(!$print) $print = $_POST["print"];

if( !$query ) $query = $_GET["query"];
if( !$query ) $query = $_POST["query"];

if(!$name     ) $name     = $_POST["name"];
if(!$password ) $password = $_POST["password"];

if(!$frontdev ) $frontdev = $_GET["frontdev"];
if( $frontdev ){
	$_SESSION[ "frontdev" ] = true;
}else{
	$_SESSION[ "frontdev" ] = false;
}//if else

if ($action == 'news'){
	if(!$topicId) $topicId = $_GET["topicId"];
	if(!$topicId) $topicId = $_POST["topicId"];
}//if

//instantiate the objects we need
$dba    = new dba();
$user   = new endUser( $dba );
$myPage = new page( $dba, $page );
$myPage->getProperties();

$layout = new layout( $dba, $page, false, $frontdev );
$layout->layoutPath = "layouts/";
$layout->mediaPath  = "";

//login box flag
$login = $myPage->isLogin();
$authentication = false;
    
//========================= HANDLE ACTIONS =====================//
if( $action == 'authenticate' ){
	if( $user->log( $name, $password ) ){
		$forward4user = $user->getRoleForward(); //Get forward
		if ($forward4user){
			die("<script type=\"text/javascript\">document.location.href='index.php?page=$forward4user'</script>");
		}else{
			die("<script type=\"text/javascript\">document.location.href='index.php?page=$referer'</script>");
		}//if else
	}else{
		die("<script type=\"text/javascript\">document.location.href='index.php?loginerror=1&page=$referer'</script>");
	}//if else
}//if

if( $action == 'authenticate_role' )
{
	if(!$roleId   ) $roleId   = $_POST["roleId"];
	if(!$roleName ) $roleName = $_POST["roleName"];
	if( $myPage->authenticate( $password, $roleId ) ){
		$_SESSION[ "role_". $roleId ] = 1; 
		die("<script type=\"text/javascript\">document.location.href='index.php?page=$referer'</script>");
	}else{
		die("<script type=\"text/javascript\">document.location.href='index.php?action=role_password&loginerror=1&page=$referer&roleId=$roleId&roleName=$roleName'</script>");
	}//if else
}//if

if( $action == 'logoff' ){
	$user->logoff();
	die("<script type=\"text/javascript\">document.location.href='index.php?page=$referer'</script>");
}//if

if( !$action && $myPage->getRole() ){
	if( $myPage->role["constrainId"] == 1 ){ //check if there is a role password
		if( !isset( $_SESSION[ "role_". $myPage->role["roleId"] ] ) ) $action = "role_password"; //check if the user has allready been authenticated for this role
	}//if
	if( $myPage->role["constrainId"] == 2 ){ //check if this is a free registration role
		if( !$user->isLogged() ) $action = "login_required"; //chceck if the user allready has been authenticated for this role
	}//if
	if( $myPage->role["constrainId"] == 3 ){//check if this is an admin registration
		$constrainId = 3;
		if( !$user->isLogged() ){//check if the user allready has been authenticated for this role
			$action = "login_required";
		}else{//if he is, check if this user is register into the role
			if( !$user->isInRole( $myPage->role["roleId"] ) ) $action = "acces_denied";
		}//if else
	}//if
}//if

// check action value and set include template file based on this value
if( $action == 'register' )      { $includeTemplate = "login_registration/register.php";								$docTitle = 'Register'; }
if( $action == 'editSettings' )  { $includeTemplate = "login_registration/editsettings.php";						$docTitle = 'Edit Settings'; }
if( $action == 'acces_denied' )  { $includeTemplate = "login_registration/access_denied.php";						$docTitle = 'Access Denied'; }
if( $action == 'login_required' ){ $includeTemplate = "login_registration/login_required.php";					$docTitle = 'Login Required'; }
if( $action == 'role_password' ) { $includeTemplate = "login_registration/role_login_required.php";			$docTitle = 'Login Required'; }
if( $action == 'sitemap' )       { $includeTemplate = "sitemap.php";																		$docTitle = 'Sitemap'; }
if( $action == 'search' )        { $includeTemplate = "search/index.php";																$docTitle = 'Search'; }
if( $action == 'newsfeed' )      { $includeTemplate = "newsfeed.php";																		$docTitle = 'NewsFeeds'; }
if( $action == 'newsfeedsub' )   { $includeTemplate = "feed_subscribe.php";															$docTitle = 'Subscribe to NewsFeeds'; }
if( $action == 'newstopics' )    { $includeTemplate = "newstopics.php";																	$docTitle = 'News Topics'; }
if( $action == 'newstopicarchive' ) { $includeTemplate = "newsarchive.php";															$docTitle = 'News Topic Archive'; }
if( $action == 'unsubscribe' )   { $includeTemplate = "removemail.php";																	$docTitle = 'Unsubscribe'; }

if( !$action ){ //if no action include load stuff for the selected page
	if( $myPage->publish ){//check if the page is published and available
		$docName        = stripslashes($myPage->properties["name"]);
		$docTitle       = stripslashes($myPage->properties["title"]);
		$docDescription = stripslashes($myPage->properties["description"]);
		$docKeyword     = stripslashes($myPage->properties["meta"]);
		$docKeyword     = stripslashes(str_replace(" ",",",$docKeyword));
		$docContent     = stripslashes($layout->buildLayout($myPage->properties["layout"]));
		$news			= $myPage->getNews();
		//if (count($news)) $news = makeNews($news, $lang);
		$showcomment	= $myPage->properties["showcomment"];
	}else{//the page is not available, display warning
		$includeTemplate ="login_registration/pageunpublish.php";
	}//if else
}//if

if( !$roleId )		$roleId		= $_POST["roleId"];
if( !$roleId )		$roleId		= $myPage->role["roleId"];
if( !$roleName )	$roleName = $_POST["roleName"];
if( !$roleName )	$roleName = $myPage->role["roleName"];

//menu builders... here you can also make your own, these are only standard stuff
$topMenu        = $myPage->getMenu('top');
$siblingMenu    = $myPage->getMenu('menu');
$childMenu      = $myPage->getMenu('child');
$topActive			= getTopActive ($page);
$subActive			= getSubActive ($page);
$historyPath    = $myPage->getPath();
if( !$siblingMenu && count($childMenu) ) $siblingMenu = $childMenu;

$templateName = "templates/". trim( $myPage->properties["template"] ) .".php";
$docTemplate    = ( file_exists( $templateName ) )? trim( $myPage->properties["template"] ):"default";
if ($action) $docTemplate = "default";
if( $includeTemplate ) $login = false;
    
//****************************JSSCRIPT TIL LOG*****************************//
$stats = '<script language="JavaScript" type="text/javascript">'."\n";
$stats.= '<!--'."\n";
$stats.= 'far="Andre"; jav="n"; skh="Andre"; jav=navigator.javaEnabled();'."\n";
$stats.= 'ref=""+escape(top.document.referrer); nav=navigator.appName;'."\n";
$stats.= 'if (navigator.appName == "Netscape" && (navigator.appVersion.charAt(0) == "2" || navigator.appVersion.charAt(0) == "3")) {skriv=false;} else {skriv=true;}'."\n";
$stats.= 'if (skriv==true) { skh=screen.width + "x" + screen.height;'."\n";
$stats.= 'if (nav != "Netscape"){far=screen.colorDepth;} else {far=screen.pixelDepth;}'."\n";
$stats.= 'puri="sessid='. session_id() .'&amp;id='. $page .'&amp;size="+skh+"&amp;referer='.$_SERVER["HTTP_REFERER"].'&amp;colors="+far+"&amp;java="+jav+"&amp;js=y";'."\n";
$stats.= 'document.write("<img height=\"1\" width=\"1\" border=\"0\" src=\"log.php?"+puri+"\" alt=\"stat\" align=left>"); }
//-->'."\n";
$stats.= '</script>'."\n";
$stats.= '<noscript>'."\n";
$stats.= '<img height="1" width="1" border="0" src="log.php?js=n" alt="stat" align=left></noscript>'."\n";

//Now include the proper template for displaying content
if($print){
	require_once("print.php");
}else{
	require_once("templates/".$docTemplate .".php");
}//if else
?>