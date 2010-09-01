<?php
/**
 * Class which maps the form table to an object
 * @author Ronald
 */
class forms
{
    /** 
     * Connection object and database abstraction layer
     * @type dba 
     */
    var $dba;

    /** 
     * Prefix for tables in the current installation
     * @type String
     */
    var $p;

    /**
     * Name of the relational table to be mapped
     * @type String
     */
    var $table;
    
    /**
     * List of all the records
     * @type mixedArray
     */
    var $list;

    /**
     * Unique identifier for a form
     * @type int
     */
    var $id;

    /**
     * Name of a form
     * @type String
     */
    var $name;

    /**
     * Type of action 
     * @type String
     */
    var $action_type;

    /**
     * Url for extern prossesing of form
     * @type String
     */
    var $extern_url;

    /**
     * A list of mail recipients ( separated by semicolo )
     * @type String
     */
    var $mail_recipients;

    /**
     * The page where to redirect the user after the form processing 
     * @type String
     */
    var $confirmation_page;

    /**
     * The name of the page where to redirect the 
     * user after the form processing ( if the page is an internal reference )
     * @type String
     */
    var $confirmation_page_name;

    /**
     * The form renderes as html
     * @type String
     */
    var $renderedform;

    /**
     * Label for the submit button 
     * @type String
     */
    var $submit_label;
    /**
     * Label for the cancel button 
     * @type String
     */
    var $cancel_label;
    
    /**
     * Reference to field object
     * @type fields
     */
    var $fields;

    /**
     * Constructor for forms class
     * @param dba dba - Database abstraction layer
     */
    function forms( $dba )
    {
        $this->dba   = $dba;
        $this->p     = $this->dba->getPrefix();
        $this->table = $this->p ."forms"; 
    }
    
    /**
     * Selecte an expecific form by id
     * @param int
     */
    function form( $id )
    {
       if( !is_numeric( $id ) ) return;
        $this->id  = $id;
        $this->loadProperties();
    }

    /**
     * Load the fields( properties ) for a form
     * @returns void
     */
    function loadProperties()
    {
        $sql = "SELECT
                  name,
                  action_type,
                  mail_recipients,
                  confirmation_page,
                  extern_url,
                  renderedform,
                  submit_label,
                  cancel_label
                FROM
                  ".$this->p."forms
                WHERE
                  id=". $this->id;

        $properties = $this->dba->singleArray( $sql );
        $this->name = trim( stripslashes( $properties["name"] ) );
        $this->action_type = trim( stripslashes( $properties["action_type"] ) );
        $this->mail_recipients = trim( stripslashes( $properties["mail_recipients"] ) );
        $this->confirmation_page = trim( stripslashes( $properties["confirmation_page"] ) );
        $this->renderedform = trim( stripslashes( $properties["renderedform"] ) );
        $this->submit_label = trim( stripslashes( $properties["submit_label"] ) );
        $this->cancel_label = trim( stripslashes( $properties["cancel_label"] ) );
        $this->extern_url   = trim( stripslashes( $properties["extern_url"] ) );
        
        if( is_numeric( $this->confirmation_page ) )
        {
          $sql = "SELECT 
                    name 
                  FROM 
                    ". $this->p ."tree 
                  WHERE 
                    id=". $this->confirmation_page;
          $this->confirmation_page_name = trim( stripslashes( $this->dba->singleQuery( $sql ) ) );
        }
    }

    //********** setters *******//

    /**
     * Update the form name
     * @param String
     * @returns void
     */
    function setName( $name )
    {
      if( !trim( $name ) ) $name = 'untitled';
      $this->name = $name;
      $sql = "UPDATE
                ".$this->table."
              SET
                name = '". addslashes( trim( $name ) ) ."'
              WHERE
                id= ". $this->id;
      $this->dba->exec( $sql );
    }

    /**
     * Update the forms action type
     * @param String
     * @returns void
     */
    function setActionType( $action_type )
    {
      if( !trim( $action_type ) ) return;
      $this->action_type = $action_type;
      $sql = "UPDATE
                ".$this->table."
              SET
                action_type = '". addslashes( trim( $action_type ) ) ."'
              WHERE
                id= ". $this->id;
      $this->dba->exec( $sql );
    }

