<?php
    if (!$p) $p = $prefix;
		/*****************************  forms *********************************/
    $sql = "DROP TABLE IF EXISTS ". $p ."forms";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $p. "forms
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name                VARCHAR(50),
                action_type         VARCHAR(50),
                mail_recipients     TEXT,
                confirmation_page   VARCHAR(200),
                extern_url          VARCHAR(200),
                renderedform        TEXT,
                submit_label        VARCHAR(200),
                cancel_label        VARCHAR(200)
            )";
    $dba->exec( $sql );


    /*****************************  fields *********************************/
    $sql = "DROP TABLE IF EXISTS ". $p ."fields";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $p. "fields
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                label               TEXT,
                fieldtype           INTEGER,
                default_value       TEXT,
                layout              VARCHAR(100),
                required            CHAR(1),
                fvalues             TEXT,
                maxchar             INT,
                width               INT,
                height              INT
            )";
    $dba->exec( $sql );

    /*****************************  fields2forms ****************************/
    $sql = "DROP TABLE IF EXISTS ". $p ."fields2forms";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $p. "fields2forms
            (
              field               INTEGER,
              form                INTEGER,
              position            INTEGER
            )";
    $dba->exec( $sql );

    /*****************************  fieldtypes ****************************/
    $sql = "DROP TABLE IF EXISTS ". $p ."fieldtypes";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $p. "fieldtypes
            (
                id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                title               VARCHAR(50),
                name                VARCHAR(50)
            )";
    $dba->exec( $sql );

    //now dump data into the field types
    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Text field', 'text' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Text area', 'textarea' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Label', 'label' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Hidden field', 'hidden' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Number field', 'number' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Mail field', 'mail' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'DK - Postal codes', 'postcode' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Country', 'country' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Date', 'date' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Time', 'time' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Date time', 'datetime' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'List', 'list' )";
    $dba->exec( $sql );


    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Drop down list', 'combobox' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Radio buttons', 'radio' )";
    $dba->exec( $sql );

    $sql = "INSERT INTO ".$p."fieldtypes ( title,name )
            VALUES( 'Checkbox', 'checkbox' )";
    $dba->exec( $sql );

    
    /*****************************  listvalues ****************************/
    $sql = "DROP TABLE IF EXISTS ". $p ."listvalues";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $p. "listvalues
            (
                fieldId             INTEGER,
                formId              INTEGER,
                listvalue           VARCHAR(200),
                selected            CHAR(1)
            )";
    $dba->exec( $sql );

    /*****************************  formdata ****************************/
    $sql = "DROP TABLE IF EXISTS ". $p ."formdata";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $p. "formdata
            (
                requestId           INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                formId              INTEGER,
                submited            DATETIME
            )";
    $dba->exec( $sql );

    $sql = "DROP TABLE IF EXISTS ". $p ."fielddata";
    $dba->exec( $sql );

    $sql = "CREATE TABLE
              ". $p. "fielddata
            (
                requestId           INTEGER,
                fieldName           TEXT,
								fieldType           TEXT,
                fieldValue          TEXT
            )";
    $dba->exec( $sql );

?>
