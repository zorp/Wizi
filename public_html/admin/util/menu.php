<?php
class menu
{
    var $dba;
    var $p;
    var $docId;
    var $path;
    var $topMenu;
    var $menu;

    function menu( $dba, $docId = 1 )
    {
        $this->dba   = $dba;
        $this->p     = $this->dba->getPrefix();
        $this->docId = $docId;
    }
    function buildMenu( $id )
    {
        if( !$id ) return;
				$sql = "SELECT
                    id,
                    name
                FROM
                    ".$this->p."tree
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
        
        unset( $this->menu );

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $this->menu[ $i ]= $this->dba->fetchArray( $result );
						$this->menu[ $i ]["name"] = stripslashes ( $this->menu[ $i ]["name"] );
        }

        return $this->menu;
    }
    function getPath( ) 
    {
        if( !count( $this->path ) )
        {
          $this->path = array();
          $this->buildPath( $this->docId );
        }
        //reverse the array preserving the values of the keys
        $this->path = array_reverse( $this->path,true );
        return $this->path;
    }
    function buildPath( $id )
    {
        while( $id )
        {
            $id = $this->getParent( $id );
            if( !$id ) return;
            $sql = "SELECT
                        id,
                        name
                    FROM
                        ".$this->p."tree
                    WHERE
                        id=$id";
            $result = $this->dba->exec( $sql );
            $n      = $this->dba->getN( $result );
            for( $i = 0; $i < $n; $i++ )
            {
                $rec = $this->dba->fetchArray( $result );
                $this->path[ $rec["id"] ] = $rec["name"];
            }
        }
    }
    function getParent( $id )
    {
        if( !$id || $id == 1 ) return 0;

        $sql =  "SELECT
                    parent
                 FROM 
                  ". $this->p ."tree 
                WHERE 
                  id=$id";
        return $this->dba->singleQuery( $sql );
    }
}
//usage
/*
require_once( "dba.php" );
$menu = new menu( new dba(), $id );
print_r( $menu->getTopMenu() );
echo "\n<br>";
print_r( $menu->getMenu() );
echo "</xmp>\n";

for( $i = 0; $i < count( $menu->topMenu ); $i++ )
{
    echo $menu->topMenu[$i]["id"];
    echo $menu->topMenu[$i]["name"];
    echo "<br>";
}
echo "<br>";
for( $i = 0; $i < count( $menu->menu ); $i++ )
{
    echo $menu->menu[$i]["id"];
    echo $menu->menu[$i]["name"];
    echo "<br>";
}


echo $menu->getPath();

*/
?>
