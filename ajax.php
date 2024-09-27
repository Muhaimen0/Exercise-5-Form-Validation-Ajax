<?php  
$filename = 'attendance_history.txt';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
    $id = $_POST['id'] ?? '';  
    $name = $_POST['name'] ?? '';  
    $password = $_POST['password'] ?? '';  
    $gender = $_POST['gender'] ?? '';  
    $time = $_POST['time'] ?? '';  
    $timePeriod = $_POST['timePeriod'] ?? '';  
    
    $fullTime = "$time $timePeriod";  
    $newRecord = "$id,$name,$gender,$fullTime\n";  

    file_put_contents($filename, $newRecord, FILE_APPEND);  
 
    header("Content-Type: application/xml");
    echo "<?xml version='1.0' encoding='UTF-8'?>";
    echo "<attendance>";
    echo "<id>" . htmlspecialchars($id) . "</id>";
    echo "<name>" . htmlspecialchars($name) . "</name>";
    echo "<gender>" . htmlspecialchars($gender) . "</gender>";
    echo "<time>" . htmlspecialchars($fullTime) . "</time>";
    echo "</attendance>";
}  
?>
