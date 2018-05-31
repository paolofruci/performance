<?php 

    include("data.php") ;
    $mydb = new db();
    $id = $_GET['id_component']; 
    $schedules = $mydb->getScheduledRequest($id);
    echo json_encode($schedules);
?>