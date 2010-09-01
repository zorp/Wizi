<?php
/**
 ** Especialize tree for graphing site maps
 ** @author Ronald
 **/
 class siteMapTree extends tree
 {
    /**
     ** Add all the childrens of the current node to the nodesArray
     ** If a children node is open, call this method again
     ** with the child id as parameter
     ** @param $id integer the current tree node id
     ** @return void
     **/
    function getNodes( $id = 0 )
    {
        //get a list of the nodes who have childrens
        $nodeList = $this->getNodesWithChildrens( $id );
        
        // **********************************************************
        // Select all the children of the current node
        // order by position
        // **********************************************************
				$sql = "SELECT
                    id        AS 'id',
                    name      AS 'name',
                    title     AS 'title',
										DATE_FORMAT(edited,'%Y-%m-%d')   AS 'edited'
                FROM
                    ". $this->table ."
                WHERE
                    parent = $id
                AND
                    ( nav = 1 OR nav IS NULL ) 
                AND
                    ( timepublish < NOW() OR timepublish IS NULL )
                AND
                    ( timeunpublish > NOW() OR timeunpublish IS NULL )
                ORDER BY
                    position";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $this->node  = $this->dba->fetchArray( $result );
            $this->node["name"] = stripslashes ( $this->node["name"] );
            $this->node["parent"] = $id;
            //add the node field to the node
            $this->node["node"] = ( $nodeList[ $this->node["id"] ] )? TRUE : FALSE;
            
            //add the open field to the node 
            if( $this->fullOpen )
            {
              $this->node["open"] = TRUE;
            }
            else
            {
              $this->node["open"] = ( $this->state[ $this->node["id"] ]  )? TRUE : FALSE;
            }
						
						// Force toplevel to be open
						if ($this->node["id"] == 1) $this->node["open"] = TRUE;

            //add the indentation field to the node
            $this->node["level"] = $this->level;

            //set the moving flag
            if( $this->node["id"] == $this->movingNode ) 
            {
               $this->node["moving"] = TRUE;
               $this->isMoving =  TRUE; 
               $tempNode = $this->node["id"];
            }
            else
            {
               $this->node["moving"] = ( $this->isMoving )? TRUE : FALSE;
            }

            //add the node to the nodeArray
            $this->nodeArray[ count( $this->nodeArray ) ] = $this->node;

            if( $this->node["open"] )
            {
                 $this->level++;
                 $this->getNodes( $this->node["id"] );
                 $this->level--;
            }

            if( $tempNode == $this->movingNode ) $this->isMoving =  FALSE; 
        }
    }
		
		function getNodesWithChildrens( $id )
    {
      $nodeList = array();

				$sql = "SELECT 
                    t1.id
                FROM 
                    ". $this->table ." AS t1,
                    ". $this->table ." AS t2
                WHERE 
                    t1.parent = $id 
                AND
                    t2.parent = t1.id
								AND
                    ( t2.nav = 1 OR t2.nav IS NULL ) 
                AND
                    ( t2.timepublish < NOW() OR t2.timepublish IS NULL )
                AND
                    ( t2.timeunpublish > NOW() OR t2.timeunpublish IS NULL )
                GROUP BY t1.id";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
           $record = $this->dba->getRecord( $result );
           $nodeList[ $record[0] ] = TRUE;
        }
      
      return $nodeList;
    }
 }
 ?>