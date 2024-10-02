<?php  
$a[] = "Gajeel Redfox"; 
$a[] = "Gray Fullbuster"; 
$a[] = "Lucy Heartfilia"; 
$a[] = "Erza Scarlet";
$a[] = "Natsu Dragneel"; 
$a[] = "Cana Alberona"; 
$a[] = "Anya Forger"; 

$q = $_REQUEST["q"] ?? ""; 

$hint = "";

if ($q !== "") {
  $q = strtolower($q);
  $len = strlen($q);
  foreach ($a as $name) {
    if (stristr($q, substr($name, 0, $len))) {
      if ($hint === "") {
        $hint = $name;
      } else {
        $hint .= ", $name";
      }
    }
  }
}


echo $hint === "" ? "no suggestion" : $hint;
?>
