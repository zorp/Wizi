<?php
class country_widget
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

    function country_widget( $dba )
    {
        $this->dba = $dba;
        $this->p   = $dba->getPrefix();
        $this->table = $this->p.'countries';
    }
    function render( $id = 1, $selected='',$default_text ='Select a country')
    {
      $sql = "SELECT 
                name,
                short_code
              FROM
                ".$this->table;
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );

      $str = '<select name="field_'. $id .'" class="input">'."\n";
      $str.= '<option value="0">'. $default_text .'</option>'."\n";
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        $str.= '<option value="'. $rec["short_code"] .'" ';
        $str.= ( $selected == $rec["short_code"] )?'selected':'';
        $str.= '>';
        $str.= $rec["name"];
        $str.= '</option>'."\n";
      }
      $str.= '</select>';
      return $str;
    }
}
?>
