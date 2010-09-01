<?php
  require_once("../util/forms.php");
  require_once("../util/dba.php");
  require_once("../util/fields.php");
  require_once("../util/date_widget.php");
  require_once("../util/postcode_widget.php");
  require_once("../util/country_widget.php");
  
  if( $cancel || $_POST["cancel"] ) Header("Location:index.php?pane=forms");

  if( !$action ) $action = $_POST["action"];
  if( !$id ) $id = $_POST["id"];
  if( !$id ) $id = $_GET["id"];
  if( !$action_type ) $action_type = $_POST["action_type"];
  if( !$name ) $name = $_POST["name"];
  if( !$extern_url ) $extern_url = $_POST["extern_url"];
  if( !$mail_recipients ) $mail_recipients = $_POST["mail_recipients"];
  if( !$confirmation_page ) $confirmation_page = $_POST["confirmation_page"];
  if( !$confirmation_page_name ) $confirmation_page_name = $_POST["confirmation_page_name"];
  if( !$submit_label ) $submit_label = $_POST["submit_label"];
  if( !$cancel_label ) $cancel_label = $_POST["cancel_label"];
  if( !$remove ) $remove = $_GET["remove"];
  if( !$moveup ) $moveup = $_GET["moveup"];
  if( !$movedown ) $movedown = $_GET["movedown"];
  if( !$pane ) $pane = $_POST["pane"];
  if( !$pane ) $pane = $_GET["pane"];
	
	if( !$referer ) $referer = $_GET["referer"];
	if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
	
  $dba = new dba();
  $forms = new forms( $dba );

  if( $submited || $_POST["submited"] )
  {
    if( !$id ) $id = $forms->addForm(); 

    $forms->id = $id;
    $forms->setName( $name );
    $forms->setActionType( $action_type );
    $forms->setMailRecipients( $mail_recipients );
    $forms->setConfirmationPage( $confirmation_page );
		if ($confirmation_page_name && !$confirmation_page) $forms->setConfirmationPage( $confirmation_page_name );
    $forms->setSubmitLabel( $submit_label );
    $forms->setCancelLabel( $cancel_label );
    $forms->setExternUrl( $extern_url );
		
		if ($action_type == "newsletter")
		{
			$fields = new fields( $dba, $forms->id );
			$fields->makeMailfield($id);
		}

    $msg =( $action == 'add' )? $forms->name .' has been created <img src="../graphics/yes.gif">':'Your changes has been saved <img src="../graphics/yes.gif">';
  }

  $forms->form( $id );
  $fields = new fields( $dba, $id );

  if( $remove  ) $fields->remove( $remove );
  if( $moveup )  $fields->moveUp( $moveup );
  if( $movedown )$fields->moveDown( $movedown ); 

  if( $action_type )     $forms->action_type     = $action_type;
  if( $mail_recipients ) $forms->mail_recipients = $mail_recipients;
  if( $name )            $forms->name            = $name;
  if( $submit_label )    $forms->submit_label    = $submit_label;
  if( $cancel_label )    $forms->cancel_label    = $cancel_label;
  if( $extern_url )      $forms->extern_url      = $extern_url;

  $panes['form'] = "Form";  
  if( $id ) $panes['preview'] = "Preview";
  if( $id && ( $formList[$i]["action_type"] != "mail" || $formList[$i]["action_type"] != "custom" ) ) $panes['formdata'] = "Collected data";
  if( !$pane ) $pane = "form";

  switch( $pane )
  {
      case("form"):
          $pane_include = "formForm.php";
          break;
      case("preview"):
          $pane_include = "previewForm.php";
          break;
      case("formdata"):
          $pane_include = "formDataView.php";
          break;
  }
?>
<html>
    <head>
        <title><?=( $action =='add')?'Create form':'Edit form'?></title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
        <script language="javascript">
          function movingUp( id )
          {
            document.location.href='form.php?id=<?=$id?>&moveup='+ id;
          }
          function movingDown( id )
          {
            document.location.href='form.php?id=<?=$id?>&movedown='+ id;
          }
          function selectPage()
          {
            szURL = 'includesDocTree.php';
            prop_str = 'resizable=no,scrollbars=no,toolbar=no,location=no,';
            prop_str+= 'directories=no,status=no,width=300,height=465,screenX=150,screenY=150';
            ae_imgwin = window.open(szURL ,"ae_imgwin",prop_str);
          }

          function choosenDocument( id, name )
          {
            document.my_form.confirmation_page.value = id;
            document.my_form.confirmation_page_name.value = name;
          }
          function addfield()
          {
            typelist = document.my_form.typelist.value;
            document.location.href='field.php?formid=<?=$id?>&type='+ typelist;
          }
          function removing( id )
          {
            if( confirm('Are you shure you want to remove the selected field?') )
            {
              document.location.href='form.php?id=<?=$id?>&remove='+ id;
            }
          }
        </script>
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
       <form name="my_form" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
       <input type="hidden" name="id" value="<?=$id?>">
       <input type="hidden" name="referer" value="<?=$referer?>">
       <input type="hidden" name="action" value="<?=$action?>">

       <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td><img src="../graphics/transp.gif"></td>
						<td onClick="document.location.href='index.php'" style="cursor:hand;"><img src="../graphics/horisontal_button/left_unselected.gif" /></td>
            <td onClick="document.location.href='index.php'" class="faneblad_unselected" style="cursor:hand;">Forms</td>
            <td onClick="document.location.href='index.php'" style="cursor:hand;"><img src="../graphics/horisontal_button/right_unselected.gif" /></td>
            <td><img src="../graphics/transp.gif" width="4" /></td>
            <?foreach( $panes as $key => $value ):?>
            <td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif" /></td>
            <td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" class="faneblad<?=($pane==$key)? "_selected":"_unselected"?>" style="cursor:hand;"><?=$value?></td>
            <td onClick="document.location.href='<?=$PHP_SELF?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif" /></td>
            <td><img src="../graphics/transp.gif" width="4" /></td>
            <?endforeach?>
          </tr>
        </table>
        
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content" valign="top">
                    <?if( $pane_include ):?>
                    <?require_once($pane_include);?>
                    <?else:?>
                    &nbsp;
                    <?endif?>
                </td>
            </tr>
        </table>

        </form>
    </body>
</html>
