<?php
  require_once("../util/dba.php");
  require_once("../util/layout.php");
  require_once("../util/forms.php");
  require_once("../util/fields.php");
  require_once("../util/date_widget.php");
  require_once("../util/postcode_widget.php");
  require_once("../util/country_widget.php");

  if( !$formid ) $formid = $_GET["formid"];
  if( !$formid ) $formid = $_POST["formid"];
  if( !$formid ) die("ID parameter expected");

  if( !$id ) $id = $_GET["id"];
  if( !$id ) $id = $_POST["id"];

  if( !$type ) $type = $_GET["type"];
  if( !$type ) $type = $_POST["type"];

  if( !$selectedLayout ) $selectedLayout = $_POST["selectedLayout"];
  if( !$label ) $label = $_POST["label"];
  if( !$default_value ) $default_value = $_POST["default_value"];
  if( !$required ) $required = $_POST["required"];
  if( !$maxchar ) $maxchar = $_POST["maxchar"];
  if( !$width ) $width = $_POST["width"];
  if( !$height ) $height = $_POST["height"];
  if( !$use_default_value ) $use_default_value = $_POST["use_default_value"];

  eval( '$selected_country = $selected_country_'.$id.';' ); 
  eval( '$postnr = $postnr_'.$id.';' ); 
  eval( '$day = $day_'.$id.';' ); 
  eval( '$month = $month_'.$id.';' ); 
  eval( '$year = $year_'.$id.';' ); 
  eval( '$hour = $hour_'.$id.';' ); 
  eval( '$minute = $minute_'.$id.';' ); 
  eval( '$second = $second_'.$id.';' ); 
  if( !$selected_country ) $selected_country  = $_POST["selected_country_". $id ];
  if( !$postnr ) $postnr    = $_POST["field_". $id ];
  if( !$day ) $day = $_POST["day_".$id];
  if( !$month ) $month = $_POST["month_".$id];
  if( !$year ) $year = $_POST["year_".$id];
  if( !$hour ) $hour = $_POST["hour_".$id];
  if( !$minute ) $minute = $_POST["minute_".$id];
  if( !$second ) $second = $_POST["second_".$id];

  if( !$listvalues ) $listvalues = $_POST["listvalues"];
  if( !$selectedlistvalues ) $selectedlistvalues = $_POST["selectedlistvalues"];

  $dba = new dba();
  $forms = new forms( $dba );
  $forms->form( $formid );
  $fields = new fields( $dba, $formid );
  
  if( $submited || $_POST["submited"] ){
    //add field
    if( !$id ) $id = $fields->addField( $formid );
    
    //set the field
    $fields->id = $id;

    //
    $resetDefaults = array('postcode','country','date','time','datetime' );
    if( in_array( $fieldTypeName ,$resetDefaults ) ) $default_value = '';
    
    //update fields
    $fields->setType( $type );
    $fields->setLabel( $label );
    $fields->setMaxChar( $maxchar );
    $fields->setDefaultValue( $default_value );
    $fields->setLayout( $selectedLayout );
    $fields->setRequired( $required );
    $fields->setWidth( $width );
    $fields->setHeight( $height );
    if( $use_default_value && $postnr ) $fields->setDefaultValue( $postnr );
    if( $use_default_value && $selected_country ) $fields->setDefaultValue( $selected_country );
    if( $use_default_value && $day && !is_numeric( $hour )  ) $fields->setDefaultValue( $day.'.'.$month.'.'.$year );
    if( $use_default_value && is_numeric( $hour ) && !$day ) $fields->setDefaultValue( $hour.':'.$minute.':'.$second );
    if( $use_default_value && $day && is_numeric( $hour ) ) $fields->setDefaultValue( $day.'.'.$month.'.'.$year.' '.$hour.':'.$minute.':'.$second );
    $fields->setListValues( $formid, $listvalues, $selectedlistvalues, $use_default_value );

    //go back to the form
    HEADER("Location:form.php?id=$formid");
  }
  
  if( $id ) 						 $fields->loadField( $id );
  if( !$type )           $type = $fields->type;
  if( !$label )          $label = $fields->label;
  if( !$selectedLayout ) $selectedLayout = $fields->layout;
  if( !$default_value )  $default_value = $fields->default_value;
  if( !$required )       $required = $fields->required;
  if( !$maxchar )        $maxchar = $fields->maxchar;
  if( !$width )          $width = $fields->width;
  if( !$height )         $height = $fields->height;
  if( !$postnr )         $postnr = $fields->default_value;
  if( !$selected_country )        $selected_country = $fields->default_value;

  $typelist = $fields->getTypeList();
  $fieldTypeTitle = $fields->fieldTypeTitleById[ $type ];
  $fieldTypeName  = $fields->fieldTypeNameById[ $type ];
  

  if( !$day && $fieldTypeName == 'date' )
  {
    if( $fields->default_value )
    {
      $date = explode(".",$fields->default_value );
      if( count( $date ) == 3 )
      {
        $day = $date[0];
        $month = $date[1];
        $year = $date[2];
      }
    }
  }
  if( !$hour && $fieldTypeName == 'time' )
  {
    if( $fields->default_value )
    {
      $date   = explode(":",$fields->default_value );
      if( count( $date ) == 3 )
      {
        $hour   = ( $date[0] )? $date[0]:'00';
        $minute = ( $date[1] )? $date[1]:'00';
        $second = ( $date[2] )? $date[2]:'00';
      }
    }
  }

  if( !$day && $fieldTypeName == 'datetime' )
  {
    if( $fields->default_value )
    {
      $datetime = explode(" ",$fields->default_value );
      $date     = explode(".",$datetime[0] );
      $time     = explode(":",$datetime[1] );

      if( count( $date ) == 3 )
      {
        $day = $date[0];
        $month = $date[1];
        $year = $date[2];
      }
      if( count( $time ) == 3 )
      {
        $hour   = ( $time[0] )? $time[0]:'00';
        $minute = ( $time[1] )? $time[1]:'00';
        $second = ( $time[2] )? $time[2]:'00';
      }
    }
  }

  $title = ( $id )?"Edit ". $fieldTypeTitle ." from form '". $forms->name ."'":"Add ". $fieldTypeTitle ." to form '". $forms->name ."'";

  $fieldProperties = array(
                            "text"=>array('label','default','required','template','maxchar','width'),
                            "textarea"=>array('label','default','required','template','width','height'),
                            "label"=>array('label_big','template'),
                            "number"=>array('label','default','required','template','maxchar','width'),
                            "mail"=>array('label','required','template','width','maxchar'),
                            "postcode"=>array('label','required','template','postnr','default_checkbox'),
                            "country"=>array('label','required','template','countries','default_checkbox'),
                            "date"=>array('label','template','date','default_checkbox'),
                            "time"=>array('label','template','time','default_checkbox'),
                            "datetime"=>array('label','template','datetime','default_checkbox'),
                            "checkbox"=>array('label','required','template','multiple_values','multiple_value_selected'),
                            "radio"=>array('label','template','required','multiple_values','value_selected'),
                            "combobox"=>array('label','template','required','multiple_values','value_selected','width'),
                            "list"=>array('label','template','required','multiple_values','value_selected','height','width'),
                            "hidden"=>array('default','hidden')
                          );

  if( $id )
  {
    if( $fieldTypeName == 'list'     ) $listvaluesArr = $fields->getListValues( $formid );
    if( $fieldTypeName == 'combobox' ) $listvaluesArr = $fields->getListValues( $formid );
    if( $fieldTypeName == 'radio'    ) $listvaluesArr = $fields->getListValues( $formid );
    if( $fieldTypeName == 'checkbox' ) $listvaluesArr = $fields->getListValues( $formid );
    
    $selectedlistvalues = false;
    //check if there is any selected listvalue
    for( $i = 0; $i < count( $listvaluesArr ); $i++ ) 
    {
      if( $listvaluesArr[$i]["selected"]=='y' ) 
      {
        $selectedlistvalue = true;
        break;
      }
    }
  }
  
  $properties = $fieldProperties[ $fieldTypeName ];
  $layout    = new layout( $dba, 1, true ); 
  $layouts   = $layout->getLayouts();
  if( !$selectedLayout ) $selectedLayout = "single.TMP1";
  $e = explode(".", $selectedLayout );
  $imgName = "../../layouts/". $e[0] .".gif";