    /**
     * Update the forms reference to a confirmation page 
     * @param String confirmation_page
     * @returns void
     */
    function setConfirmationPage( $confirmation_page )
    {
        $sql = "UPDATE
                  ".$this->table."
                SET ";
        if( !trim( $confirmation_page ) ) $sql.='confirmation_page = NULL ';
        else $sql.= "confirmation_page = '". addslashes( trim( $confirmation_page ) ) ."' ";

        $sql.=" WHERE
                  id= ". $this->id;

      $this->confirmation_page = $confirmation_page;
      $this->dba->exec( $sql );

        if( is_numeric( $this->confirmation_page ) )
        {
          $sql = "SELECT 
                    name 
                  FROM 
                    ". $this->p ."tree 
                  WHERE 
                    id=". $this->confirmation_page;
          $this->confirmation_page_name = trim( stripslashes( $this->dba->singleQuery( $sql ) ) );
        }else{
					$this->confirmation_page_name = $confirmation_page;
				}
    }

    /**
     * Update the forms mail recipients 
     * @param String
     * @returns void
     */
    function setMailRecipients( $mail_recipients )
    {
      $this->mail_recipients = $mail_recipients;
      $sql = "UPDATE
                ".$this->table."
              SET
                mail_recipients = '". addslashes( trim( $mail_recipients ) ) ."'
              WHERE
                id= ". $this->id;
      $this->dba->exec( $sql );
    }
    /**
     * Update the forms action url
     * @param String
     * @returns void
     */
    function setExternUrl( $extern_url )
    {
      $this->extern_url = $extern_url;
      $sql = "UPDATE
                ".$this->table."
              SET
                extern_url = '". addslashes( trim( $extern_url ) ) ."'
              WHERE
                id= ". $this->id;
      $this->dba->exec( $sql );
    }

    /**
     * Update the forms submit buttom label 
     * @param String
     * @returns void
     */
    function setSubmitLabel( $label )
    {
      $this->submit_label = $label;
      $sql = "UPDATE
                ".$this->table."
              SET
                submit_label = '". addslashes( trim( $label ) ) ."'
              WHERE
                id= ". $this->id;
      $this->dba->exec( $sql );
    }

    /**
     * Update the forms cancel buttom label 
     * @param String
     * @returns void
     */
    function setCancelLabel( $label )
    {
      $this->cancel_label = $label;
      $sql = "UPDATE
                ".$this->table."
              SET
                cancel_label = '". addslashes( trim( $label ) ) ."'
              WHERE
                id= ". $this->id;
      $this->dba->exec( $sql );
    }

    /**
     * Build a list of all the forms
     * @returns mixedArray
     */
    function getForms()
    {
        $sql = "SELECT
                    id,
                    name,
										action_type
                FROM
                    ".$this->table;
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )  $this->list[$i] = $this->dba->fetchArray( $result );

