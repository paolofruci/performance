<?php 
    include("data.php");
    $mydb = new db();
    $dataPost = $_POST;
    session_start();




    if(isset($dataPost['project_id'])) {
        if(!isset($dataPost['component_id']) || $dataPost['component_id'] == null){
            echo json_encode($mydb->add_component( $dataPost , $dataPost['project_id'] )) ;
            
        }else{
            echo json_encode($mydb->edit_component( $dataPost , $dataPost['component_id'] )) ;            
        }
    }else{
        echo json_encode(array());
    }
?>