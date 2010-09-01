<?php
/**********************************************************************/
/*                                                                    */
/*   File name: install.php                                           */
/*                                                                    */
/*                                                                    */
/**********************************************************************/
/*                                                                    */
/*                                                                    */
/**********************************************************************/
/*                                                                    */
/*   Date: 05-11-2001 15:45:00                                        */
/*                                                                    */
/**********************************************************************/

if( !$password ) $password = $_POST["password"];
if( !$install  ) $install  = $_POST["install"];
if( !$submited ) $submited = $_POST["submited"];

$ip = $_SERVER["REMOTE_ADDR"];
$url = $_SERVER["SERVER_NAME"];
$serverip = $_SERVER["SERVER_ADDR"];

if( $submited )
{
	if( !$dbName ) $dbName = $_POST["dbName"];
	if( !$dbPass ) $dbPass = $_POST["dbPass"];
	if( !$hostName ) $hostName = $_POST["hostName"];
	if( !$username ) $username = $_POST["username"];
	if( !$prefix   ) $prefix   = $_POST["prefix"];
	if( !$rootpath ) $rootpath = $_POST["rootpath"];

	$error = array();
	if( !$dbName ) $error[ count( $error ) ] = "Name of database is missing";
	if( !$dbPass ) $error[ count( $error ) ] = "Password for database is missing";
	if( !$hostName ) $error[ count( $error ) ] = "Hostname or IP adress for database is missing";
	if( !$username ) $error[ count( $error ) ] = "Username for database is missing";
	if( !$rootpath ) $error[ count( $error ) ] = "Path to root is missing";
	
	if( !count( $error ) )
	{
		makeDBA($dbName,$dbPass,$hostName,$username,$prefix,$rootpath);
		$message = "A WIZI installation has been made on the adress: ".$url."\n\n\n";
		$message.= "The IP adress of the person installing was: ".$ip."\n\n";
		$message.= "The server IP adress is: ".$serverip."\n\n";
		$message.= "The URL is: ".$url."\n\n\n";
		$message.= "The installation was made on ".date('D M j G:i:s - Y');
		@mail("rasmus@artz.dk", "New Wizi installed on ".$url."", $message);
		Header("Location:admin/tools/set_up.php");
	}
}

