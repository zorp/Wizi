<?php
/**********************************************************************/
/*                                                                    */
/*   File name: /Users/ronaldja/Sites/wizi/admin/util/templates.php   */
/*                                                                    */
/*                                                                    */
/**********************************************************************/
/*                                                                    */
/*                                                                    */
/**********************************************************************/
/*                                                                    */
/*   Date: 02/24/02 13:02:30                                          */
/*                                                                    */
/**********************************************************************/
$TEMPLATE_PATH = "../templates/";
//usage
//echo getTemplates();
//echo htmlentities( getTemplate("A");

function getTemplatesPaste( )
{
    global $TEMPLATE_PATH;
    //find the templates on the templates directory
    //and list them in a select widget
    $str = '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
    

    if( is_dir( $TEMPLATE_PATH ) )
    {
        $handle = opendir( $TEMPLATE_PATH );
        if( $handle )
        {
            while( $file = readdir( $handle ) )
            {
                if( stristr( $file, ".html" ) || stristr( $file, ".htm" )  )
                {
                    $fileArr = explode( ".", $file );
                    $name    = $fileArr[ 0 ];
                    if( $name )
                    {
                        $counter++;
                        ($counter%2==0)? $color = '#e3e3e3': $color = '#cdcdcd';

                        $body.= '<tr>';
                        $body.= '<td bgcolor="'. $color .'" class="plainText">&nbsp;';
                        $body.= '<a href="#" onClick="pasting(\''. $name .'\');return false;">';
                        $body.= $name;
                        $body.= '</a>';
                        $body.= '</td>';
                        $body.= '<td bgcolor="'. $color .'" class="plainText">';
                        $body.= '<a href="../templates/'. $name .'.html" target="blank">';
                        $body.= 'preview</a>';
                        /*
                        $body.= '<textarea name="'. $name .'" style="visibility:hidden;width:0px;height:0px;">';
                        $body.= getTemplate(  $name );
                        $body.= '</textarea>';
                        */

                        $body.= '</td>';
                        $body.= '</tr>';
                    }
                }
            }
        }
        closedir( $handle );

    }

    if( !$body ) $body = '<tr><td>No templates availables</td></tr>';
    $str.= $body . '</table>';
    return $str;
}

function getTemplates( $templateName, $widgetName )
{
    global $TEMPLATE_PATH;
    //find the templates on the templates directory
    //and list them in a select widget

    if( is_dir( $TEMPLATE_PATH ) )
    {
        $str = '<select name="'. $widgetName .'" style="width:150px">';
        $str.= '<option value="none">None</option>';

        $handle = opendir( $TEMPLATE_PATH );
        if( $handle )
        {
            while( $file = readdir( $handle ) )
            {
                if( stristr( $file, ".html" ) || stristr( $file, ".htm" )  )
                {
                    $fileArr = explode( ".", $file );
                    $name    = $fileArr[ 0 ];
                    if( $name )
                    {
                        if( $name == templateName )  $selected = ' selected ';
                        else $selected = '';
                        
                        $str.= '<option '. $selected .' value="'. $name .'">'. $name .'</option>';
                    }
                }
            }
        }
        closedir( $handle );
        $str.= '</select>';
    }

    return $str;
}

function getTemplate( $template_name )
{
    global $TEMPLATE_PATH;

    if( is_dir( $TEMPLATE_PATH ) )
    {
        $handle = opendir( $TEMPLATE_PATH );
        if( $handle )
        {
            while( $file = readdir( $handle ) )
            {
                if( stristr( $file, ".html" ) || stristr( $file, ".htm" )  )
                {
                    $fileArr = explode( ".", $file );
                    $name    = $fileArr[ 0 ];
                    if( $name  == $template_name )
                    {
                        //read the file on a string;
                        $filename = $TEMPLATE_PATH . $file ;
                        $fp  = fopen( $filename, "r");
                        $content = fread( $fp, filesize( $filename ) );
                        fclose( $fp );
                        //handle properties on body tag
                        $contentArray = split( "<.?body>",$content );

                        //parse the body out of the content string
                        if( count( $contentArray ) == 3 ) $str = $contentArray[ 1 ];
                        else $str = $content;

                        break;
                    }
                }
            }
        }
        closedir( $handle );
    }
    return $str;
}
?>
