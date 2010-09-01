<?php
class endUserRole
{
    var $dba;
    var $p;
    var $id;
    var $name;
    var $description;
    var $password;
    var $users;
    var $constrain;
		var $forward;

    function endUserRole( $dba, $id )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->id  = $id;
        $this->getRole();
    }
    function getRole()
    {
        $sql = "SELECT
                    name,
                    description,
                    password,
                    constrain,
		    showLogin
                FROM
                    ".$this->p."end_user_role
                WHERE
                    id=".$this->id;
        $result = $this->dba->exec($sql);
        $record = $this->dba->fetchArray( $result );

        $this->name = stripslashes ( $record["name"] );
        $this->description = stripslashes ( $record["description"] );
        $this->password  = stripslashes ( $record["password"] );
        $this->constrain = $record["constrain"];
        $this->showLogin = $record["showLogin"];
    }
    function setShowLogin( $showLogin )
    {
    	if( !$showLogin ) $showLogin = 'n';
	else $showLogin = 'y';
	$this->showLogin = $showLogin;

        $sql = "UPDATE
                    ".$this->p."end_user_role
                SET
                    showLogin = '$showLogin'
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }
    function setConstrain( $constrain )
    {
        if( !is_numeric( $constrain ) ) return;
        $this->constrain = $constrain;

        $sql = "UPDATE
                    ".$this->p."end_user_role
                SET
                    constrain = $constrain 
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }
    function setName( $name )
    {
        if( !trim( $name ) ) return;
        $this->name = trim( $name );

        $sql = "UPDATE
                    ".$this->p."end_user_role
                SET
                    name = '". trim( addslashes( $name ) ) ."'
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }
    function setPassword( $password )
    {
        if( !trim( $password ) ) return;
        $this->password = trim( $password );

        $sql = "UPDATE
                    ".$this->p."end_user_role
                SET
                    password = '". trim( addslashes( $password ) ) ."'
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }

    function setDescription( $description )
    {
        if( !trim( $description ) ) return;
        $this->description = trim( $description );

        $sql = "UPDATE
                    ".$this->p."end_user_role
                SET
                    description = '". trim( addslashes( $description ) ) ."'
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }
    function setUser( $users )
    {
        $this->users = $users;

        //remove all the current users for this role
        $sql = "DELETE FROM
                    ".$this->p."end_user2role
                WHERE
                    role = ".$this->id;
        $this->dba->exec( $sql );

        if( !array_sum( $users ) ) return;
        for( $i = 0; $i < count( $this->users ); $i++ )
        {
            $sql = "INSERT INTO
                        ".$this->p."end_user2role
                    (
                        role,
                        user
                    )
                    VALUES
                    (
                        ".$this->id.",
                        ".$this->users[$i]."
                    )";
            $this->dba->exec( $sql );
        }
    }
}
?>
