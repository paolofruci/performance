<?php 
    include("data.php");
    $mydb = new db();
    $result = array();
    if($_POST['action-function'] == 'deleteComponent' ){
        $delete = $mydb->deleteComponent($_POST['item-id']) ;
        if($delete == "OK"){
            $result['output'] = "OK";
        }else{
            $result['error'] = $delete;
        }
    }elseif ($_POST['action-function'] == 'deleteProject' ) {
        $delete = $mydb->deleteProject($_POST['item-id']) ;
        if($delete == "OK"){
            $result['output'] = "OK";
        }else{
            $result['error'] = $delete;
        }
    }
    
    echo json_encode($result) 

?>