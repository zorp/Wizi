<?php
    /*********************************************************************/
    /*   set_up.php                                                      */
    /*********************************************************************/
    /*   Rasmus Frey        -                                            */
    /*                                                                   */
    /*   V I Z I O N   F A C T O R Y   N E W M E D I A                   */
    /*   Vermundsgade 40C - 2100 København Ø - Danmark                   */
    /*   Tel : +45 39 29  25 11 - Fax: +45 39 29 80 11                   */
    /*   ronald@vizionfactory.dk - www.vizionfactory.dk                  */
    /*                                                                   */
    /*********************************************************************/
    require("../util/dba.php");
    $dba = new dba();
    $prefix = $dba->getPrefix();

    /*****************************  doctree *********************************/
    $sql_str = "DROP TABLE IF EXISTS ".$prefix."tree";
    $dba->exec($sql_str);

    $sql  = "CREATE TABLE
                ".$prefix."tree
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250),
                parent              INTEGER,
                position            INTEGER,
                nav                 INTEGER,
                timepublish         DATETIME,
                timeunpublish       DATETIME,
                content             TEXT,
                description         TEXT,
                meta                TEXT,
                title               TEXT,
								draftcontent        TEXT,
                draftdescription    TEXT,
                draftmeta           TEXT,
                drafttitle          TEXT,
								draftheading        TEXT,
                heading             TEXT,
                creator             INTEGER,
                created             DATETIME,
                template            VARCHAR(50),
		            layout              VARCHAR(50),
                news                CHAR(1),
                fromnews            DATETIME,
                tonews              DATETIME,
                topic               INTEGER,
                edited              DATETIME,
								draftedited					DATETIME,
								isdraft							INTEGER,
								showcomment					CHAR(1)
            )";

    $dba->exec( $sql );
    $status.= $prefix."tree table created<br>";

   //============================== CREATE TREE_STATE TABLE ============================
    $sql= "DROP TABLE IF EXISTS ".$prefix."tree_state";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."tree_state
            (
                id                 INTEGER,
                uid                VARCHAR(200),
                time               DATETIME
            )";

    $dba->exec( $sql );
    $status.= $prefix."tree state table created<br>";

    /*****************************  history ********************************/
    $sql = "DROP TABLE IF EXISTS ". $prefix ."history";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
    		". $prefix ."history
	    (
       id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
       docid		           INTEGER,
       content             TEXT,
       description         TEXT,
       meta                TEXT,
       title               TEXT,
       heading             TEXT,
       edited	             DATETIME,
       editor              INTEGER,
       template            VARCHAR(100),
       layout              VARCHAR(100),
       topic               INTEGER
	    )";

    $dba->exec( $sql );
    $status.= $prefix."history table created<br>";


    /*****************************  mediatree *********************************/
    $sql_str = "DROP TABLE IF EXISTS ".$prefix."mediatree";
    $dba->exec($sql_str);

    $sql = "CREATE TABLE
                ".$prefix."mediatree
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250),
                parent              INTEGER,
                position            INTEGER,
                description         TEXT,
                meta                TEXT,
                format              VARCHAR(10),
                size                INTEGER,
                height              INTEGER,
                width               INTEGER,
                creator             INTEGER,
                editor              INTEGER,
                created             DATETIME,
                edited              DATETIME,
                hightres           INTEGER,
								downloadcount				INTEGER,
								lastdownload				DATETIME
            )";

    $dba->exec( $sql );
    $status.= $prefix."mediatree table created<br>";

    //============================== CREATE MEDIATREE_STATE TABLE ============================
    $sql= "DROP TABLE IF EXISTS ".$prefix."mediatree_state";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."mediatree_state
            (
                id                 INTEGER,
                uid                VARCHAR(200),
                time               DATETIME
            )";

    $dba->exec( $sql );
    $status.= $prefix."mediatree state table created<br>";

    //now dump some data
    //insert the root into the document tree
    $sql = "INSERT INTO
                ".$prefix."tree
            (
                name,
                parent,
		            nav,
                content,
                created,
                creator
            )
            VALUES
            (
                'Home',
		            'y',
                1,
                '<h1>This site is under construction</h1>Please come back later',
                NOW(),
                1
            )";
    $dba->exec( $sql );



    $sql = "INSERT INTO
                ".$prefix."mediatree
            (
                name,
                parent,
                created,
                edited,
                creator,
                editor
            )
            VALUES
            (
                'File Library',
                0,
                NOW(),
                NOW(),
                1,
                1
            )";
    $dba->exec( $sql );

    /***************************** search ********************************/
    $sql_str = "DROP TABLE IF EXISTS ".$prefix."search";
    $dba->exec($sql_str);

    $sql_str = "CREATE TABLE ".$prefix."search(";
    $sql_str.= "word VARCHAR(250) ";
    $sql_str.= ",id INTEGER ";
    $sql_str.= ")";

    $dba->exec($sql_str);
    $status.= $prefix."search table created<br>";
		
		 /***************************** comment ********************************/
    $sql_str = "DROP TABLE IF EXISTS ".$prefix."comment";
    $dba->exec($sql_str);

    $sql_str = "CREATE TABLE ".$prefix."comment(";
    $sql_str.= "id                   INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT";
		$sql_str.= ",pageid							 INTEGER";
		$sql_str.= ",name                VARCHAR(250)";
		$sql_str.= ",email							 VARCHAR(250)";
    $sql_str.= ",comment						 TEXT";
		$sql_str.= ",datetime						 DATETIME";
    $sql_str.= ")";

    $dba->exec($sql_str);
    $status.= $prefix."comment table created<br>";


    /*****************************  label  *******************************/
    $sql_str = "DROP TABLE IF EXISTS ".$prefix."label";
    $dba->exec($sql_str);

    $sql_str = "CREATE TABLE ".$prefix."label( ";
    $sql_str.= "id                   INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT";
    $sql_str.= ",name                VARCHAR(250) ";        // name of label
    $sql_str.= ",uk                  TEXT";        	    // label content english
    $sql_str.= ",dk                  TEXT";                 // label content danish
    $sql_str.= ")";
    $dba->exec($sql_str);

    $status.= $prefix."label table created<br>";

    //dump label data if exists
    if( file_exists("label.sql"))
    {
     	$file = file("label.sql");
	for($i=0;$i<count($file);$i++)
	{
        $prefix;
        $str = $file[$i];
        $sql = str_replace("{prefix}",$prefix,$str);
		$dba->exec( $sql );
	}
    }

    /*****************************  user   *******************************/
    $sql= "DROP TABLE IF EXISTS ".$prefix."a_user";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."a_user
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250),
                full_name           VARCHAR(250),
                password            VARCHAR(250),
                mail                VARCHAR(250),
                language            VARCHAR(20),
                warning             INTEGER,
                sessionTime         INTEGER,
                sessid              VARCHAR(250),
                sessionStart        TIMESTAMP,
		            pane	              VARCHAR(50)
            )";
    $dba->exec( $sql );

    $status.=$prefix."user table created<br>";

    //insert admin user
    $sql= "INSERT INTO
            ".$prefix."a_user
           (
                name,
                password
            )
            VALUES
            (
                'admin',
                '". md5( 'Verk1459') ."'
            )";
    $dba->exec( $sql );

    /*****************************  role *******************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."role";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."role
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250),
                description         TEXT
            )";
    $dba->exec( $sql );

    $status.=$prefix."role table created<br>";

    //create admin role
    $sql = "INSERT INTO
                ".$prefix."role
            (
                name
            )
            VALUES
            (
                'Administrators'
            )";
    $dba->exec( $sql );

    /*************************  user2role *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."user2role";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."user2role
            (
                role     INTEGER,
                user     INTEGER
            )";

    $dba->exec( $sql );

    $status.=$prefix."user2role table created<br>";

    //add admin to admin role
    $sql = "INSERT INTO
                ".$prefix."user2role

            (
                role,
                user
            )
            VALUES
            (
                1,
                1
            )";
    $dba->exec( $sql );

    /*************************  realms *****************************/
    $sql = "DROP TABLE IF EXISTS ". $prefix ."realms";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."realms
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(50)
            )";
    $dba->exec( $sql );

    $status.=$prefix."realms table created<br>";

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Edit' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Rename' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Delete' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Create' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Duplicate' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Move' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Restore' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Remove version' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$prefix."realms( name ) VALUES( 'Properties' )";
    $dba->exec( $sql );


    /*************************  roles_constrains *****************************/
    $sql = "DROP TABLE IF EXISTS ". $prefix ."roles_constrains";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."roles_constrains
            (
                doc               INTEGER,
                role              INTEGER,
                realm             INTEGER
            )";
    $dba->exec( $sql );

    $status.=$prefix."roles_constrains table created<br>";


    //give admin all right over index page
    $sql = "INSERT INTO
            ".$prefix."roles_constrains
            ( doc, role, realm )
            SELECT
              1,
              1,
              id
            FROM ".$prefix."realms";
    $dba->exec( $sql );


    /*****************************  end_user   *******************************/
    $sql= "DROP TABLE IF EXISTS ".$prefix."end_user";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."end_user
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250),
                full_name           VARCHAR(250),
                password            VARCHAR(250),
                mail                VARCHAR(250),
								forward             INTEGER,
                sessid              VARCHAR(250),
                sessionStart        TIMESTAMP
            )";
    $dba->exec( $sql );

    $status.=$prefix."end_user table created<br>";

    /*************************  end_user_constrains *****************************/
    $sql = "DROP TABLE IF EXISTS ". $prefix ."end_user_role_constrains";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."end_user_role_constrains
            (
                doc               INTEGER,
                role              INTEGER
            )";
    $dba->exec( $sql );

    $status.=$prefix."end_user_role_constrains table created<br>";
		
    /*****************************  end_user_role *******************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."end_user_role";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."end_user_role
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250),
                description         TEXT,
                password            VARCHAR(250),
                constrain           INTEGER,
		            showLogin           CHAR(1)
            )";
    $dba->exec( $sql );

    $status.=$prefix."end_user_role table created<br>";


    /*************************  end_user_restrictions *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."end_user_realms";
    $dba->exec( $sql );
    $sql = "CREATE TABLE
                ".$prefix."end_user_realms
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250)
	          )";
    $dba->exec( $sql );

    //insert restrictions
    $sql = "INSERT INTO ".$prefix."end_user_realms ( name ) VALUES ('Role password')";
    $dba->exec( $sql );
    $sql = "INSERT INTO ".$prefix."end_user_realms ( name ) VALUES ('Free registration')";
    $dba->exec( $sql );
    $sql = "INSERT INTO ".$prefix."end_user_realms ( name ) VALUES ('Registration by administrator')";
    $dba->exec( $sql );

    /*************************  end_user2role *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."end_user2role";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."end_user2role
            (
                role     INTEGER,
                user     INTEGER
            )";

    $dba->exec( $sql );

    $status.=$prefix."end_user2role table created<br>";

		/*************************  end_user2newsfeed *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."end_user2newsfeed";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
                ".$prefix."end_user2newsfeed
            (
                user_id     INTEGER,
                feed_id     INTEGER
            )";

    $dba->exec( $sql );

    $status.=$prefix."end_user2newsfeed table created<br>";

    /*************************  includes *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."includes";
    $dba->exec($sql);

    $sql = "CREATE TABLE
                ".$prefix."includes
            (

                id                INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                doc               INTEGER,
                internal          INTEGER,
		            external          VARCHAR( 250 ),
                type              CHAR(1),
		            position          INTEGER
            )";
    $dba->exec( $sql );

    //type can be "d" ( document ), "m" ( media ), "e" ( external url reference )
    $status.=$prefix."includes table created<br>";


    /*************************  references *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."references";
    $dba->exec($sql);

    $sql = "CREATE TABLE
                ".$prefix."references
            (
                referer           INTEGER,
                reference         INTEGER,
                reference_type    CHAR(1)
            )";
    $dba->exec( $sql );
    $status.=$prefix."references table created<br>";

    /*************************  topics *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."topics";
    $dba->exec($sql);

    $sql = "CREATE TABLE
                ".$prefix."topics
            (
                id                INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name              VARCHAR(250),
                description       TEXT,
                icon              INTEGER,
                format            VARCHAR(10)
            )";
    $dba->exec( $sql );
    $status.=$prefix."topics table created<br>";

    /*************************  stats *****************************/
    $sql = "DROP TABLE IF EXISTS ".$prefix."stats";
    $dba->exec($sql);

    $sql = "CREATE TABLE
                ".$prefix."stats
            (
                timestamp         TIMESTAMP,
                sessid            VARCHAR(200) NOT NULL,
                ip                VARCHAR(50),
                referer           VARCHAR(200),
                id                INTEGER,
                assettype         CHAR(1),
                useragent         VARCHAR(200),
                js                CHAR(1),
                java              CHAR(1),
                screen            VARCHAR(50),
                colors            VARCHAR(50)
            )";
    $dba->exec( $sql );
    $status.=$prefix."stats table created<br>";

		$sql = "DROP TABLE IF EXISTS ".$prefix."permanent_stats";
		$dba->exec($sql);
		$sql = "CREATE TABLE
								".$prefix."permanent_stats
						(
							id int(11) default NULL,
							unique_visits int(11) default NULL,
							visits int(11) default NULL,
							last_visit timestamp NOT NULL
						)";
		$dba->exec( $sql );
    $status.=$prefix."permanent_stats table created<br>";


    /*****************************  newsfeed *********************************/
    $sql = "DROP TABLE IF EXISTS ". $prefix ."newsfeed";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $prefix. "newsfeed
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(250),
                url                 VARCHAR(250),
                fetch_interval      INT,
                displaynumber       INT,
                active              CHAR(1),
                cache               TEXT,
                lastfetched         DATETIME,
                position            INT
            )";
    $dba->exec( $sql );

    $status.= $prefix .'newsfeed table created<br>';

    /**************************** INSTALL FORMS *********************************/
    require_once("forms.php");
    $status.= $prefix ."forms table created<br>";

    /*************************** INSTALL COUNTRY DATA ****************************/
    require_once("countries.php");
    $status.= $prefix ."countries table created<br>";

    /*************************** INSTALL POSTNUMBER DATA ****************************/
    require_once("postnumber.php");
    $status.= $prefix ."postnumber table created<br>";
		
		/*************************** INSTALL NEWSLETTER DATA ****************************/
    require_once("newsletters.php");
    $status.= $prefix ."newsletter table created<br>";
		
    //now register the user id and start a session for him
    session_start();
    session_register("1");
    $sid = session_id();
    $sql = "UPDATE ".$prefix."a_user SET sessid='$sid',sessionStart=NOW() WHERE id=1";
    $dba->exec($sql);
		
		$status.=$prefix."<br><br><strong>Admin user created:</strong><br>Username: admin<br>Password: Verk1459";
?>
<html>
	<head>
		<title>Install</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
	</head>
	<body class="grayBody">
        <br><br>
        <br><br>
        <center>
            <table cellpadding="0" cellspacing="0" border="0" width="400">
                <tr>
                    <td class="Header2" colspan="2">Following tables where created by the system:</td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/red.gif" border="0" width="400" height="3"></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/transp.gif" border="0" width="400" height="10"></td>
                </tr>
                <tr>

                    <td class="plainText" colspan="2">
                        <?php echo $status;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/transp.gif" border="0" width="400" height="10"></td>
                </tr>
                <tr>
                    <td colspan="2"><img src="../graphics/red.gif" border="0" width="400" height="3"></td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        <input type="button" value=" START " onClick="document.location.href='../index.php';" class="knap" style="width:200px">
                    </td>
                </tr>
            </table>
        </center>
	</body>
</html>
