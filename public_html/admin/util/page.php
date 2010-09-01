<?php
class page extends menu
{
    var $dba;
    var $p;
    var $properties;
    var $templates;
    var	$layout;
    var $id;
    var $publish;
    var $role; 

    function page( $dba, $id = 1 )
    {
        $this->dba   = $dba;
        $this->p     = $this->dba->getPrefix();
        $this->id    = $id;
        $this->menu( $dba, $this->id );
    }
    function getRole()
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
                    ".$this->p."end_user_realms as constrain,
                    ".$this->p."end_user_role_constrains as roleconstrain
                WHERE
                    role.constrain = constrain.id
                AND
                    roleconstrain.role = role.id
                AND
                    roleconstrain.doc = ". $this->id ."
                ORDER BY
                    constrain.id DESC 
                LIMIT 0,1";
        $this->role = $this->dba->singleArray( $sql );

        return $this->role;
    }

    function isLogin()
    {
        $sql = "SELECT 
                    id
                FROM
                  ".$this->p."end_user_role as role
                WHERE
                  role.showLogin = 'y'";
        if($this->dba->singleQuery( $sql ) ) return "login_registration/loginform.php";
    }
    function authenticate( $password, $roleId )
    {
        if( !trim( $password ) ) return false;
        if( !is_numeric( $roleId ) ) return false;

        $sql = "SELECT
                    id
                FROM
                    ".$this->p."end_user_role
                WHERE
                    password ='". trim( $password ) ."'
                AND
                    id = $roleId";
        return $this->dba->singleQuery( $sql );
    }
    function getNews()
    {
			 $sql = "SELECT
                  doc.id,
                  doc.name,
                  doc.title,
                  doc.heading,
                  doc.description,
                  LEFT( doc.content, 200 ) AS summary,
                  DATE_FORMAT(doc.fromnews,'%d/%m/%Y')   AS edited,
									topic.id     AS topicId,
                  topic.name   AS topicName,
                  topic.icon   AS topicIcon,
                  topic.format AS topicFormat
                FROM
                  ".$this->p."tree as doc,
									".$this->p."topics as topic
                WHERE
										doc.topic = topic.id
								AND
               			doc.news = 'y'
                AND 
                    ( doc.timepublish < NOW() OR doc.timepublish IS NULL )
                AND
                    ( doc.timeunpublish > NOW() OR doc.timeunpublish IS NULL )
								AND
                    doc.fromnews < NOW() 
                AND
                    doc.tonews > NOW()
                ORDER BY
                  	doc.fromnews DESC";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        $formats = array( "jpg","gif","png" );

        for( $i = 0; $i < $n; $i++ )
        {
          $news[$i] = $this->dba->fetchArray($result);
					$news[$i]["name"] = stripslashes ( $news[$i]["name"] );
					$news[$i]["title"] = stripslashes ( $news[$i]["title"] );
					$news[$i]["heading"] = stripslashes ( $news[$i]["heading"] );
					$news[$i]["description"] = stripslashes ( $news[$i]["description"] );
					$news[$i]["summary"] = strip_tags(stripslashes ( $news[$i]["summary"] ));
					$news[$i]["topicName"] = stripslashes ( $news[$i]["topicName"] );
					$news[$i]["date"] = $news[$i]["edited"];
          if( in_array( $news[$i]["topicFormat"], $formats ) )
          {
            $news[$i]["topicIcon"] = $news[$i]["topicIcon"].'.'.$news[$i]["topicFormat"];
          }
        }
        return $news;
    }
		function getNewsFromTopic($topicId, $index = false, $amount = false)
    {
				if (!is_numeric($topicId)) return;
				
				$sql = "SELECT
                  doc.id,
                  doc.name,
                  doc.title,
                  doc.heading,
                  doc.description,
                  LEFT( doc.content, 200 ) AS summary,
                  DATE_FORMAT(doc.fromnews,'%d/%m/%Y')   AS edited,
                  topic.id     AS topicId,
                  topic.name   AS topicName,
                  topic.icon   AS topicIcon,
                  topic.format AS topicFormat
                FROM
                  ".$this->p."tree as doc,
                  ".$this->p."topics as topic
                WHERE
									doc.topic = topic.id
								AND
                  topic.id = $topicId
                AND 
                    ( doc.timepublish < NOW() OR doc.timepublish IS NULL )
                AND
                    ( doc.timeunpublish > NOW() OR doc.timeunpublish IS NULL )
                ORDER BY
                  doc.fromnews DESC";
				if ($index && $amount ) $sql.= " LIMIT $index,$amount";
        
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        $formats = array( "jpg","gif","png" );

        for( $i = 0; $i < $n; $i++ )
        {
          $news[$i] = $this->dba->fetchArray($result);
					$news[$i]["name"] = stripslashes ( $news[$i]["name"] );
					$news[$i]["title"] = stripslashes ( $news[$i]["title"] );
					$news[$i]["heading"] = stripslashes ( $news[$i]["heading"] );
					$news[$i]["description"] = stripslashes ( $news[$i]["description"] );
					$news[$i]["summary"] = stripslashes ( $news[$i]["summary"] );
					$news[$i]["topicName"] = stripslashes ( $news[$i]["topicName"] );
					$news[$i]["date"] = $news[$i]["edited"];
          if( in_array( $news[$i]["topicFormat"], $formats ) )
          {
            $news[$i]["topicIcon"] = $news[$i]["topicIcon"].'.'.$news[$i]["topicFormat"];
          }
        }
        return $news;
    }
    function getMenu( $level = 'top' )
    {
        if( $level == "top"    ) return $this->buildMenu( 1 );
        if( $level == "menu"   )
        {
            if( $this->properties["parent"] != 1 )
            {
              $temp = $this->buildMenu( $this->properties["parent"]  );
              return $temp;
            }
            else return $this->buildMenu( $this->id );
        }
        if( $level == "child" ) return $this->buildMenu( $this->id );
    }
    function getInclude( $name )
    {
        $fp = fopen( $name,"r");
        $include = fread( $fp, filesize( $filename ) );
        fclose( $fp );

        return $include;
    }
    function getProperties()
    {
        $sql = "SELECT
               name,
               title,
               meta,
               description,
               template,
               parent,
	             layout,
							 showcomment
            FROM
                ". $this->p. "tree
            WHERE
                id = ". $this->id ."
            AND 
                ( timepublish < NOW() OR timepublish IS NULL )
            AND
                ( timeunpublish > NOW() OR timeunpublish IS NULL )";

        $result = $this->dba->exec( $sql );
        if( $this->dba->getN( $result ) ){
                $this->properties = $this->dba->fetchArray( $result );
                $this->publish = true;
        }else {
          $this->publish = false;
					$sql = "SELECT
               name,
               title,
               meta,
               description,
               template,
               parent,
	             layout,
							 showcomment,
							 rightcol
            FROM
                ". $this->p. "tree
            WHERE
                id = ". $this->id;

          //get the page properties anyway
          $result = $this->dba->exec( $sql );
					if( $this->dba->getN( $result ) ){
          	$this->properties = $this->dba->fetchArray( $result );
					}
        }
    }
    function getIncludes()
    {
        //get all the includes for the document
        $sql = "SELECT 
                inc.doc as id,
                inc.internal as incid,
                inc.type  as type,
                doc.content as content,
                doc.template as template
            FROM 
                ".$this->p."includes AS inc 
            LEFT JOIN 
                ".$this->p."tree AS doc 
            ON 
                inc.internal = doc.id 
            WHERE 
                inc.doc=". $this->id ."
            ORDER BY
                inc.position";

        $result = $this->dba->exec( $sql );
        $n	= $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->fetchArray( $result );
            if( trim( $this->properties["content"] ) ) $this->properties["content"].= "<br>";
            if( $record["type"] == "d" ) $this->properties["content"].= $record["content"];
            else $this->properties["content"].= $this->loadMedia( $record["incid"] );
        }
    }
    function loadMedia( $id )
    {
        if( !is_numeric( $id ) ) return;
        $sql = "SELECT
                    id,
                    name,
                    description,
                    meta as alt,
                    format,
                    size,
                    height,
                    width
                FROM
                    ".$this->p."mediatree
                WHERE
                    id= $id";
        $media    = $this->dba->singleArray( $sql );
        $fileName = "media/". $media["id"] .".". $media["format"]; 

        if( !file_exists( $fileName ) ) return;
        $f = $media["format"];

        if( $f == 'gif' || $f == 'jpg' || $f == 'png' )
        {
            $str = '<img src="'. $fileName .'" ';
            $str.= ( $media["width"] )?' width="'. $media["width"] .'" ':'';
            $str.= ( $media["height"] )?' height="'. $media["height"] .'" ':'';
            $str.= ( $media["alt"] )?' alt="'. $media["alt"] .'" ':'';
            $str.= '/>';
            return $str;
        }
        elseif( $f == 'swf' )
        {
            $str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
            $str.= 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" ';
            $str.= ( $media["width"] )?' width="'. $media["width"] .'" ':'';
            $str.= ( $media["height"] )?' height="'. $media["height"] .'" ':'';
            $str.= '>';
            $str.= '<param name="movie" value="'. $fileName .'">';
            $str.= '<param name="quality" value="high">';
            $str.= '<embed src="'. $fileName .'" quality="high" ';
            $str.= 'pluginspage="http://www.macromedia.com/go/getflashplayer" ';
            $str.= 'type="application/x-shockwave-flash" ';
            $str.= ( $media["width"] )?' width="'. $media["width"] .'" ':'';
            $str.= ( $media["height"] )?' height="'. $media["height"] .'" ':'';
            $str.= '></embed></object>';
            return $str;
        }
        else
        {
            $str= '<a href="'. $fileName .'" traget="_blank">';
            $str.= 'Open <b>'. $media["name"] .'</b> in new window</a>';
            return $str;
        }
    }
		
		function getTopPages(){
			$sql = "SELECT id FROM ".$this->p."tree WHERE parent = 1";
			$result = $this->dba->exec( $sql );
			$n	= $this->dba->getN( $result );

			for( $i = 0; $i < $n; $i++ ){
				$record[$i] = $this->dba->fetchArray( $result );
				$topPages[$i] = $record[$i]["id"];
			}
			return $topPages;
		}
		
		function getSubPages($parent){
			$sql = "SELECT id FROM ".$this->p."tree WHERE parent = ".$parent;
			$result = $this->dba->exec( $sql );
			$n	= $this->dba->getN( $result );

			for( $i = 0; $i < $n; $i++ ){
				$record[$i] = $this->dba->fetchArray( $result );
				$subPages[$i] = $record[$i]["id"];
			}
			return $subPages;
		}

    function isInRealm( )
    {

    }
}
?>
