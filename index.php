<?php
/**
 * 	sample file index.php
 */
include_once "literal.class.php";
//
$literal=new literal();
$chiffre='';
$lettre='';
if (isset($_POST['convertir']) && ($_POST['convertir']=='convertir')){
	if(isset($_POST['chiffre']) && !empty($_POST['chiffre'])){
		//sample
		$chiffre=$_POST['chiffre'];
		$literal=new literal();

		$lettre= $literal->literalize($chiffre);
	}
}
//

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Ecrire en lettre un nombre </title>
</head>
<body>
	<h3>
		nombre &agrave; convertir :
		<?php echo $chiffre;?>
	</h3>
	<form method="post" action="">
		<input type="text" name="chiffre" value="" /> <input type="submit"
			name="convertir" value="convertir" />
	</form>

	<font id="ur" size="8" face="Trebuchet MS, Verdana, Arial, sans-serif"
		color="#DAD3B7"> <?php echo $lettre;?>
	</font>
</body>
</html>
