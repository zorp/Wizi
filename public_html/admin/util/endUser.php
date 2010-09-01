<?php
/**
 * Class which maps the endUser table to an object
 * @author Ronald
 */
class endUser
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
     * Flag. Tells if the user is currently logged
     * @type boolean
     */
    var $logged;

    /**
     * Unique user id and the table primary key 
     * @type int
     */
    var $id;

    /**
     * User name
     * @type String
     */
    var $name;

    /**
     * User full name
     * @type String
     */
    var $full_name;

    /**
     * User password ( MD5 crypt )
     * @type String
     */
    var $password;

    /**
     * User mail adress 
     * @type String
     */
    var $mail;

    /**
     * User current session identifier
     * @type String
     */
    var $sessid;

    /**
     * Roles for this user ( role name = role id ) 
     * @type Hashtable
     */
    var $rolesByName;

    /**
     * Roles for this user ( role id= role name  ) 
     * @type Hashtable
     */
    var $rolesById;

    /**
     * Constrains for this user on a document ( constrain, doc )
     * @type mixedArray
     */
    var $constrains;

    /**
     * Flag. Tells if the user is pars of the current role
     * @type boolean
     */
    var $isInRole;

    /**
     * Constructor for endUser class
     * @param dba dba - Database abstraction layer
     * @param id int - Unique user identifier
     */
    function endUser( $dba, $id = 0 )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();

        //tables used by this class
        $this->table          = $this->p .'end_user';
        $this->user2role      = $this->p .'end_user2role';
        $this->sessid = session_id();

        if( $id ) 
        {
            $this->id     = $id;
            $this->logged = $this->loadById();
        }
        else 
        {
            $this->logged = $this->load();
        }
        if( $this->id )$this->getRoles();
    }

    function loadById( )
    {
        $sql = "SELECT 
                    name,
                    full_name,
                    password,
                    mail
                FROM
                    ".$this->table."
                WHERE
                    id=". $this->id;

        $result = $this->dba->exec( $sql );
        $record = $this->dba->fetchArray( $result );
        
        if( !$record ) return false;
        
        $this->name         = stripslashes ( $record["name"] );
        $this->full_name    = stripslashes ( $record["full_name"] );
        $this->password     = stripslashes ( $record["password"] );
        $this->mail         = stripslashes ( $record["mail"] );

        return true;
    }
    function load( )
    {
        $sql = "SELECT 
                    id,
                    name,
                    full_name,
                    password,
                    mail
                FROM
                    ".$this->table."
                WHERE
                    sessid = '". $this->sessid ."'";

        $result = $this->dba->exec( $sql );
        $record = $this->dba->fetchArray( $result );
        
        if( !$record ) return false;
        
        $this->id           = $record["id"];
        $this->name         = stripslashes ( $record["name"] );
        $this->full_name    = stripslashes ( $record["full_name"] );
        $this->password     = stripslashes ( $record["password"] );
        $this->mail         = stripslashes ( $record["mail"] );

        return true;
    }
    function log( $name, $password )
    {
        if( !trim( $name ) )  return false;
        if( !trim( $password ) ) return false;
        $password = md5( $password );

        $sql = "SELECT
                    id
                FROM
                    ".$this->table."
                WHERE
                    name = '". trim( addslashes( $name ) ) ."'
                AND
                    password = '". trim( addslashes( $password ) ) ."'";

        $id = $this->dba->singleQuery( $sql );

        if( ! $id ) return false;
        
        $sql = "UPDATE
                    ".$this->table."
                SET
                    sessid = '". $this->sessid ."',
                    sessionStart = NOW()
                WHERE
                   id = $id"; 
        $this->dba->exec( $sql );

        $this->logged = $this->load();

        return true;
    }
    function logoff( )
    {
        if( !$this->logged ) return;

        $sql = "UPDATE
                    ".$this->table."
                SET
                    sessid = ''
                WHERE
                   id = ". $this->id; 
        $this->dba->exec( $sql );
        $this->logged = false;
    }
    function isLogged( )
    {
        return $this->logged;
    }
    function setName( $name )
    {
        if( !trim( $name ) ) return;
        $this->name = $name;

        $sql = "UPDATE
                    ".$this->table."
                SET
                    name = '". addslashes( trim( $name ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );
    }

    function setFull_name( $full_name )
    {
        //if( !trim( $full_name ) ) return;
        $this->full_name = $full_name;

        $sql = "UPDATE
                    ".$this->table."
                SET
                    full_name = '". addslashes( trim( $full_name ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );
    }

    function setPassword( $password )
    {
        if( !trim( $password ) ) return;
        $password = md5( $password );
        $this->password = $password;

        $sql = "UPDATE
                    ".$this->table."
                SET
                    password = '". addslashes( trim( $password ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );
    }

    function setMail( $mail )
    {
        //if( !trim( $mail ) ) return;
        $this->mail = $mail;

        $sql = "UPDATE
                    ".$this->table."
                SET
                    mail = '". addslashes( trim( $mail ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );
    }
		function setForward( $forward )
    {
        //if( !trim( $forward ) ) return;
        $this->forward = trim( $forward );

        $sql = "UPDATE
                    ".$this->table."
                SET
                    forward = ". $forward ."
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }
		function getRoleForward( )
    {
        $sql = "SELECT
                    forward
                FROM
                    ".$this->table."
                WHERE
                    id = ". $this->id;

        $result = $this->dba->singleQuery( $sql );
        return $result;
    }
    function getRoles()
    {
        $sql = "SELECT
              r.id,
              r.name
          FROM
              ".$this->p."role as r,
              ".$this->p."user2role  as u2r,
              ".$this->table."  AS u
          WHERE
              u2r.user = u.id
          AND
              u2r.role = r.id
          AND
              u.id = ".$this->id;

          $result = $this->dba->exec( $sql );
          $n	= $this->dba->getN( $result );
          for( $i = 0; $i < $n; $i++ )
          {
              $record = $this->dba->getRecord( $result );
              $this->rolesById[ $record[0] ] = $record[1];
              $this->rolesByName[ $record[1] ] = $record[0];
          }
    }

    function getConstrains( )
    {
    	$sql  ="SELECT 
              rc.doc as doc,
              rs.name  as realmName
                  FROM	
              ". $this->p ."roles_constrains AS rc,
              ". $this->p ."role AS r,
              ". $this->p ."user2role AS u2r,
              ". $this->p ."realms AS rs
            WHERE
              u2r.user = ". $this->id ."
            AND 	
              u2r.role = r.id
            AND 
              rc.role = r.id
            AND 
              rs.id = rc.realm";

          $result = $this->dba->exec( $sql );
          $n	= $this->dba->getN( $result );
          for( $i = 0; $i < $n; $i++ )
          {
            $record = $this->dba->fetchArray( $result );
            $this->constrains[ $record["realmName"] ][ $record["doc"] ] = 1;	
          }
          return $this->constrains;
    }
    function isInRole( $roleid )
    {
        $sql = "SELECT 
                    role
                FROM
                    ".$this->user2role."
                WHERE
                    user = ".$this->id ."
                AND
                    role = $roleid";
        $this->isInRole = $this->dba->singleQuery( $sql );
        return $this->isInRole;
    }
		
		/**
     * Subscribes a user to selected newsfeed
     * @param feeds array - Newsfeed unique identifiers
		 * @param userId int - User unique identifier
     */
		function subscribeNewsfeed( $feeds,$userId )
    {
				if( !$userId ) return;

        $sql = "DELETE FROM
                    ".$this->p."end_user2newsfeed
                WHERE
                    user_id = ". $userId;

        $this->dba->exec( $sql );

				for( $i = 0; $i < count($feeds); $i++ )
        {
					$sql = "INSERT INTO
	                    ".$this->p."end_user2newsfeed
	                (
	                    user_id,
	                    feed_id
	                )
	                VALUES
	                (
	                    ". $userId .",
	                    ". $feeds[$i] ."
	                )";
	        $this->dba->exec( $sql );
				}
    }
		
		/**
     * Accepts an userid and returns array with newsfeeds user subscribes to
		 * @param userId int - User unique identifier
		 * @return array int - Newsfeeds unique identifiers
     */
		function getNewsfeedSubscribtion ( $userId )
		{
			$sql = "SELECT 
                    feed_id
                FROM
                    ".$this->p."end_user2newsfeed
                WHERE
                    user_id = ".$userId;

			$result = $this->dba->exec( $sql );
			$n	= $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
      	$record = $this->dba->fetchArray( $result );
      	$subscribedFeeds[$i] = $record["feed_id"];
      }
      return $subscribedFeeds;
		}
}
?>
