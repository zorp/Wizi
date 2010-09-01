<?php
class endUserRoles
{
    var $dba;
    var $p;
    var $roleList;
    var $itemList;
    var $constrains;
    var $loginBoxNeeded = false;

    function endUserRoles( $dba )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
    }
    function getRoles()
    {
        $sql = "SELECT
                    id,
                    name,
                    description,
										constrain
                FROM
                    ".$this->p."end_user_role";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ ) 
        {
            $this->roleList[ count( $this->roleList ) ] = $this->dba->fetchArray( $result );
						$this->roleList[ count( $this->roleList ) - 1 ]["name"] = stripslashes ( $this->roleList[ count( $this->roleList ) - 1 ]["name"] );
						$this->roleList[ count( $this->roleList ) - 1 ]["description"] = stripslashes ( $this->roleList[ count( $this->roleList ) - 1 ]["description"] );
						$this->roleList[ count( $this->roleList ) - 1 ]["constrain"] = stripslashes ( $this->roleList[ count( $this->roleList ) - 1 ]["constrain"] );
        }
        return $this->roleList;            
    }
    function user2roles( $id = 0 )
    {
        $sql = "SELECT 
                    user.id                         AS 'id',
                    user.name                       AS 'name',
                    user2role.role                  AS 'selected'
                FROM 
                    ".$this->p."end_user AS user 
                LEFT JOIN 
                    ".$this->p."end_user2role  AS user2role 
                ON
                    user2role.user = user.id
                AND 
                    user2role.role = $id ";
        
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $this->itemList[count($this->itemList)] = $this->dba->fetchArray( $result );
					$this->itemList[ count( $this->itemList ) - 1 ]["name"] = stripslashes ( $this->itemList[ count( $this->itemList ) - 1 ]["name"] );
       }
       return $this->itemList;
    }
    function addRole()
    {
        $sql = "INSERT INTO
                    ".$this->p."end_user_role
                (
                    name
                )
                VALUES
                (
                    'new role'
                )";
        $this->dba->exec( $sql );

        return $this->dba->last_inserted_id();
    }
    function deleteRole( $id )
    {
        if( !is_numeric( $id ) ) return;
        $sql = "DELETE FROM
                    ".$this->p."end_user_role
                WHERE
                    id= $id";
        $this->dba->exec( $sql );
    }
    function getRoleConstrains()
    {
        $sql = "SELECT
                    role.id         AS roleId,
                    role.name       AS roleName,
                    role.password   AS rolePassword,
		    						role.showLogin  AS showLogin,
                    constrain.id    AS constrainId,
                    constrain.name  AS constrainName
                FROM
                    ".$this->p."end_user_role as role,
                    ".$this->p."end_user_realms as constrain
                WHERE
                    role.constrain = constrain.id";
        
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $rec = $this->dba->fetchArray( $result );
            if( $rec["showLogin"] !='n' ) $this->loginBoxNeeded = true;
            $this->roleConstrains[ $rec["roleId"] ] = $rec;
        }
        return $this->roleConstrains;
    }
    function getAllConstrains()
    {
        $sql = "SELECT 
                    id,
                    name
                FROM
                    ".$this->p."end_user_realms";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $this->constrains[$i] = $this->dba->fetchArray( $result );
        }
        return $this->constrains;
    }
		function getConstrainName($id)
    {
        if (!is_numeric($id)) return false;
				$sql = "SELECT 
                    name
                FROM
                    ".$this->p."end_user_realms
								WHERE
										id = $id";
        $result = $this->dba->singleQuery( $sql );
        return $result;
    }
}
?>
