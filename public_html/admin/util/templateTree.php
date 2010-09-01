<?php
class templateTree extends tree
{
    function getNodes( $id = 0 )
    {
        //get a list of the nodes who have childrens
        $nodeList = $this->getNodesWithChildrens( $id );
        
        //select all the children of the current node
        $sql = "SELECT
                    id          AS 'id',
                    name        AS 'name',
                    template    AS 'template'
                FROM
                    ". $this->table ."
                WHERE
                    parent = $id
                ORDER BY
                    position";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $this->node  = $this->dba->fetchArray( $result );
						$this->node["name"] = stripslashes ( $this->node["name"] );

            //add the node field to the node
            $this->node["node"] = ( $nodeList[ $this->node["id"] ] )? TRUE : FALSE;
            
            //add the open field to the node 
            $this->node["open"] = ( $this->state[ $this->node["id"] ]  )? TRUE : FALSE;

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
