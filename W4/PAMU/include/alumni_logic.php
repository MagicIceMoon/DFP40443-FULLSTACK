<?php 

$pages = [
    "Home" => "index.php",
    "Directory" => "directory.php",
    "Giving" => "donations.php",
    "Events" => "events.php"
];

function generatedMenu($item){
    $html = "";
    foreach ($item as $page => $url) {
        $html .= "<li><a href='$url'></a></li>";
    };

    return $html;
};
?>