<?php 
    include("data.php");
    $mydb = new db();
    $result = array();
    $data = array();
    if($_POST['project-name']){
        $data['projectName'] = $_POST['project-name'] ;
        if( isset($_POST['project-id']) && $_POST['project-id']!=null){
            $data['projectID'] = $_POST['project-id'] ;
            $result['output'] = $mydb->edit_Project($data);
            
        }else{
            $result['output'] = $mydb->add_Project($data);
        }
    }else{
        $result['error'] = "non è stato specificato un nome progetto";
    } 
    echo json_encode($result);
?>