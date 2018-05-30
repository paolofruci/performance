<?php
    include("data.php");
    $mydb = new db();
    $dataPost = $_POST;
    if($mydb->add_Request($dataPost)){
        echo "Richiesta aggiunta";
    }
?>