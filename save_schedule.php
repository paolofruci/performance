<?php
### script richiamato dalla pagina show_component.php dalla sezione dello scheduler
    
    include("data.php");
    $mydb = new db();    
    
    if( isset($_POST['deleteSchedule']) && isset($_POST['id_schedule']) && $_POST['id_schedule']!=null ){
        echo  $mydb->deleteScheduleRequest( $_POST['id_schedule'] );
    }else{
        $data = $_POST['schedule'];
        $data['checkemail'] = (isset($data['checkemail']) ) ? $data['checkemail'] : '' ;
        $data['checkemailtext'] = (isset($data['checkemailtext']) ) ? $data['checkemailtext'] : '' ;
        echo  $mydb->insertScheduleRequest( $data );
    }
    
    

?>