<?php
/**
 * Class which maps the fields table to an object
 * @author Ronald
 */
class fields
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
     * Identifier for field record
     * @type int
     */
    var $id;

    /**
     * Pivot table for joining a form with it's corresponding fields
     * @type String
     */
    var $fields2forms;

    /**
     * Table for the different types of fields
     * @type String
     */
    var $fieldtypes;

    /**
     * Hash of id's an field type titles 
     * @type mixedArray
     */
    var $fieldTypeTitleById;

    /**
     * Hash of id's an field type names
     * @type mixedArray
     */
    var $fieldTypeNameById;

    /**
     * List of all field types 
     * @type mixedArray
     */
    var $typelist;

    /**
     * List of all the records
     * @type mixedArray
     */
    var $list;

    var $listvalues;

    /**
     * Id of the form this fields belong to
     * @type int
     */
     var $formid;

     var $type;
     var $label;
     var $maxchar;
     var $default_value;
     var $layout;
     var $required;
     var $width;
     var $height;


    /**
     * Constructor for forms class
     * @param dba dba - Database abstraction layer
     */
    function fields( $dba, $formid )
    {
        if( !is_numeric( $formid ) ) return false;
        $this->dba   = $dba;
        $this->p     = $this->dba->getPrefix();
        $this->table = $this->p ."fields"; 
        $this->fields2forms = $this->p."fields2forms";
        $this->fieldtypes  = $this->p."fieldtypes";
        $this->listvalues  = $this->p."listvalues";
        $this->formid = $formid;
    }
    function loadField( $id )
    {
      if( !is_numeric( $id ) ) return;
      $this->id = $id;
      $sql = "SELECT
                label,
                fieldtype,
                default_value,
                layout,
                required,
                maxchar,
                width,
                height
              FROM
                ". $this->table ."
              WHERE
                id = ". $this->id;
      $rec = $this->dba->singleArray( $sql );
      $this->label   = stripslashes( $rec["label"] );
      $this->type    = stripslashes( $rec["fieldtype"] );
      $this->layout        = stripslashes( $rec["layout"] );
      $this->default_value = stripslashes( $rec["default_value"] );
      $this->required      = stripslashes( $rec["required"] );
      $this->maxchar = $rec["maxchar"];
      $this->width   = $rec["width"];
      $this->height  = $rec["height"];
    }

    function makeMailfield($formId)
		{
			//check if form already has mail field
			$sql = "SELECT
									f.id AS id,
									f.fieldtype AS fieldtype,
									f2f.form AS form,
									f2f.field AS field
							FROM
                ". $this->table ." AS f,
								".$this->fields2forms." AS f2f
              WHERE
                form = ". $formId . "
							AND
								id = field";
			$result = $this->dba->exec( $sql );
      $n	= $this->dba->getN( $result );
			
			if (!$n)
			{
				//add field
				$id = $this->addField( $formId );
				//set the field
		    $this->id = $id;
				$this->setType( '6' );
		    $this->setLabel( 'E-mail:' );
				$this->setRequired( 'on' );
			}
		}
		
		
		function addField( $formId )
    {
      if( !is_numeric( $formId ) ) return;
      $sql = "INSERT INTO ".$this->table." ( label ) VALUES ( NULL )";
      $this->dba->exec( $sql );
      $this->id = $this->dba->last_inserted_id();

      //get position
      $sql = "SELECT MAX(position) FROM ".$this->fields2forms ." 
              WHERE form = $formId ";
      $position = $this->dba->singleQuery( $sql );
      $position++;

      //insert into pivot table
      $sql = "INSERT INTO ".$this->fields2forms ." ( field, form, position ) 
              VALUES( ". $this->id .",$formId,$position )";
      $this->dba->exec( $sql );

      return $this->id;
    }

    /**
     * Remove the field from the form
     * @param int id -field identifier
     * @returns void
     */
    function remove( $id )
    {
      if( !is_numeric( $id ) ) return;
      $sql = "DELETE FROM
              ". $this->table ."
              WHERE
                id = $id";
      $this->dba->exec( $sql );
      $sql = "DELETE FROM
              ". $this->fields2forms ."
              WHERE
                field = $id";
      $this->dba->exec( $sql );
    }

    /**
     * Change the order of the fields in the current form
     * Move the requested field up
     * @param int id -field identifier
     * @returns void
     */
    function moveUp( $id )
    {
      if( !is_numeric( $id ) ) return;

      $sql = "SELECT
                field AS id,
                position
              FROM
                ".$this->fields2forms ." 
              WHERE
                form = ". $this->formid ."
              ORDER BY
                position";

      $result = $this->dba->exec( $sql );
      $n	= $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
          $record = $this->dba->fetchArray( $result );
          $record["position"] = ( $i + 1 );

          if( $record["id"]== $id && $previousRecord )
          {
              $sql = "UPDATE
                          ".$this->fields2forms ." 
                      SET
                          position=". $previousRecord["position"] ."
                      WHERE
                          field = ". $record["id"] ."
                      AND
                         form = ". $this->formid;
              $this->dba->exec( $sql );

              $sql = "UPDATE
                          ".$this->fields2forms ." 
                      SET
                          position=". $record["position"] ."
                      WHERE
                          field = ". $previousRecord["id"] ."
                      AND
                         form = ". $this->formid;
              $this->dba->exec( $sql );

              return;    
          }
          else
          {
              $sql = "UPDATE
                          ".$this->fields2forms ." 
                      SET
                          position=". ( $i + 1 ) ."
                      WHERE
                          field = ". $record["id"] ."
                      AND
                         form = ". $this->formid;
              $this->dba->exec( $sql );
          }
          $previousRecord = $record;
      }
    }

    /**
     * Change the order of the fields in the current form
     * Move the requested field down 
     * @param int id -field identifier
     * @returns void
     */
    function moveDown( $id )
    {
      if( !is_numeric( $id ) ) return;

      $sql = "SELECT
                  field AS id,
                  position
              FROM
                  ".$this->fields2forms ." 
              WHERE
                 form = ". $this->formid ."
              ORDER BY
                  position";

      $result = $this->dba->exec( $sql );
      $n	= $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
          $record = $this->dba->fetchArray( $result );
          $record["position"] = $i + 1;

          if( $nextRecord )
          {
            $sql = "UPDATE
                    ".$this->fields2forms ." 
                  SET
                      position=". $nextRecord["position"] ."
                  WHERE
                      field = ". $record["id"] ."
                  AND
                     form = ". $this->formid;
            $this->dba->exec( $sql );

            $sql = "UPDATE
                    ".$this->fields2forms ." 
                  SET
                      position=". $record["position"] ."
                  WHERE
                      field = ". $nextRecord["id"] ."
                  AND
                     form = ". $this->formid;
                  $this->dba->exec( $sql );
                  unset( $nextRecord );
          }
          else
          {
            $sql = "UPDATE
                      ".$this->fields2forms ." 
                    SET
                        position=". ( $i + 1 ) ."
                    WHERE
                        field = ". $record["id"] ."
                    AND
                       form = ". $this->formid;
            $this->dba->exec( $sql );
          }

          if( $record["id"]== $id ) $nextRecord = $record;
      }
    }

    /**
     * Set a given type for the current field
     * @param int type -field type identifier 
     * @returns void
     */
    function setType( $type )
    {
      if( !is_numeric( $type ) ) return;

      $this->type = $type;
      $sql = "UPDATE
                ".$this->table."
              SET
                fieldtype = $type
              WHERE
                id = ".$this->id;
      $this->dba->exec( $sql );
    }

    /**
     * Set text label for the current field
     * @param  String label -Text for the label
     * @returns void
     */
    function setLabel( $label )
    {
      $this->label = ( trim( $label ) )? "'". addslashes( trim( $label ) ) ."'":"NULL";
      $sql = "UPDATE
                ".$this->table."
              SET
                label = ". $this->label ."
              WHERE
                id = ".$this->id;
      $this->dba->exec( $sql );
    }

    /**
     * Set the maximun numbers of charachters accepted by the field
     * @param  int maxchar -Maximun number of characters accepted for the field
     * @returns void
     */
    function setMaxChar( $maxchar )
    {
      if( !is_numeric( $maxchar ) ) return;
      $this->maxchar = $maxchar;
      $sql = "UPDATE
              ".$this->table ."
             SET
              maxchar= $maxchar
            WHERE
              id = ".$this->id;
      $this->dba->exec( $sql );
    }
    function setDefaultValue( $default_value ='')
    {
      $this->default_value = ( trim( $default_value ) )? "'". addslashes( trim( $default_value ) ) ."'":"NULL";
      $sql = "UPDATE
              ".$this->table ."
             SET
              default_value = ". $this->default_value ."
            WHERE
              id = ".$this->id;
      $this->dba->exec( $sql );
    }
    function setLayout( $selectedLayout )
    {
      if( !trim( $selectedLayout ) ) return;
      $this->layout = ( trim( $selectedLayout ) )? "'". addslashes( trim( $selectedLayout ) ) ."'":"NULL";
      $sql = "UPDATE
              ".$this->table ."
             SET
              layout = ". $this->layout ."
            WHERE
              id = ".$this->id;
      $this->dba->exec( $sql );
    }
    function setRequired( $required )
    {
      $required = ( $required == 'on' )? 'y':'n';
      $sql = "UPDATE
              ".$this->table ."
             SET
              required = '$required'
            WHERE
              id = ".$this->id;
      $this->dba->exec( $sql );
    }
    function setWidth( $width )
    {
      if( !is_numeric( $width ) ) return;
      $sql = "UPDATE
              ".$this->table ."
             SET
              width = $width
            WHERE
              id = ".$this->id;
      $this->dba->exec( $sql );
    }
    function setHeight( $height )
    {
      if( !is_numeric( $height ) ) return;
      $sql = "UPDATE
              ".$this->table ."
             SET
              height = $height
            WHERE
              id = ".$this->id;
      $this->dba->exec( $sql );
    }
    function getListValues( $formId )
    {
      if( !is_numeric( $formId ) ) return;
      $sql = "SELECT
                listvalue,
                selected
              FROM
                ".$this->listvalues."
              WHERE
                fieldId =". $this->id."
              AND
                formId =". $formId ."
							ORDER BY listvalue";
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $temp[$i] = $this->dba->fetchArray( $result );
      }
      return $temp;           
    }
    function setListValues( $formId, $listvalues, $selectedlistvalues, $use_default_value )
    {
      if( !is_numeric( $formId ) ) return;
      $listvalues = explode(',', $listvalues );  
      $selectedlistvalues = explode(',', $selectedlistvalues );  
      if( !is_array( $selectedlistvalues ) ) $selectedlistvalues = array();

      //first remove all previous entries for this field
      $sql = "DELETE FROM ".$this->listvalues." WHERE fieldId = ".$this->id;
      $this->dba->exec( $sql );

      if( is_array( $listvalues ) && count( $listvalues ) > 0 )
      {
        for( $i = 0; $i < count( $listvalues ); $i++ )
        {
          $item = $listvalues[$i];
          $selected = ( $use_default_value && in_array( $item, $selectedlistvalues ) )?'y':'n';
          $sql = "INSERT INTO ". $this->listvalues ."
                  ( 
                    fieldId,
                    formId,
                    listvalue,
                    selected
                  )
                  VALUES
                  (
                    ".$this->id.",
                    ".$formId.",
                    '". addslashes( trim( $item ) ) ."',
                    '". $selected ."'
                  )";
          $this->dba->exec( $sql );
        }
      }
    }
    

    /**
     * Build a list of all the field types
     * @returns mixedArray
     */
     function getTypeList()
     {
        $sql = "SELECT 
                  id,
                  name,
                  title
                FROM
                  ".$this->fieldtypes;
        
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ ) 
        {
          $rec = $this->dba->fetchArray( $result );
          $this->fieldTypeTitleById[ $rec["id"] ] = $rec["title"];
          $this->fieldTypeNameById[ $rec["id"] ] = $rec["name"];
          $this->typelist[$i] = $rec;
        }
        return $this->typelist;            
     }

    
    /**
     * Build a list of all the fiedls
     * @returns mixedArray
     */
    function getFields()
    {
        $sql = "SELECT
                    f.id,
                    f.label,
                    f.fieldtype,
                    f.default_value,
                    f.layout,
                    f.required,
                    f.fvalues,
                    f.maxchar,
                    f.width,
                    f.height,
                    ft.name,
                    ft.title
                FROM
                    ".$this->table ."       AS f,
                    ".$this->fields2forms ." AS f2f,
                    ".$this->fieldtypes ."  AS ft
                WHERE
                    f2f.form = ". $this->formid ."
                AND
                    f2f.field = f.id
                AND
                    f.fieldtype = ft.id
                ORDER BY
                    f2f.position";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )  $this->list[$i] = $this->dba->fetchArray( $result );

        return $this->list;            
    }
}
?>
