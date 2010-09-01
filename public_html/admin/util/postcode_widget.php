<?php
class postcode_widget
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
     * Table name
     * @type String
     */
    var $table;

    function postcode_widget( $dba )
    {
        $this->dba = $dba;
        $this->p   = $dba->getPrefix();
        $this->table = $this->p.'postnr';
    }
    function render( $id = 1, $selected ='',$default_text ='Vælg postnr.')
    {
      $sql = "SELECT 
                fr_gr,
                byen,
                gade,
                postnr
              FROM
                ".$this->table."
							ORDER BY postnr";
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );

      $str = '<select name="field_'. $id .'" class="input">'."\n";
      $str.= '<option value="0">'. $default_text .'</option>'."\n";
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        $str.= '<option value="'. $rec["postnr"] .'-'. $rec["byen"] .'" ';
        $str.= ($selected == $rec["postnr"] )?'selected':'';
        $str.= '>';
        $str.= $rec["postnr"];
				$str.= ( $rec["fr_gr"] )? ', '.$rec["fr_gr"]:'';
        $str.= ( $rec["byen"] )? ', '.$rec["byen"]:'';
        $str.= ( $rec["gade"] )? ', '.$rec["gade"]:'';
        //$str.= $rec["postnr"];
        $str.= '</option>'."\n";
      }
      $str.= '</select>';
      return $str;
    }
}
    //usage
    //$widget = new date_widget("test",3,1,2000);
    //echo $widget->render();
?>