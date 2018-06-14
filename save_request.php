<?php
    include("data.php");
    $mydb = new db();
    $dataPost = $_POST;
    // echo "<pre>"; print_r($_POST); echo "</pre>";
    $error = 'Error on insert request for component: ';


    function getVMName($vmid){
        global $mydb;
        return $mydb->getVM($vmid)->vmname;
    }

    $aDateInterval          = explode(' - ', $_POST['IntervalTime'] );
    $data['startTime']      = $aDateInterval[0];
    $data['endTime']        = $aDateInterval[1];
    $data['projectName']    = $_POST['projectName']; 
    $data['projectID']      = $_POST['projectID']; 
    

    switch ($_POST['action']) {

        case 'newRequestperComponent':
            $data['componentID']    = $_POST['componentID'];
            $data['componentName']  = $_POST['componentName'];
            $data['requestName']    = $_POST['requestName'];
            $aVmsID =  (isset($_POST['select_list'])) ?  $_POST['select_list'] : array();
            $data['vmsID']      = implode( ',' , $aVmsID );
            $data['VmsNames']   = implode( ',' , array_map("getVMName",$aVmsID) );
            
            if($mydb->add_Request($data)){
                echo "Richiesta aggiunta";
            }
            // echo "<pre>"; print_r($data); echo "</pre>";
            break;

        case 'newRequestperProject':
            if( isset( $_POST['select_list'] ) && is_array($_POST['select_list']) ){
                $total = count($_POST['select_list']);
                $count = 0;
                foreach ( $_POST['select_list'] as $key => $id_component ) {
                    $data['componentID']    = $id_component;
                    $componentData = $mydb->getComponent($id_component) ;
                    $data['componentName']  = $componentData->componente_nome;
                    $data['requestName']    = $_POST['projectName'] . ' - ' . $componentData->componente_nome;
                    $data['vmsID']          = $componentData->vms_id;
                    $aVmsID                 = explode( ',' , $data['vmsID']);
                    $data['VmsNames']       = implode( ',' , array_map("getVMName",$aVmsID) );
                    
                    if($mydb->add_Request($data)){
                        $count++; continue;
                    }else{
                        $error .= " $id_component";
                    }
                    // echo "<pre>"; print_r($data); echo "</pre>";
                }
                echo ($count == $total)?  "Sono state aggiunte tutte le richieste per i Componenti selezionati." :  $error ;
            }
            
            break;
        default:
            echo "Problemi nell'aggiunta della richiesta - Non è stata selezionata una action!";
            break;
    }

?>