?>
<html>
    <head>
        <title><?=$title?></title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
        <script language="javascript">
           function getListValues( )
           {
              var termId = '';
              var selId ='';
              if( !document.my_form.multipleList ) return;
              destList = document.my_form.multipleList;

              for( len = 0; len < destList.options.length; len++ ) 
              {
                  if ( destList.options[ len ].text != '')
                  {
                      if( termId !='' ) termId +=',';
                      termId += destList.options[len].text;
                  }
                  if( destList.options[ len ].selected )
                  {
                     if( selId != '' ) selId += ',';
                     selId += destList.options[len].text;
                  }
              }
              document.my_form.listvalues.value = termId;
              document.my_form.selectedlistvalues.value = selId;
           }
           function addValue()
           {
              itemName = document.my_form.multiple_add.value;
              destList = document.my_form.multipleList;
              document.my_form.multiple_add.value ='';
              if( itemName =='' ) return;

              var newDestList = new Array( destList.options.length );
              var len = 0;

              //read the current destination list on an option array
              for( len = 0; len < destList.options.length; len++ ) 
              {
                  if ( destList.options[ len ] != null )
                  {
                      newDestList[ len ] = new Option( destList.options[ len ].text, destList.options[ len ].value, destList.options[ len ].defaultSelected, destList.options[ len ].selected );
                  }
              }
              newDestList[ len ] = new Option( itemName,itemName );

              // Populate the destination with the items from the new array
              for ( var j = 0; j < newDestList.length; j++ ) 
              {
                  if ( newDestList[ j ] != null )
                  {
                      destList.options[ j ] = newDestList[ j ];
                  }
              }
          }
          function removeValue()
          {
            srcList =  document.my_form.multipleList; 

            // Remove selected elements from the list
            for( var i = srcList.options.length - 1; i >= 0; i-- ) 
            { 
                if ( srcList.options[i] != null && ( srcList.options[i].selected == true ) )
                {
                    srcList.options[i]       = null;
                }
            }
          }
          function changeLayout()
          {
            newLayout = document.my_form.selectedLayout.value;
            myArray = newLayout.split('.');
            var newSrc = '../../layouts/'+ myArray[0] +'.gif';
            document.layout_icon.src = newSrc;
          }
        </script>
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
       <form name="my_form" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
       <input type="hidden" name="id" value="<?=$id?>">
       <input type="hidden" name="formid" value="<?=$formid?>">
       <input type="hidden" name="listvalues">
       <input type="hidden" name="selectedlistvalues">

       <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td><img src="../graphics/transp.gif"></td>
            <td><img src="../graphics/horisontal_button/left_selected.gif"></td>
            <td class="faneblad_selected"><?=$title?></td>
            <td><img src="../graphics/horisontal_button/right_selected.gif"></td>
          </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content" valign="top">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr> 
                        <td bgcolor="#FFFFFF" colspan="3"><img src="../graphics/transp.gif" height="20"></td>
                      </tr> 
                      <tr>
                        <td class="header"><?=$title?></td>
                      </tr> 
                        <td bgcolor="#FFFFFF" class="save_message"><?=$message?></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="plainText">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
                      <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="tdpadtext">Field type</td>
                                </tr>
                                <tr>
                                    <td class="tdpadtext">
																			<select class="input" name="type" onChange="document.my_form.submit()">
                                        <? for( $j = 0; $j < count( $typelist ); $j++ ):?>
                                          <option value="<?=$typelist[$j]["id"]?>" <?=($type==$typelist[$j]["id"])?'selected':''?>><?=$typelist[$j]["title"]?></option>
                                        <? endfor?>
                                      </select>
                                    </td>
                               </tr>

                               <!--Template--> 
                               <? if( in_array('template',$properties ) ):?>
                                <tr>
                                    <td>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                      <tr>
                                        <td class="tdpadtext" valign="top">
                                          Choose a layout
                                          <br>
                                          <select onChange="changeLayout()" name="selectedLayout" class="select_list">
                                            <? for( $i = 0; $i < count( $layouts ); $i++ ):?>
                                              <? $f = explode( ".",$layouts[$i] )?>
                                              <option value="<?=$layouts[$i]?>" <?=( $layouts[$i] == $selectedLayout )?"selected":"";?> ><?=$f[0]?></option>
                                            <? endfor?>
                                          </select>

                                        </td>
                                        <td valign="middle" align="right">
                                          <br />
                                          <? if( file_exists( $imgName ) ):?>
                                            <img  name="layout_icon" src="<?=$imgName?>">
                                          <? else:?>
                                            <img name="layout_icon" src="../../layouts/noIcon.gif">
                                          <? endif?>
                                        </td>
                                     </tr>
                                    </table>
                                    </td>
                                </tr>
                               <? endif?>

                               <!--Lable--> 
                               <? if( in_array('label',$properties ) ):?>
                                 <tr>
                                     <td class="tdpadtext">Label </td>
                                 </tr>
                                 <tr>
                                    <td class="tdpadtext">
                                       <input type="text" name="label" class="input" value="<?=$label?>">
                                    </td>
                                 </tr>
                                <? endif?>

                                <!--Postnr-->
                                <? if( in_array('postnr',$properties ) ):?>
                                 <tr>
                                     <td class="tdpadtext">
                                     <input type="checkbox" name="use_default_value" <?=($postnr)?'checked':''?>>
                                     Use a selected postnumber as default 
                                     </td>
                                 </tr>
                                 <tr>
                                    <td class="tdpadtext">
                                       <? $post = new postcode_widget( $dba )?>
                                       <?=$post->render( $id, $postnr )?>
                                    </td>
                                 </tr>
                                <? endif?>

                                <!--Country-->
                                <? if( in_array('countries',$properties ) ):?>
                                 <tr>
                                     <td class="tdpadtext">
                                     <input type="checkbox" name="use_default_value" <?=($selected_country)?'checked':''?>>
                                     Use a selected country as default 
                                     </td>
                                 </tr>
                                 <tr>
                                    <td class="tdpadtext">
                                       <? $country = new country_widget( $dba )?>
                                       <?=$country->render( $id, $selected_country )?>
                                    </td>
                                 </tr>
                                <? endif?>

                                <!--Date-->
                                <? if( in_array('date',$properties ) ):?>
                                 <tr>
                                     <td class="tdpadtext">
                                     <input type="checkbox" name="use_default_value" <?=($day)?'checked':''?>>
                                     Use a selected date as default 
                                     </td>
                                 </tr>
                                 <tr>
                                    <td class="tdpadtext">
                                       <? $date = new date_widget($id,$day,$month,$year)?>
                                       <?=$date->render()?>
                                       <span style="font-weight:normal;color:#666666">[d.m.y]</span>
                                    </td>
                                 </tr>
                                <? endif?>

                                <!--Time-->
                                <? if( in_array('time',$properties ) ):?>
                                 <tr>
                                     <td class="tdpadtext">
                                     <input type="checkbox" name="use_default_value" <?=($hour)?'checked':''?>>
                                     Use a selected time as default 
                                     </td>
                                 </tr>
                                 <tr>
                                    <td class="tdpadtext">
                                       <? $date = new date_widget($id,$day,$month,$year,$hour,$minute,$second)?>
                                       <?=$date->renderTime()?>
                                       <span style="font-weight:normal;color:#666666">[h:m:s]</span>
                                    </td>
                                 </tr>
                                <? endif?>

                                <!--dateTime-->
                                <? if( in_array('datetime',$properties ) ):?>
                                 <tr>
                                     <td class="tdpadtext">
                                     <input type="checkbox" name="use_default_value" <?=($day && $hour)?'checked':''?>>
                                     Use a selected date-time as default 
                                     </td>
                                 </tr>
                                 <tr>
                                    <td class="tdpadtext">
                                       <? $date = new date_widget($id,$day,$month,$year,$hour,$minute,$second)?>
                                       <?=$date->renderDateTime()?>
                                       <span style="font-weight:normal;color:#666666">[d.m.y h:m:s]</span>
                                    </td>
                                 </tr>
                                <? endif?>


                               <!--Lable big--> 
                               <? if( in_array('label_big',$properties ) ):?>
                                 <tr>
                                     <td class="tdpadtext">Label </td>
                                 </tr>
                                 <tr>
                                    <td class="tdpadtext">
                                       <textarea class="input" rows="8" cols="30" name="label"><?=$label?></textarea>
                                    </td>
                                 </tr>
                                <? endif?>

                               <!--Default--> 
                               <? if( in_array('default',$properties  ) ):?>
                               <tr>
                                   <td class="tdpadtext">Default value </td>
                               </tr>
                               <tr>
                                  <td class="tdpadtext">
                                     <input type="text" name="default_value" class="input" value="<?= $fields->default_value?>">
                                  </td>
                               </tr>
                               <? endif?>
															 
															 <!--Hidden-->
															 <? if( in_array('hidden',$properties  ) ):?>
                               <tr>
                                   <td class="tdpadtext">&nbsp;</td>
                               </tr>
															 <tr>
                                   <td class="tdpadtext">Select a default value<br>(used to send information about the user with the form):</td>
                               </tr>
                               <tr>
                                  <td class="tdpadtext">
                                     <select onChange="document.my_form.default_value.value = this.value;" name="hiddenvaluestandard" class="select_list">
																					<option value="<?= $fields->default_value?>"></option>
																					<option value="USER_IP">Users Ip address</option>
																					<option value="USER_BROWSER">Users Browser type</option>
																					<option value="USER_LANGUAGE">Users Browser language</option>
																			</select>
                                  </td>
                               </tr>
                               <? endif?>

                               <!--Required--> 
                               <? if( in_array('required',$properties ) ):?>
                               <tr>
                                   <td class="tdpadtext">
                                    <? if ( $formid == 1 && ($id == 1 || $id == 2) ):?>
																		<input type="hidden" name="required" value="on">
																		<? else:?>
																		<input type="checkbox" name="required" <?=( $required == 'y' )?'checked':''?>>
                                    Mark the field as required
																		<? endif?>
                                   </td>
                               </tr>
                               <? endif?>


                               <!--max char-->
                               <?if( in_array('maxchar',$properties ) ):?>
                               <tr>
                                   <td class="tdpadtext">
                                    <select name="maxchar" style="width:50px">
                                      <?if( !$maxchar ) $maxchar = 200;?>
                                      <?for( $i = 1; $i < 201;$i++ ):?>
                                          <option value="<?=$i?>" <?=( $i == $maxchar )?'selected':''?>><?=$i?></option>
                                      <?endfor?>
                                    </select>
                                    Maximun number of characters allowed
                                   </td>
                               </tr>
                               <?endif?>

                               <!--Width-->
                               <?if( in_array('width',$properties ) ):?>
                               <tr>
                                   <td class="tdpadtext">
                                    <?if( !$width ) $width = 25;?>
                                    <select name="width" style="width:50px">
                                      <?for( $i = 1; $i < 201;$i++ ):?>
                                          <option value="<?=$i?>" <?=( $i == $width )?'selected':''?>><?=$i?></option>
                                      <?endfor?>
                                    </select>
                                    Width of field
                                   </td>
                               </tr>
                               <?endif?>

                               <!--Height-->
                               <?if( in_array('height',$properties ) ):?>
                               <tr>
                                   <td class="tdpadtext">
                                   <?if( !$height ) $height = 8?>
                                    <select name="height" style="width:50px">
                                      <?for( $i = 1; $i < 51;$i++ ):?>
                                          <option value="<?=$i?>" <?=( $i == $height )?'selected':''?>><?=$i?></option>
                                      <?endfor?>
                                    </select>
                                    Hight of field
                                   </td>
                               </tr>
                               <?endif?>

                               <!--List-->
                               <?if( in_array('multiple_values',$properties ) ):?>
                               <tr>
                                   <td class="tdpadtext">&nbsp;</td>
                               </tr>
                                 <tr>
                                     <td class="tdpadtext">
                                     <input type="checkbox" name="use_default_value" <?=( $selectedlistvalue )?'checked':''?>>
                                     Use selected items as default
                                     </td>
                                 </tr>
                               <tr>
                                   <td class="tdpadtext">
            							            <select multiple name="multipleList" type="list" size="5"  class="input">
                                      <?php for( $i = 0; $i < count( $listvaluesArr ); $i++ ):?>
                                        <option <?=( $listvaluesArr[$i]["selected"] =='y' )?'selected':''?>>
                                          <?=$listvaluesArr[$i]["listvalue"]?>
                                        </option>
                                      <?endfor?>
                                      </select>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tdpadtext">
                                       <input type="text" name="multiple_add" class="input" style="input">
                                   </td>
                               </tr>
                               <tr>
                                   <td class="tdpadtext" align="right">
                                       <input type="button" value="Remove item" onClick="removeValue()" class="medium_knap">
                                       <input type="button" value="Add item" onClick="addValue()" class="medium_knap">
                                   </td>
                               </tr>
                               <?endif?>

                               <tr> <td class="tdpadtext">&nbsp; </td> </tr>
                            </table>
                          </td>
                        </tr>
                   </table>
                   <table width="310" cellpadding="0" cellspacing="0" border="0">
                      <tr> <td>&nbsp;</td> </tr>
                      <tr>
                          <td align="right"><input type="button" value="Cancel" onClick="document.location.href='form.php?id=<?=$formid?>'" class="knapred"> <input type="submit" value="OK" name="submited" class="knapgreen" onClick="getListValues()"></td>
                      </tr>
                   </table>
                   <br/>
                </td>
            </tr>
        </table>
				</form>
  </body>
</html>
