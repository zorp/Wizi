<?php
class template
{
    var $dba;
    var $p;
    var $templates;
    var $templatesPath;

    function template( $dba )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->templatesPath = "../../templates/";
    }

    function select( $doc, $template )
    {
        if( !trim( $template ) ) return;
        if( !is_numeric( $doc ) ) return;

        $sql = "UPDATE
                    ".$this->p."tree
                SET
                    template = '". $template ."'
                WHERE
                    id=". $doc;

        $this->dba->exec( $sql );

        //get the childrens
        $sql = "SELECT
                    id
                FROM
                    ".$this->p."tree
                WHERE
                    parent= $doc";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->getRecord( $result ); 
            $this->select( $record[0], $template );
        }
    }

    function getTemplates()
    {
        if( !is_dir( $this->templatesPath ) ) return;
        $handle = opendir( $this->templatesPath );
        if( !$handle ) return;
        while( $file = readdir( $handle ) )
        {

            if( stristr( $file, ".html" ) || stristr( $file, ".php" )  )
            {
                $f = explode(".", $file );
                $this->templates[ count( $this->templates ) ] = $f[0];
            }
        }
        closedir( $handle );
        return $this->templates;
    }
}
