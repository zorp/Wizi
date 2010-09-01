<?php
/**
 * Class which maps to the comment tables representing a document
 * whith all it's fields
 * @author Rasmus
 */
class comments
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
		* Unique document id and the table primary key 
		* @type int
		*/
		var $id;
		
		/**
		* Count value of comments for current document 
		* @type int
		*/
		var $commentNumber;
		
		/**
		* String variables for storing comment data 
		* @type string
		*/
		var $name;
		var $email;
		var $comment;

		function comments( $dba, $id=1 )
		{
			$this->dba = $dba;
			$this->p   = $this->dba->getPrefix();
			$this->id  = $id;
		}
		
		function countComments()
		{
			$sql = "SELECT
										COUNT(id)
							FROM
                  	".$this->p."comment
							WHERE
										pageid = ".$this->id;

			$this->commentNumber = $this->dba->singleQuery( $sql );
		}
		
		function getComments()
    {
       $sql = "SELECT
                  id,
                  name,
									email,
									comment,
                  DATE_FORMAT(datetime,'%M %D, %Y at %T') AS datetime
                FROM
                  ".$this->p."comment
                WHERE
               			pageid = ".$this->id."
                ORDER BY
                  	datetime ASC";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
          $pagecomments[$i] = $this->dba->fetchArray($result);
					$pagecomments[$i]["name"] = stripslashes ( $pagecomments[$i]["name"] );
					$pagecomments[$i]["email"] = stripslashes ( $pagecomments[$i]["email"] );
					$pagecomments[$i]["comment"] = stripslashes ( $pagecomments[$i]["comment"] );
					$pagecomments[$i]["datetime"] = $pagecomments[$i]["datetime"];
        }
        return $pagecomments;
    }
		
		function addComment()
		{
			$this->comment = strip_tags($this->comment);
			$this->comment = str_replace("\r","<br>",$this->comment);
			
			$sql = "INSERT INTO
									".$this->p."comment
			            (
										pageid,
										name,
										email,
										comment,
										datetime
									)
									VALUES
									(
										".$this->id.",
										'".trim(addslashes($this->name))."',
										'".trim(addslashes($this->email))."',
										'".trim(addslashes($this->comment))."',
										NOW()
									)";
			$this->dba->exec( $sql );
		}
		
		function updateComment($id,$name,$email,$comment)
		{
			$comment = strip_tags($comment);
			$comment = str_replace("\r","<br>",$comment);
			
			$sql = "UPDATE
									".$this->p."comment
							SET
										name = '".trim(addslashes($name))."',
										email = '".trim(addslashes($email))."',
										comment = '".trim(addslashes($comment))."'
							WHERE
										id = $id";
			
			$this->dba->exec( $sql );
		}
		
		function deleteComment($id)
		{
			if( !is_numeric( $id ) ) return;
			
			$sql = "DELETE FROM
									".$this->p."comment
							WHERE
										id = $id";
			
			$this->dba->exec( $sql );
		}

}//END CLASS
?>