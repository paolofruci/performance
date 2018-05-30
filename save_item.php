<?php 
    include("data.php");
    $mydb = new db();
    $dataPost = $_POST;
    //var_dump(implode(',',$_POST['vm_id']));





    if(isset($dataPost['project_id'])) {
        if(!isset($dataPost['component_id']) || $dataPost['component_id'] == null){
            if( $mydb->add_component( $dataPost , $dataPost['project_id'] ) ){
                ?>
                    <div class="alert alert-success" role="alert">
                        Componente Salvato correttamente! 
                    </div>
                <?php
            }else{
                ?>
                    <div class="alert alert-danger" role="alert">
                        Errore nel salvataggio del componente!
                    </div>
                <?php
            }
        }else{
            if( $mydb->edit_component( $dataPost , $dataPost['component_id'] ) ){
                ?>
                    <div class="alert alert-success" role="alert">
                        Componente Salvato correttamente!
                    </div>
                <?php
            }else{
                ?>
                    <div class="alert alert-danger" role="alert">
                        Errore nel edit del componente!
                    </div>
                <?php
            }
        }
    }else{
        $last_id =  $mydb->add_Project( $dataPost ) ;
        if($last_id){
            if( $mydb->add_component( $dataPost , $last_id) ){
                ?>
                    <div class="alert alert-success" role="alert">
                        Componente Salvato correttamente!
                    </div>
                <?php
            }else{
    
            }
        }
    }
?>
<script>
    $(document).ready(function(){
        // RELOAD SIDEBAR 
        $(".sidebar").load("sidebar.php");
    })
</script>