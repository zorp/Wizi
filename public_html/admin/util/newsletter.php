<?php
/**
 * Class which maps the media table to an object
 * @author Ronald
 */
class newsletter
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
     * Name of the relational table to be mapped for subscribers
     * @type String
     */
		var $subscribertable;
		
		/**
     * Name of the relational table to be mapped for data
     * @type String
     */
		var $datatable;

    /**
     * Unique media id and the table primary key 
     * @type int
     */
    var $id;
    
    /**
     * @type String
     */
    var $body;
		
		/**
    * @type String
    */
    var $plainbody;
		
    /**
     * @type String
     */
    var $subject;
		
		/**
		 * Status that indivcates if mail has been sent or not.
     * @type int
     */
    var $status;
    
    /**
     * Media name
     * @type String
     */
    var $name;

    function newsletter( $dba, $id = 0 )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->table = $this->p."newslettertree";
				$this->subscribertable = $this->p."newsletter_subscribers";
				$this->datatable = $this->p."newsletter_data";

        if( $id ) $this->id  = $id;
    }
    function createMedia()
    {
        global $user;
        $uid = $user->id;
        if( !$uid ) $uid = 1;

        $sql ="INSERT INTO
                    ".$this->table."
                (
                    name,
                    creator,
                    created,
                    parent
                )
                VALUES
                (
                    'Untitled',
                    $uid,
                    NOW(),
                    1
                )";
        $this->dba->exec( $sql );
        $this->id = $this->dba->last_inserted_id();
    }
    function loadProperties()
    {
        $sql = "SELECT
                   name,
                   body,
									 plainbody,
                   subject,
                   format,
                   size,
									 DATE_FORMAT(status,'%d/%m-%Y') AS status
                FROM
                    ".$this->table."
                WHERE 
                    id= ".$this->id;

        $record = $this->dba->singleArray( $sql );

        $this->name        = stripslashes ( $record["name"] );
        $this->body  			 = stripslashes ( $record["body"] );
				$this->plainbody   = stripslashes ( $record["plainbody"] );
				$this->subject		 = stripslashes ( $record["subject"] );
        $this->format      = stripslashes ( $record["format"] );
        $this->size        = $record["size"];
				$this->status      = $record["status"];
    }
    function setName( $name )
    {
        if( !trim( $name ) ) return;
        $this->name = stripslashes( $name );
        $sql = "UPDATE
                    ".$this->table."
                SET
                    name = '". addslashes( trim( $name ) ) ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
    function setBody( $body )
    {
        if( !trim( $body ) ) return;
        $this->body= stripslashes( $body );
        $sql = "UPDATE
                    ".$this->table."
                SET
                    body = '". addslashes( trim( $body ) ) ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		function setPlainBody( $plainbody )
    {
        //if( !trim( $plainbody ) ) return;
        $this->body= stripslashes( $plainbody );
        $sql = "UPDATE
                    ".$this->table."
                SET
                    plainbody = '". addslashes( trim( $plainbody ) ) ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		function setSubject( $subject )
    {
        if( !trim( $subject ) ) return;
        $this->subject= stripslashes( $subject );
        $sql = "UPDATE
                    ".$this->table."
                SET
                    subject = '". addslashes( trim( $subject ) ) ."'
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		function setStatus()
    {
        $sql = "UPDATE
                    ".$this->table."
                SET
                    status = NOW()
                WHERE
                    id=".$this->id;
        $this->dba->exec( $sql );
    }
		
		function loadStandardData()
		{
			$sql = "SELECT
		                fromname,
										fromemail,
										bounceemail
		          FROM
		                ".$this->datatable;
		
			$result = $this->dba->singleQuery( $sql );
			return $result;
		}
		function setData($fromname, $fromemail, $bounceemail)
    {
        if (!$fromname) $fromname = "Wizi Newsletter";
				if (!$fromemail) $fromemail = "newsletter@wizi.dk";
				if (!$bounceemail) $bounceemail = "newsletter@wizi.dk";
				$sql = "UPDATE
                    ".$this->datatable."
                SET
                    fromname = '".$fromname."',
										bounceemail = '".$bounceemail."',
		  	          	fromemail = '".$fromemail."'";
        $this->dba->exec( $sql );
    }
}
?>
