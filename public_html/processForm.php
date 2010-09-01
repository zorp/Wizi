<?php
require_once("admin/util/dba.php");
require_once("admin/util/forms.php");
require_once("admin/util/fields.php");

if( !$id ) $id = $_POST['id'];
if( !$id ) die("Form parameter expected");

$dba = new dba();
$forms = new forms( $dba );
$forms->form( $id );
$fields = new fields( $dba, $id );

$keys = $fields->getFields();
$n = count( $keys );
for( $i = 0; $i < $n; $i++ )
{
  switch( $keys[$i]['name'] )
  {
    case( 'date' ):
      $value = $_POST['day_'. $keys[$i]['id'] ] .'.';
      $value.= $_POST['month_'. $keys[$i]['id'] ] .'.';
      $value.= $_POST['year_'. $keys[$i]['id'] ];
      break;
    case( 'time' ):
      $value = $_POST['hour_'. $keys[$i]['id'] ] .':';
      $value.= $_POST['minute_'. $keys[$i]['id'] ] .':';
      $value.= $_POST['second_'. $keys[$i]['id'] ];
      break;
    case( 'datetime' ):
      $value = $_POST['day_'. $keys[$i]['id'] ] .'.';
      $value.= $_POST['month_'. $keys[$i]['id'] ] .'.';
      $value.= $_POST['year_'. $keys[$i]['id'] ] .' ';
      $value.= $_POST['hour_'. $keys[$i]['id'] ] .':';
      $value.= $_POST['minute_'. $keys[$i]['id'] ] .':';
      $value.= $_POST['second_'. $keys[$i]['id'] ];
      break;
    default:
      $value =  $_POST['field_'. $keys[$i]['id'] ]; 
      if( !is_array( $value ) ) $value = stripslashes( $value );
  }
  $keys[$i]['postValue'] = $value;
}

switch( $forms->action_type )
{
  case('db'):
    $forms->insertRecord( $keys );
  break;
	case('newsletter'):
    for( $i = 0; $i < count( $keys ); $i++ )
		{
			if ($keys[$i]["fieldtype"] == 6)
			{
				$filledMail = $keys[$i]["postValue"];
				$deleteid = $forms->checkDuplicateMail($forms->id, $filledMail);
			}
		}
		if (!$deleteid) $forms->insertRecord( $keys );
  break;
  case('mail'):
    writeMail( $keys, $forms->name, $forms->mail_recipients );
  break;
  case('csv'):
    //one file a month
    $fileName = 'admin/forms/formdata/form_'. $forms->id .'_'. date('m_Y') .'.csv';
    writeCSV( $fileName, $keys );
  break;
}

if( is_numeric( $forms->confirmation_page ) )
{
 Header('Location:index.php?page='. $forms->confirmation_page );
 die();
}

if( trim( $forms->confirmation_page ) ) Header('Location:'. $forms->confirmation_page );

function writeMail( $keys, $formName, $recipients )
{
  if( !trim( $recipients ) ) return;
  $recipients = explode(',',$recipients );
  for( $i = 0; $i < count( $recipients ); $i++ )
  {
    if( $to ) $to.= ',';
    $to.= '<'. $recipients[$i] .'>';
  }

  $subject = 'Form data ['. $formName .']['. date('F j, Y, g:i a') .']';

  $message = $subject ."\n";
  $message.= '.........................................................'."\n\n";
  $n = count( $keys );
  for( $i = 0; $i < $n; $i++ )
  {
    $message.= $keys[$i]['label'];
    $message.= ' ['. $keys[$i]['title'] .']:'."\n";
    $message.= "\t";
    $message.= ( is_array( $keys[$i]['postValue'] ) )? implode(',', $keys[$i]['postValue'] ):$keys[$i]['postValue'];
    $message.= "\n\n";
  }
  $message.= '.........................................................'."\n\n";
  $headers = "From: WIZI WEB\r\n";
  mail( $to, $subject, $message, $headers );
}

function writeCSV( $fileName, $keys )
{
  $header = !file_exists( $fileName );

  $n = count( $keys );
  for( $i = 0; $i < $n; $i++ )
  {
    if( $header )
    {
      if( $head ) $head.= ',';
      $head.= '\''. addslashes( $keys[$i]['label'] ) ;
      $head.= ' ['. addslashes( $keys[$i]['title'] ) .']\'';
    }
    if( $record ) $record.= ',';
    $record.= '\'';
    $record.= ( is_array( $keys[$i]['postValue'] ) )? addslashes( implode(",", $keys[$i]['postValue'] ) ):addslashes( $keys[$i]['postValue'] );
    $record.= '\'';
  }
  if( $header ) $content = $head ."\r\n";
  $content.= $record ."\r\n";
  
  $index = ( $header )?'w':'a';
  $fp = fopen( $fileName, $index );
  fwrite( $fp, $content );
  fclose( $fp );
}
?>
<html>
  <head>
    <title>The form has been processed</title>
  </head>
  <body>
    <h1>The form has been processed.</h1>
		<p>Thanks for your time</p>
  </body>
</html>