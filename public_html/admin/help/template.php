<? $includeDoc = array( "documents"=>"documents.php","media"=>"media.php","cmsusers"=>"cms_users.php","templates"=>"templates.php","websiteuser"=>"website_users.php") ?>
<html>
    <head>
        <title>Wizi help</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
    <table cellpadding="0" cellspacing="0" border="0">
    	<tr>
      	<td><img src="../graphics/transp.gif"></td>
				<td><img src="../graphics/transp.gif"></td>
	    	<td onclick="document.location.href='/wizi5demo/admin/help/index.php?id=&pane=users'" style="cursor:hand;"><img src="../graphics/horisontal_button/left_selected.gif"></td>
		    <td  onclick="document.location.href='/wizi5demo/admin/help/index.php?id=&pane=users'"class="faneblad_selected" style="cursor:hand;">Help </td>
		    <td onclick="document.location.href='/wizi5demo/admin/help/index.php?id=&pane=users'" style="cursor:hand;"><img src="../graphics/horisontal_button/right_selected.gif"></td>
		  	 <td><img src="../graphics/transp.gif" width="4"></td>
	  	</tr>
    </table>
		
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
	  	<td width="1"><img src="graphics/transp.gif" border="0" width="1" height="350"></td>
	    <td class="tdborder_content" valign="top">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	      	<tr> 
	        	<td bgcolor="#FFFFFF"><img src="../graphics/transp.gif" height="20"></td>
	        </tr> 
	        <tr>
	        	<td class="header">Overview</td>
	        </tr> 
					<tr>
	        	<td bgcolor="#FFFFFF"><img src="../graphics/transp.gif" height="15"></td>
	        </tr>
	        <tr>
	        	<td valign="top">
							<table cellpading="0" cellspacing="0" border="0" width="100%">
								<tr>
									<td class="cellStyle"><?require_once( 'help_files/'.$includeDoc [ $include ] );?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</body>
</html>

