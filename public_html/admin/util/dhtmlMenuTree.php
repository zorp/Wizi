<?php
/**
 ** Especialize tree for graphing site maps
 ** @author Ronald
 **/
 class dhtmlMenuTree extends tree
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
        
        //select all the children of the current node
        //who are publish an visible on navigation
        $sql = "SELECT
                    id        AS 'id',
                    name      AS 'name',
                    title     AS 'title'
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
						$this->node["title"] = stripslashes ( $this->node["title"] );
            $this->node["parent"] = $id;

            //add the node field to the node
            $this->node["node"] = ( $nodeList[ $this->node["id"] ] )? TRUE : FALSE;
            
            //add the open field to the node 
            $this->node["open"] = TRUE; //( $this->state[ $this->node["id"] ]  )? TRUE : FALSE;

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
 }
 ?>
