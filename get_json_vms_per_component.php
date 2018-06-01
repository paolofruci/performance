<?php 

    include("data.php") ;
    $mydb = new db();
    $array = array(
        "data" => array()
    );
    if(isset($_GET['component_id'])){
        $array['data'] = $mydb->getVM4Component($_GET['component_id']);
        
    }
    echo json_encode($array);
?>