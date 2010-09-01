<?php
if (!$p) $p = $prefix;

/*****************************  Newsletters Tree *********************************/

$sql = "DROP TABLE IF EXISTS ".$p."newslettertree";
$dba->exec( $sql );

$sql = "CREATE TABLE
							".$p."newslettertree
							(
								  id 						int(10) unsigned NOT NULL auto_increment,
								  name 					varchar(50) default NULL,
								  parent 				int(11) default NULL,
								  position 			int(11) default NULL,
								  body 					text,
								  plainbody			text,
									subject 			text,
								  format 				varchar(10) default NULL,
								  size 					int(11) default NULL,
								  creator 			int(11) default NULL,
								  editor 				int(11) default NULL,
								  created 			datetime default NULL,
								  edited 				datetime default NULL,
								  status 				datetime default NULL,
								  PRIMARY KEY  	(id)
								)";

$dba->exec( $sql );

/*****************************  Newsletter Data *********************************/
$sql = "DROP TABLE IF EXISTS ".$p."newsletter_data";
$dba->exec( $sql );

$sql = "CREATE TABLE 
							".$p."newsletter_data
							(
								  fromname 			varchar(255) default NULL,
								  fromemail			varchar(255) default NULL,
									bounceemail		varchar(255) default NULL
							)";

$dba->exec( $sql );

/*****************************  Newsletters Tree state *********************************/


$sql = "DROP TABLE IF EXISTS ".$p."newslettertree_state";
$dba->exec( $sql );

$sql = "CREATE TABLE
							".$p."newslettertree_state
							(
								  id 						int(11) default NULL,
								  uid 					varchar(200) default NULL,
								  time 					datetime default NULL
							)";

$dba->exec( $sql );


//now dump some data

//insert the root into the document tree
$sql = "INSERT INTO
            ".$p."newslettertree
        (
            name,
            parent,
            created,
            creator
        )
        VALUES
        (
            'Newsletters',
            0,
            NOW(),
            1
        )";
$dba->exec( $sql );

//insert Standard data into data
$sql = "INSERT INTO
            ".$p."newsletter_data
        (
            fromname,
            fromemail,
						bounceemail
        )
        VALUES
        (
            'Wizi Newsletter',
            'newsletter@wizi.dk',
						'bounce@wizi.dk'
        )";
$dba->exec( $sql );