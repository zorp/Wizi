<?php
if (!$email) $email = $_POST["email"];
if (!$formId) $formId = $_GET["formId"];
if (!$formId) $formId = $_POST["formId"];

$forms = new forms( $dba );
$forms->form( $formId );
if ($email && $formId) $deleteStatus = $forms->deleteMailFromMaillist($email, $formId);

if ($deleteStatus) $message = "The e-mail: ".$email." has been removed from the mailing list: ".$forms->name.".";
else $message = "The e-mail could not be found in the database.";
?>
<h1>Unsubscribe from the mailing list: <?php echo $forms->name?></h1>
<?php echo ($message && $email)?'<p style="color:green;">'.$message.'</p>':''; ?>
<form name="unsubscribe" action="index.php?action=unsubscribe&formId=<?php echo $formId?>&lang=<?php echo $lang?>" method="post" onsubmit="return validate_form('myform')">
	<input type="hidden" name="formId" value="<?php echo $formId?>">
	<script type="text/javascript">
		var fields2validate = new Array();
		fields2validate[ fields2validate.length ] =new Array('email','mail','E-mail');
	</script>
	<p>Type the e-mail adress you want to remove:</p>
	<input type="text" name="email" value="">
	<input type="submit" name="submit" value="Unsubscribe">
</form>