        return $this->list;            
    }

    /**
     * Add a new form and returns the new forms id
     * @returns int 
     */
    function addForm()
    {
        $sql = "INSERT INTO
                  ".$this->table."
                (
                  name
                )
                VALUES
                (
                  'New form'
                )";
        $this->dba->exec( $sql );
        return $this->dba->last_inserted_id();
    }

    /**
     * Deletes a form
     * @returns void
     */
    function remove( $id )
    {
        if( !is_numeric( $id ) ) return;
        $sql = "DELETE FROM
                  ".$this->table."
                WHERE
                  id= $id";
        $this->dba->exec( $sql );
    }
    function getCellnumber( $templateName )
    {
        $f = explode(".",$templateName );
        $ext = $f[ count( $f ) - 1 ];
        $cell = str_replace( "TMP", "", $ext );
        return  is_numeric( $cell )? $cell:1;
    }

    /**
     * Render the current form as html
     * @returns String
     */
     function render( $fieldArray )
     {
        $cellSlots = 0;
        $cellcount = 0;
        $openRow = false;
        $maxcolums = 4;

        $str = "";
				
				if( !$this->dev ){
					$str.= '<form onsubmit="return validate_form(\'form_'. $this->id .'\')" name="form_'. $this->id .'" method="post" action="';
					$str.= ( $this->action_type =='custom' && trim( $this->extern_url )  )? $this->extern_url : 'processForm.php';
					$str.= '">'."\n";
					$str.= '<input type="hidden" name="confirmation_page" value="'. $this->confirmation_page .'">'."\n";
					$str.= '<input type="hidden" name="id" value="'. $this->id .'">'."\n";

					//javascript validation array
					$str.= '<script language="JavaScript">';
					$str.= 'var fields2validate = new Array();';
					$str.= '</script>';
				}

        $str.= '<table cellspacing="0" cellpadding="2" border="0">'."\n";

        for( $i = 0; $i < count( $fieldArray ); $i++ )
        {
          $field = $fieldArray[$i];

          if( $field['name'] == 'hidden' )
          {
            $str.= '<input type="hidden" name="field_'. $field['id'] .'" ';
            
						if ( $field['default_value'] == "USER_BROWSER" )
						{
							$str.= 'value="'. $_SERVER["HTTP_USER_AGENT"] .'">';
						}
						else if ( $field['default_value'] == "USER_LANGUAGE" )
						{
							$str.= 'value="'. $_SERVER["HTTP_ACCEPT_LANGUAGE"] .'">';
						}
						else if ( $field['default_value'] == "USER_IP" )
						{
							$str.= 'value="'. $_SERVER["REMOTE_ADDR"] .'">';
						}
						else
						{
							$str.= 'value="'. $field['default_value'] .'">';
						}
            $str.= "\n";
            continue;
          }

          if( $cellSlots == $cellcount )
          {
            $layout = ( trim( $field['layout'] ) )? $field['layout']:'single.TMP1';
            $cellSlots = $this->getCellnumber( $layout );
            if( $openRow ) $str.= '</tr>'."\n";
            $openRow = true;
            $str.= '<tr>'."\n";
            $cellcount = 0;
          }
          
          $cellcount++;
          $str.= '<td style="padding-top:3px;padding-bottom:3px;" valign="top" ';
          if( $cellSlots == $cellcount || $i == ( count( $fieldArray ) - 1 )) $str.= ' colspan="'. ( $maxcolums - $cellcount + 1 ) .'" ';
          /*
          if( $i == count( $fieldArray ) - 1 )
          {
          }
          */

          $str.= '>'."\n";
          $str.= $this->renderField( $field );
          $str.= '</td>'."\n";
        }
        if( $openRow ) $str.= '</tr>'."\n";

        //ouput buttons

        $str.= '<tr>';
        $str.= '<td colspan="4" align="right">';

        if (trim( $this->cancel_label ))
				{
					$str.= '<input type="reset" value="';
        	$str.= ( trim( $this->cancel_label ) )? $this->cancel_label:'Reset';
        	$str.= '" class="submit_button_field" />&nbsp;&nbsp;';
				}

        $str.= '<input type="submit" value="';
        $str.= ( trim( $this->submit_label ) )? $this->submit_label:'Submit';
        $str.= '" class="submit_button_field" />';
				
				if ($this->action_type == "newsletter")
				{
					$str.='<p><a href="?action=unsubscribe&formId='.$this->id.'">Unsubscribe</a></p>';
				}
        
        $str.= '</td>';
        $str.= '</tr>';
        $str.= '</table>';
        
        if( !$this->dev ){
					$str.= '</form>';
				}
        return $str;
     }
     function renderTextField( $field ){
     		$str.= '<input type="text" name="field_'.$field['id'] .'" ';
        $str.= ( trim( $field['default_value'] ) )? 'value="'. trim($field['default_value']) .'" ':'';
        $str.= 'class="text_field" ';
        $str.= ( trim( $field['width'] ) )? 'size="'. $field['width'] .'" ':'';
        $str.= ( trim( $field['maxchar'] ) )? 'maxlength="'. $field['maxchar'] .'" ':'';
        $str.= ' />';
        return $str;
     }
     function renderTextArea( $field )
     {
        $str.= '<textarea name="field_'. $field['id'] .'" ';
        $str.= 'cols="';
        $str.= ( trim( $field['width'] ) )? $field['width']:35;
        $str.= '" rows=';
        $str.= ( trim( $field['height'] ) )? $field['height']:8;
        $str.= '" class="textarea_field">';
        $str.= trim( $field['default_value'] );
        $str.= '</textarea>';
        return $str;
     }
     function renderCountryField( $field )
     {
        $country = new country_widget( $this->dba );
        $selected_country = ( $field['default_value'] )?$field['default_value']:'';
        $str = $country->render( $field['id'], $selected_country ); 
        return $str;
     }
     function renderPostCodeField( $field )
     {
         $postnr = ( $field['default_value'] )?$field['default_value']:'';
         $post = new postcode_widget( $this->dba );
         $str = $post->render( $field['id'], $postnr );
         return $str;
     }
     function renderDateField( $field )
     {
        $d = explode(".",$field['default_value'] );
        $date = new date_widget($field['id'],$d[0],$d[1],$d[2] );
        $str = $date->render();
        return $str;
     }
     function renderTimeField( $field )
     {
        $d = explode(":",$field['default_value'] );
        $date = new date_widget($field['id'],'','','',$d[0],$d[1],$d[2] );
        $str = $date->renderTime();
        return $str;
     }
     function renderDateTimeField( $field )
     {
        $datetime = explode(" ",$field['default_value'] );
        $d     = explode(".",$datetime[0] );
        $t     = explode(":",$datetime[1] );

        $date = new date_widget( $field['id'],$d[0],$d[1],$d[2],$t[0],$t[1],$t[2] );
        $str = $date->renderDateTime();
        return $str;
     }
     function renderListField( $field )
     {
        $this->fields->id = $field['id'];
        $listItems = $this->fields->getListValues( $this->id );

        $str.= '<select multiple class="input" size="';
        $str.= ( trim( $field['height'] ) )? $field['height']:5;
        $str.= '" name="field_'. $field['id'].'[]">';
        $str.= '<option></option>';
        for( $i = 0; $i < count( $listItems ); $i++ )
        {
          $str.= '<option ';
          $str.= ( $listItems[$i]['selected'] == 'y' )?'selected':'';
          $str.= '>';
          $str.= $listItems[$i]['listvalue'];
          $str.= '</option>'."\n";
        }
        $str.= '</select>';
        return $str;
     }
     function renderComboField( $field )
     {
        $this->fields->id = $field['id'];
        $listItems = $this->fields->getListValues( $this->id );

        $str.= '<select class="input" ';
        $str.= '" name="field_'. $field['id'].'">';
        $str.= '<option></option>';
        for( $i = 0; $i < count( $listItems ); $i++ )
        {
          $str.= '<option ';
          $str.= ( $listItems[$i]['selected'] == 'y' )?'selected':'';
          $str.= '>';
          $str.= $listItems[$i]['listvalue'];
          $str.= '</option>'."\n";
        }
        $str.= '</select>';
        return $str;
     }
     function renderRadioField( $field )
     {
        $this->fields->id = $field['id'];
        $listItems = $this->fields->getListValues( $this->id );

        for( $i = 0; $i < count( $listItems ); $i++ )
        {
          $str.= '<input type="radio" name="field_'.$field['id'] .'" ';
          $str.= ( $listItems[$i]['selected'] == 'y' )?'checked':'';
          $str.= ' value="'.$listItems[$i]['listvalue'] .'" ';
          $str.= '>';
          $str.= $listItems[$i]['listvalue'];
          $str.='<br />';
        }
        return $str;
     }

     function renderCheckBoxField( $field )
     {
        $this->fields->id = $field['id'];
        $listItems = $this->fields->getListValues( $this->id );

        for( $i = 0; $i < count( $listItems ); $i++ )
        {
          $str.= '<div style="float:left;"><input type="checkbox" name="field_'.$field['id'] .'[]" ';
          $str.= ( $listItems[$i]['selected'] == 'y' )?'checked':'';
          $str.= ' value="'.$listItems[$i]['listvalue'] .'" ';
          $str.= '></div><div>';
          $str.= $listItems[$i]['listvalue'];
          $str.='</div><br />';
        }
        return $str;
     }
     function renderField( $field )
     {
        if( trim( $field['label'] ) )
        {
          $str = '<span class="field_label">';
          $str.= $field['label'];
          $str.= '</span>';
        }
        if( $field['required'] == 'y' )
        {
          $str.= '<span style="color:#cc3300">*</span>';
          $str.= '<script language="JavaScript">';
          $str.= 'fields2validate[ fields2validate.length ] =';
          $str.= 'new Array(\'field_'. $field['id'] .'\',\'';
          $str.= $field['name'] .'\',\'';
          $str.= (trim( $field['label'] ) )? $field['label']:$field['name'];
          $str.= '\');';
          $str.= '</script>';
        }
        if( $str ) $str.= '<br />';

        switch( $field['name'] )
        {
          case( 'text' ):
            $str.= $this->renderTextField( $field );
            break;
          case( 'number' ):
            $str.= $this->renderTextField( $field );
            break;
          case( 'mail' ):
            $str.= $this->renderTextField( $field );
            break;
          case( 'textarea' ):
            $str.= $this->renderTextArea( $field );
            break;
          case( 'country' ):
            $str.= $this->renderCountryField( $field );
            break;
          case( 'postcode'):
            $str.= $this->renderPostCodeField( $field );
            break;
          case( 'date'):
            $str.= $this->renderDateField( $field );
            break;
          case( 'time'):
            $str.= $this->renderTimeField( $field );
            break;
          case( 'datetime'):
            $str.= $this->renderDateTimeField( $field );
            break;
          case( 'list' ):
            $str.= $this->renderListField( $field );
            breaK;
          case( 'combobox' ):
            $str.= $this->renderComboField( $field );
            breaK;
          case( 'radio' ):
            $str.= $this->renderRadioField( $field );
            breaK;
          case( 'checkbox' ):
            $str.= $this->renderCheckBoxField( $field );
            breaK;
        }
        return $str;
     }
     function insertRecord( $keys )
     {
        if( !is_array( $keys ) ) return; 

        $sql = "INSERT INTO 
                  ". $this->p ."formdata
                ( 
                  formid,
                  submited
                )
                VALUES
                (
                  ". $this->id .",
                  NOW()
                )";
        $this->dba->exec( $sql );
        $requestId =  $this->dba->last_inserted_id();
        
        for( $i = 0; $i < count( $keys ); $i++ )
        {
          if( $values ) $values.= ',';
          $values.= '('. $requestId .',';
          $values.= "'". addslashes( $keys[$i]['label'] )."',";
          $values.= "'".addslashes( $keys[$i]['title'] ) ."',";
          $values.= "'";
          $values.= ( is_array( $keys[$i]['postValue'] ) )? addslashes( implode(',', $keys[$i]['postValue'] ) ):addslashes( $keys[$i]['postValue'] );
          $values.= "')";
        }

        $sql = "INSERT INTO
                  ". $this->p. "fielddata
                (
                  requestId,
                  fieldName,
									fieldType,
                  fieldValue
                )
                VALUES
                $values
              ";
        if( $values ) $this->dba->exec( $sql );
     }
     function getFormData( $index = 0, $antal = 10, $orderBy = 'default' )
     {
        if ($orderBy == 'default') $orderBy = 'f.submited, fd.fieldName';
				
				$sql = "SELECT 
                  f.requestId   AS request,
                  f.submited    AS submited,
                  fd.fieldName  AS fieldname,
									fd.fieldType  AS fieldtype,
                  fd.fieldValue AS fieldvalue,
									fd.requestId	AS requestid
                FROM 
                  ". $this->p ."formdata AS f, 
                  ". $this->p ."fielddata AS fd
                WHERE 
                  f.requestId = fd.requestId 
                AND 
                  f.formId = ". $this->id ." 
                ORDER BY
									".$orderBy;
//				echo $sql;
				
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        $rec_number = 0;
        for(  $i = 0; $i < $n; $i++ )
        {
          $record   = $this->dba->fetchArray( $result );
          if( $record['request'] != $recordId )
          {
            //this is a new record
            $rec_number++;
            $recordId = $record['request'];
          }
          if( $antal == 'all' || ( $rec_number > $index && ( $rec_number <= ( $index + $antal )) ) )
          {
            $formData[ $record['request'] ][ count( $formData[ $record['request'] ] ) ] = $record;
          }
        }
        return $formData;
     }
		 
		 function deleteFormData($deleteid)
		 {
		 		if( !is_numeric( $deleteid ) ) return; 
				
				$sql = "DELETE FROM 
									". $this->p ."fielddata
								WHERE
									requestId = ".$deleteid;
				$this->dba->exec( $sql );
				
				$sql = "DELETE FROM 
									". $this->p ."formdata
								WHERE
									requestId = ".$deleteid;
				$this->dba->exec( $sql );
		 }
		 
		 function checkDuplicateMail($formid, $email)
		 {
				if (!$formid) return false;
				if (!$email) return false;
				$sql = "SELECT
									fd.requestId,
									fd.fieldType,
									fd.fieldValue,
									f.requestId,
									f.formId
								FROM
									". $this->p ."fielddata AS fd,
									". $this->p ."formdata AS f
								WHERE
									f.formId = ".$formid."
								AND
									f.requestId = fd.requestId
								AND
									fd.fieldValue = '".$email."'";
					$record = $this->dba->singleQuery($sql);
					return $record;
		 }
		 
		 function deleteMailFromMaillist($email, $formId)
		 {
				if (!$formId) return false;
				if (!$email) return false;
				$sql = "SELECT
									fd.requestId,
									fd.fieldType,
									fd.fieldValue,
									f.requestId,
									f.formId
								FROM
									". $this->p ."fielddata AS fd,
									". $this->p ."formdata AS f
								WHERE
									f.formId = ".$formId."
								AND
									f.requestId = fd.requestId
								AND
									fd.fieldValue = '".$email."'";
					$record = $this->dba->singleQuery($sql);
				
				if (!$record)
				{
					return false;
				}
				else
				{
					$sql = "DELETE FROM 
										". $this->p ."fielddata
									WHERE
										requestId = ".$record[0];
					$this->dba->exec( $sql );
					
					$sql = "DELETE FROM 
										". $this->p ."formdata
									WHERE
										requestId = ".$record[0];
					$this->dba->exec( $sql );
					
					return true;
				}
		 }
}
?>
