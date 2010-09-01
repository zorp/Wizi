<?php
class endUserRealms
{
    var $dba;
    var $p;
    var $realms;
    var $realms4role;
    var $realm;
    var $role;

    function endUserRealms( $dba, $role  )
    {
        $this->dba   = $dba;
        $this->p     = $dba->getPrefix();
        $this->role  = $role;
    }
    function getDocConstrainsForRole( )
    {
        $sql = "SELECT
                    doc
                FROM
                    ".$this->p."end_user_role_constrains
                WHERE
                    role = ". $this->role;

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $doc = $this->dba->fetchArray( $result );
            $this->realms4role[ $doc["doc"] ] = 1;
        }
        return $this->realms4role;
    }
    function toogleConstrainsForDoc( $docId )
    {
        if( !$docId ) return;

        $sql = "DELETE FROM
                    ".$this->p."end_user_role_constrains
                WHERE
                    role = ". $this->role ."
                AND
                    doc = ". $docId;
        $this->dba->exec( $sql );
        
        if( $this->dba->affectedRows() ) 
        {
            $this->recourseRemoveConstrain( $docId );
            return;
        }
        
        $sql = "INSERT INTO
                    ".$this->p."end_user_role_constrains
                (
                    role,
                    doc
                )
                VALUES
                (
                    ". $this->role .",
                    ". $docId ."
                )";
        $this->dba->exec( $sql );
        $this->recourseAddConstrain( $docId );
    }
    function recourseAddConstrain( $docId )
    {
        if( !$docId ) return;
        $sql = "SELECT 
                    id
                FROM
                    ".$this->p."tree
                WHERE
                    parent = $docId";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i  < $n; $i++ )
        {
            $rec = $this->dba->getRecord( $result );

            $sql = "INSERT INTO
                        ".$this->p."end_user_role_constrains
                    (
                        role,
                        doc
                    )
                    VALUES
                    (
                        ". $this->role .",
                        ". $rec[0] ."
                    )";
            $this->dba->exec( $sql );
            $this->recourseAddConstrain( $rec[0] );
        }
    }
    function recourseRemoveConstrain( $docId )
    {
        if( !$docId ) return;
        $sql = "SELECT 
                    id
                FROM
                    ".$this->p."tree
                WHERE
                    parent = $docId";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i  < $n; $i++ )
        {
            $rec = $this->dba->getRecord( $result );

            $sql = "DELETE FROM
                        ".$this->p."end_user_role_constrains
                    WHERE
                        role = ". $this->role ."
                    AND
                        doc = ". $rec[0]; 
            $this->dba->exec( $sql );
            $this->recourseRemoveConstrain( $rec[0] );
        }
    }
}
?>
