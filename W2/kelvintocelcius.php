<!DOCTYPE html>
<html>
<head>
</head>
<body>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<label>Kelvin (K)</label>
<input name="kelvinVal">
<input type="submit" value="Calculate">
</form>

</body>

<?php
if($_SERVER['REQUEST_METHOD'] == "POST") {
$kelvin = $_POST['kelvinVal'];

$celcius = $kelvin - 273.15;

if($kelvin = 50) {
    echo "normal";
} elseif($kelvin < 50) {
    echo "cold";
} else {
    echo "hot";
}
}
?>
<?php
echo $celcius;
?>
</html>