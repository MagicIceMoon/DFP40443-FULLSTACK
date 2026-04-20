<?php
include 'db.php';

$sql = "SELECT * FROM roles";
$result = mysqli_query($conn,$sql);
$output = "<ul>";

while($row = mysqli_fetch_assoc($result)) {
    $output.="<li>".$row['name']."</li>";
}
$output.="</ul>";

echo $output;
?>