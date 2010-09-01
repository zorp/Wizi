<?php
class endUsers
{
    var $dba;
    var $p;
    var $list;

    function endUsers( $dba )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
    }
    function getUsers( )
    {
        $sql = "SELECT
                    id,
                    name,
                    full_name
                FROM
                    ".$this->p."end_user";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
				{
					$this->list[ count( $this->list ) ] = $this->dba->fetchArray( $result );
					$this->list[ count( $this->list ) - 1 ]["name"] = stripslashes ( $this->list[ count( $this->list ) - 1 ]["name"] );
					$this->list[ count( $this->list ) - 1 ]["full_name"] = stripslashes ( $this->list[ count( $this->list ) - 1 ]["full_name"] );
				}

        return $this->list;
    }
    function addUser( )
    {
        $sql= "INSERT INTO 
                ".$this->p."end_user
               ( 
                    name,
                    password
                )
                VALUES
                (
                    'New user',
                    'change password'
                )";
        $this->dba->exec( $sql );
        $new_id = $this->dba->last_inserted_id();

        //insert them into the guest role
        $sql = "INSERT INTO
                    ".$this->p."end_user2role
                (
                    role,
                    user
                )
                VALUES
                (
                    1,
                    $new_id
                )";
        $this->dba->exec( $sql );

        return $new_id;
    }
    function deleteUser( $id )
    {
        if( !$id || !is_numeric( $id ) ) return false;

        $sql = "DELETE FROM
                    ".$this->p."end_user
                WHERE
                    id=$id";
        $this->dba->exec( $sql );
        return true;
    }
}
?>
