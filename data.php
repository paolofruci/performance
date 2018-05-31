<?php
  
class db
{
    // property declaration
    // avvio una connessione con il database MySQL
    private $dbServer = "localhost";
    private $dbUser = "vmware";
    private $dbPassword = "Password1";
    private $dbName = "vmware";

    function __construct() {
        # CONNESSIONE AL DB
        $this->db  = new mysqli($this->dbServer, $this->dbUser, $this->dbPassword, $this->dbName);
        if ($this->db->connect_errno) { echo "Connection Failed" ; exit(); }
    }

    // method declaration
    public function getProgetti() {
        $query = "SELECT * FROM progetti";
        $result = $this->db->query($query);
        $i = 0;
        while($row = $result->fetch_assoc()) {
            $aResults[$i] = $row;
            $aResults[$i]['componenti'] = array();
            $aResults[$i]['status'] = 'OK';
            ### Recupero componenti per progetto
            $query2 = "SELECT * FROM componenti WHERE progetto_id=". $row["progetto_id"];	
            $result2 = $this->db->query($query2);
            $j=0;
            while($row2 = $result2->fetch_assoc()) {
                $aResults[$i]['componenti'][$j] = $row2 ;
                $aResults[$i]['componenti'][$j]['vms'] = array() ;
                $status =  $this->getComponentStatus($row2['componente_id']);
                $aResults[$i]['componenti'][$j]['status'] = $status;
                
                if( $status == 'warning' && $aResults[$i]['status'] != 'critical' ){
                    $aResults[$i]['status'] = $status;
                }else if ($status == 'critical'){
                    $aResults[$i]['status'] = $status;
                }else if ($status == 'OK' && $aResults[$i]['status'] != 'critical' && $aResults[$i]['status'] != 'warning'){
                    $aResults[$i]['status'] = $status;
                }
                
                ### Recupero VM per componente
                $aVMs = explode(",", $row2['vms_id']);
                
                $queryVMs = "";
                $j++;
            }
            $i++;
        }
        return $aResults;
    }

    public function getProjectComponents($project_id){
        $query = "SELECT * FROM componenti WHERE progetto_id=". $project_id;	
        $result = $this->db->query($query);
        $aResults = array();
        $i=0;
        while($row = $result->fetch_assoc()) {
            $row['status'] =  $this->getComponentStatus($row['componente_id']);
            $aResults[$i] = $row ;
            $i++;
        }
        return $aResults;
    }

    public function getProgettoName($id){
        $query = "SELECT progetto_nome FROM progetti where progetto_id = $id";
        $result = $this->db->query($query);
        $nomeProgetto = $result->fetch_object()->progetto_nome ;
        return $nomeProgetto;
    }
    public function getComponent($id){
        $q = "SELECT * FROM vmware.componenti a left join vmware.progetti b on a.progetto_id=b.progetto_id where a.componente_id=$id";
        $result = $this->db->query($q);
        $component = $result->fetch_object();
        @$component->vms = array();
        $vms_id = $component->vms_id;
        if($vms_id):
            $q2 = "select vms.* , 
            vms_stats.powerstate, 
            vms_stats.cpu_max_perc,
            vms_stats.badge,
            vms_stats.ncpu,
            vms_stats.memorymb, 
            vms_stats.mem_max_perc, 
            vms_stats.disk_max_io, 
            vms_stats.net_max_io
            from vms left join vms_stats on vms.vm_id = vms_stats.vm_id 
            where vms.vm_id in ($vms_id) 
            ORDER by vms.vmname asc";
            $result_vms = $this->db->query($q2);
            if ($result_vms->num_rows > 0) {
                while($row = $result_vms->fetch_object()) {
                    // @$row->cpu_status_class = $this->getStateColor('cpu', $row->cpu_max_perc );
                    @$component->vms[] = $row;
                }
            }
        endif;
        return $component;
    }
    