function makeDBA($dbname,$password,$hostname,$user,$prefix,$rootpath="/")
{
		$dbFileName = "admin/util/dba.php";
    $media_lib  = "media";
		$formdata_lib = "admin/forms/formdata";
		$import_lib = "import/images";
		
		@chmod($dbFileName, 0777);
		@chmod($media_lib, 0777);
		@chmod($formdata_lib, 0777);
		@chmod($import_lib, 0777);

    if( !is_writable( $dbFileName ) || !is_writable( $media_lib ) || !is_writable( $formdata_lib ) || !is_writable( $import_lib ) )
    {
        $str = '<br><br><center><div style="color:#000000;font-family:Verdana,sans-serif;font-size:14px;font-weight:900">';
				
				if( !is_writable( $dbFileName ) )				$str.= '<br>The file '. $dbFileName .' and its parent directory should be writable.';
        if( !is_writable( $media_lib ) ) 				$str.= '<br>The directory '. $media_lib . ' should be writable.';
				if( !is_writable( $formdata_lib ) ) 		$str.= '<br>The directory '. $formdata_lib . ' should be writable.';
				if( !is_writable( $import_lib ) ) 			$str.= '<br>The directory '. $import_lib . ' should be writable.';

        $str.= '</div>';
        $str.= '<input type="button" value=" BACK " onClick="history.back()" class="knap" style="width:100px"></center>';
        die( $str );
    }

    if(file_exists( $dbFileName ))
    {
			$fd      = fopen( $dbFileName, "r" );
			$content = fread( $fd, filesize( $dbFileName ) );
			fclose($fd);

    	$replace = 'function dba( $db ="'.$dbname.'",$host="'.$hostname.'"';
    	$replace.= ',$user="'.$user.'",$password="'.$password.'",$prefix="'.$prefix.'",$rootpath="'.$rootpath.'"';
			$content = preg_replace("/function dba([^)]*)/",$replace, $content );

			//write the new content
    	$fp = fopen ($dbFileName, "w");
    	fwrite( $fp, $content );
			fclose( $fp );
    }
}
?>
<html>
	<head>
		<title>Install WIZI Content Management System</title>
		<link rel="stylesheet" href="admin/style/style.css" type="text/css">
	</head>
	<body class="grayBody">
		<br><br>
		<br><br>
		<br><br>
		<br><br>
		<center>
		<form name="my_form" action="<?php echo $PHP_SELF; ?>" method="post">
		
			<?if( $install && $password == 'admin' ):?>
				<input type="hidden" name="password" value="<?=$password?>">
				<input type="hidden" name="install" value="install">
				<?for( $i = 0; $i < count( $error ); $i++ ):?>
					<?=$error[$i]?><br>
				<?endfor?>
		    <table cellpadding="0" cellspacing="0" border="0" width="450">
		    	<tr>
						<td class="Header2" colspan="2" style="padding-left:5px;">Database information</td>
					</tr>
					<tr>
						<td colspan="2"><img src="admin/graphics/red.gif" border="0" width="400" height="3"></td>
					</tr>
		    	<tr>
						<td colspan="2"><img src="admin/graphics/transp.gif" border="0" width="400" height="10"></td>
					</tr>
					<tr class="color1" style="padding-left:5px;padding-top:3px;padding-bottom:3px;padding-right:5px;">
		    		<td valign="middle" class="plainText">Db Name:</td>
		    		<td align="right"><input type="text" name="dbName" style="width:250px;"></td>
		    	</tr>
		    	<tr class="color2" style="padding-left:5px;padding-top:3px;padding-bottom:3px;padding-right:5px;">
		    		<td valign="middle" class="plainText">Db Username:</td>
		    		<td align="right"><input type="text" name="username" style="width:250px;"></td>
		    	</tr>
			    <tr class="color1" style="padding-left:5px;padding-top:3px;padding-bottom:3px;padding-right:5px;">
						<td valign="middle" class="plainText">Db Password:</td>
						<td align="right"><input type="text" name="dbPass" style="width:250px;"></td>
					</tr>
			    <tr class="color2" style="padding-left:5px;padding-top:3px;padding-bottom:3px;padding-right:5px;">
				    <td valign="middle" class="plainText">Db Host:</td>
						<td align="right"><input type="text" name="hostName" style="width:250px;"></td>
		    	</tr>
			    <tr class="color1" style="padding-left:5px;padding-top:3px;padding-bottom:3px;padding-right:5px;">
				    <td valign="middle" class="plainText">Table prefix:</td>
				    <td align="right"><input type="text" name="prefix" style="width:250px;"></td>
			    </tr>
					<tr class="color2" style="padding-left:5px;padding-top:3px;padding-bottom:3px;padding-right:5px;">
				    <td valign="middle" class="plainText">Absolute path to root of system.<br><strong>Probably already guessed.</strong><br>(must end with /)</td>
				    <td align="right"><input type="text" name="rootpath" style="width:250px;" value="<? echo ereg_replace( "install.php", "", $_SERVER["REQUEST_URI"] ); ?>"></td>
			    </tr>
			    <tr>
						<td colspan="2"><img src="admin/graphics/transp.gif" border="0" width="450" height="10"></td>
					</tr>
		    	<tr>
						<td colspan="2"><img src="admin/graphics/red.gif" border="0" width="450" height="3"></td>
					</tr>
			    <tr>
				    <td colspan="2" align="center">
					    <input type="hidden" name="submited" value="1"><input type="button" value=" Cancel " class="knap" onClick="parent.history.back();" style="width:150px"><input type="submit" value=" OK " class="knap" name="submit" style="width:150px">
		    		</td>
		    	</tr>
					<tr>
				    <td colspan="2" align="center" height="20">&nbsp;</td>
		    	</tr>
					<tr class="color2" style="padding-left:5px;padding-top:3px;padding-bottom:3px;padding-right:5px;">
				    <td colspan="2" align="left">
							Information used for logging:<p><p>
							<?echo "Your IP adress: ".$_SERVER["REMOTE_ADDR"];?><br>
							<?echo "Host URL: ".$_SERVER["SERVER_NAME"];?><br>
							<?echo "Server IP adress: ".$_SERVER["SERVER_ADDR"];?><br>
		    		</td>
		    	</tr>
				</table>
			<?else:?>
				<div class="header2"> Welcome to <span style="color:#cc3300">wizi</span> version 4.0</div>
				<span class="plainText"> A Content Management System Developed by Execube ApS
				<br><br><br>
				<b>Type Installation Password:</b></span><br/>
				<input type="password" name="password" style="width=200px;" value=""><br/>
				<input type="hidden" name="install" value="1">
				<input type="submit" name="submit" style="width=200px;" class="knap" value=" INSTALL ">
			<?endif?>
		</form>
		</center>
	</body>
</html>