    public function getStatusClassColor($name,$value){
            
        $query_soglie = "SELECT ".$name."_warning, ".$name."_critical from soglie LIMIT 1";
        $soglie = $this->db->query($query_soglie)->fetch_object() ;
            switch (true) {
                case $value >= $soglie->{$name."_critical"} :
                    $status = "bg-danger text-white" ;
                    break;
                case $value >= $soglie->{$name."_warning"} && $value < $soglie->{$name."_critical"} :
                    $status = "bg-warning";
                    break;
                default:
                    $status = "" ;
                    break;
            }
        
        
        return $status;
    }


    public function getVM($id){
        $q = "SELECT * FROM vms WHERE vm_id = $id LIMIT 1";
        return $this->db->query($q)->fetch_object() ;
    }

    public function getAllVMs(){
        $q = "SELECT * FROM vms";
        $result = $this->db->query($q);
        while($row = $result->fetch_object()) {
            $vms[] = $row;
        }
        return $vms;
    }
    public function add_component($data,$project_id){
        $componente_nome =  $data['componentName'];
        $progetto_id =  $data['project_id'];
        $vms_id = (isset($data['vm_id']))? implode(',',$data['vm_id']) : '' ;
        $query = "INSERT INTO componenti (componente_nome, progetto_id, vms_id) VALUES ('$componente_nome', '$progetto_id', '$vms_id')";
        if ($this->db->query($query) === TRUE) {
           return true;
        } else {
            return false ;
        }
    }
    public function edit_component($data,$component_id){
        $componente_nome =  $data['componentName'];
        $progetto_id =  $data['project_id'];
        $vms_id = implode(',',$data['vm_id']);
        $query = "UPDATE vmware.componenti SET 
            componente_nome = '$componente_nome' ,
            vms_id = '$vms_id'
            WHERE componente_id = '$component_id'";
        if ($this->db->query($query) === TRUE) {
           return true;
        } else {
            return false ;
        }
    }
    public function add_Project($data){
        $progetto_nome = $data['projectName'];
        $query = "INSERT INTO progetti set progetto_nome='$progetto_nome'";
        if ($this->db->query($query) === TRUE) {
           return $this->db->insert_id;
        } else {
            return false ;
        }
    }
    public function edit_Project($data){
        $progetto_nome = $data['projectName'];
        $progetto_id = $data['projectID'];
        $query = "UPDATE progetti set progetto_nome='$progetto_nome' WHERE progetto_id = '$progetto_id'";
        if ($this->db->query($query) === TRUE) {
           return $progetto_id;
        } else {
            return false ;
        }
    }
    public function add_Request($data){
        $progetto = $data['projectName'];
        $componente = $data['componentName'];
        $component_id = (isset($data['componentID']))? $data['componentID'] : '' ;
        $requestName = $data['requestName'];
        $aDateInterval = explode(' - ', $data['IntervalTime'] );
        $now = date('d/m/Y H:i');
        $startTime = $aDateInterval[0];
        $endTime = $aDateInterval[1];
        // $vmsName = implode(',',$data['vms_name']);
        $aVmsID =  ( isset($data['vms_id']) ) ?  $data['vms_id'] : array();
        $vmsID = implode(',',$aVmsID);
        $aVmsName = array();
        if(count($aVmsID) > 0){
            foreach ($aVmsID as $key => $value) {
                $aVmsName[] = $this->getVM($value)->vmname;
            }
        }
        $vmsName = (count($aVmsName) > 0) ? implode(',',$aVmsName) :  '' ;
        $query = "INSERT INTO request (progetto, componente, vmname,vms_id,start_time,end_time,type,status,componente_id,request_name,request_date) 
            VALUES ('$progetto','$componente','$vmsName','$vmsID','$startTime','$endTime','manual','Pending','$component_id','$requestName','$now')";
        if ($this->db->query($query) === TRUE) {
            return $this->db->insert_id;
         } else {
             return false ;
         }
    }
    public function getHistoryRequest($component_id=null){
        if($component_id){
            $query = "SELECT * FROM vmware.request where componente_id = '$component_id'";
        }else{
            $query = "SELECT * FROM vmware.request"; 
        }
        
        $result = $this->db->query($query);
        $requests = array();
        while($row = $result->fetch_object()) {
            $requests[] = $row;
        }
        if(count($requests)){
            return $requests;
        }else{
            return array();
        }
        
    }
    public function deleteProject($id){
        $q="DELETE FROM progetti WHERE progetto_id=$id";
        if ($this->db->query($q) === TRUE) {
            $q="DELETE FROM componenti WHERE progetto_id=$id";
            if ($this->db->query($q) === TRUE) {
                return "OK";
            }else{
                return  "Error deleting record: " . $this->db->error;
            }
         } else {
             return  "Error deleting record: " . $this->db->error;
         }
    }
    public function deleteComponent($id){
        $q="DELETE FROM componenti WHERE componente_id=$id";
        if ($this->db->query($q) === TRUE) {
            $q2 = "DELETE FROM schedule WHERE componente_id=$id";
            if ($this->db->query($q2) === TRUE) {
                return "OK";
            }else{
                return  "Error deleting record: " . $this->db->error;
            }
         } else {
             return  "Error deleting record: " . $this->db->error;
         }
    }

    public function getScheduledRequest($id_component){
        $q="SELECT * FROM schedule WHERE componente_id=$id_component";
        $result = $this->db->query($q);
        $schedRequests = array();
        while($row = $result->fetch_object()) {
            $schedRequests[] = $row;
        }
        if(count($schedRequests)){
            return $schedRequests;
        }else{
            return array();
        }
    }

    public function insertScheduleRequest( $data=array() ){
        $q = "INSERT INTO schedule (`type`, `componente_id`, `email`) 
                VALUES ('".$data['type']."', '".$data['componentid']."', '".$data['checkemailtext']."')";
        if ($this->db->query($q) === TRUE) {
            return "OK";
         } else {
             return  "ERROR inserting record: " . $this->db->error;
         }  
    }

    public function deleteScheduleRequest( $id_schedule ){
        $q = "DELETE FROM schedule WHERE id=$id_schedule";
        if ($this->db->query($q) === TRUE) {
            return "OK";
         } else {
             return  "ERROR deleting record: " . $this->db->error;
         }  
    }


    public function getVM4Project($project_id){
        $q="SELECT GROUP_CONCAT(vms_id SEPARATOR ',') as vms FROM vmware.componenti where progetto_id = $project_id " ;
        $result = $this->db->query($q);
        if($result->num_rows){
            $row = $result->fetch_object();
            return $row->vms;
        }else{
            return '';
        }
    }

    public function getComponentStatus($component_id){
        $q='SELECT 
                case 
                    when ( 
                        max(cpu_max_perc) BETWEEN (select cpu_warning from soglie limit 1) AND (select cpu_critical from soglie limit 1) OR 
                        max(mem_max_perc) BETWEEN (select mem_warning from soglie limit 1) AND (select mem_critical from soglie limit 1) OR
                        max(disk_max_io)  BETWEEN (select disk_warning from soglie limit 1) AND (select disk_critical from soglie limit 1) OR
                        max(net_max_io)   BETWEEN (select net_warning from soglie limit 1) AND (select net_critical from soglie limit 1) 
                    ) then "warning"
                    when (
                        max(cpu_max_perc) > (select cpu_critical from soglie limit 1) OR 
                        max(mem_max_perc) > (select mem_critical from soglie limit 1) OR
                        max(disk_max_io)  > (select disk_critical from soglie limit 1) OR
                        max(net_max_io)   > (select net_critical from soglie limit 1) 
                    ) then "critical" 
                    else 
                        "OK"
                END as status
                    
            from vms_stats 
            where componente_id = '.$component_id.'
            group by componente_id
            LIMIT 1';
        $result = $this->db->query($q);
        if($result->num_rows){
            $row = $result->fetch_object();
            return $row->status;
        }else{
            return '';
        }

    }

    public function login($username,$password){
        $q="SELECT * FROM utenti WHERE utente_name='$username' AND `password` = '$password' LIMIT 1";
        $result = $this->db->query($q);
	
        if($result->num_rows > 0){
            $row = $result->fetch_object() ;
            return array(
                    "utente_name" => $row->utente_name,
                    "utente_id" => $row->utente_id 
                    ) ;
        }else{
            return false;
        }
    }

}

?>