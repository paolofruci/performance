<?php 

    include("data.php") ;
    $mydb = new db();
    $id = $_GET['id']; 
    $component = $mydb->getComponent($id);
    $schedules = $mydb->getScheduledRequest($id)
    // $HistoryRequests = $mydb->getHistoryRequest( $id );
?>

<div class="d-flex align-items-start align-items-stretch justify-content-start">
    <h4><?=$component->progetto_nome?></h4> 
    <div class="border-left ml-2 pl-2">   
        <h4 class=" text-muted"><?=$component->componente_nome?></h4>
    </div> 
    <div class="dropdown border-left  ml-2 pl-2">
        <a class="dropdown-toggle btn btn-link btn-sm" href="#" id="dropdownMenuLink" data-toggle="dropdown" >
            Actions
        </a>
        
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a href="edit_component.php?project_id=<?=$component->progetto_id?>&component_id=<?=$id?>"  class="link2main dropdown-item">
                <span class="oi oi-pencil"></span> Edit
            </a>
            <a  href="#" 
                title="rimuovi componente"
                data-function="deleteComponent"
                data-itemid="<?=$id?>"
                data-itemname="<?=$component->componente_nome?>"
                data-prjid="<?=$component->progetto_id?>"
                data-toggle="modal" 
                data-target="#delete-prj-comp-modal" 
                class="dropdown-item">
                    <span class="oi oi-circle-x"></span> Delete
            </a>
        </div>
    </div>
    <div class="ml-auto">
        <a href="new_request.php" class="btn btn-light btn-sm getPerformance" data-toggle="modal" data-target="#perfModal">
            <span class="oi oi-bar-chart"></span> Get Performance
        </a>
        <a href="#" class="btn btn-light btn-sm getSchedule" id="dropdownschedulelink" data-toggle="modal" data-target="#scheduleModal">
            <span class="oi oi-timer"></span> Schedule
        </a>
    </div>
</div>
<hr>

<h6 class="text-muted" align="center">Performance Last hour Max value</h6> 

<table id="vms-componente" class="table table-sm" style='text-align:center;vertical-align:middle'>
    <thead class="">
        <tr>
            <th scope="col" class="d-none">#</th>
            <th scope="col">VMName</th>
            <th>PowerState</th>
            <th>Health</th>
            <th>IPaddress</th>
            <th>vCPU</th>
            <th>Memory (MB)</th>
            <th>Mem %</th>
            <th>Cpu %</th>
            <th>Disk KBps</th> 
            <th>Net KBps</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            if(isset($component->vms)) :
                foreach ($component->vms as $key => $oVM) : ?>
                <tr>
                    <td class="vm_id d-none"><?=$oVM->vm_id?></td>
                    <td class="vm_name"><?=$oVM->vmname?></td>
                    <td><?=$oVM->powerstate?></td>
                    <td>
                        <?php
                         if ($oVM->badge == 'green') 
                            echo '<span class="text-success oi oi-circle-check"></span>' ;
                        elseif ($oVM->badge == 'yellow') 
                            echo '<span class="text-warning oi oi-warning"></span>';
                        elseif ($oVM->badge == 'red') 
                            echo '<span class="text-danger oi oi-warning"></span>';
                        ?>
                    </td>
                    <td><?=$oVM->ip?></td>
                    <td><?=$oVM->ncpu?></td>
                    <td><?=$oVM->memorymb?></td>
                    <td class="<?=$mydb->getStatusClassColor("mem",$oVM->mem_max_perc)?>"><?=$oVM->mem_max_perc?></td>
                    <td class="<?=$mydb->getStatusClassColor("cpu",$oVM->cpu_max_perc)?>"> <?=$oVM->cpu_max_perc?> </td>
                    <td class="<?=$mydb->getStatusClassColor("disk",$oVM->disk_max_io)?>"><?=$oVM->disk_max_io?></td> 
                    <td class="<?=$mydb->getStatusClassColor("net",$oVM->net_max_io)?>"><?=$oVM->net_max_io?></td>
                </tr>
        <?php 
                endforeach; 
            endif;?>
    </tbody>
</table>
<hr>
<h6 class="text-muted">Performance History</h6>
<div class="row">
    <div class="col-sm-12" id="history-request">
            
    </div>
</div>
<table id="Requests-history-comp" class="display" style="width:100%">
    <thead class="">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Task</th>
            <th scope="col">Start Time</th>
            <th scope="col">End Time</th>
            <th scope="col">Interval</th>
            <th scope="col">type</th>
            <th scope="col">status</th>
            <th scope="col">Report</th>
        </tr>
    </thead>
</table>
<!-- PERFORMANCE MODAL  -->
<div id="perfModal" class="modal fade get-performance-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<!-- // PERFORMANCE MODAL  -->


<!-- SCHEDULE MODAL -->
<div id="scheduleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Schedule Performance Request for <?=$component->componente_nome?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="addScheduleForm" class="">
                    <input Type="hidden" name="schedule[componentid]" value="<?=$id?>" />
                    <div class="d-flex">
                        <div class="w-50 p-1">
                            <label for="scheduletype">Type</label>
                            <select class="form-control form-control-sm" name="schedule[type]" id="scheduletype" required>
                                <option value=""></option>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="w-50 p-1">
                            <div class="form-check">
                                <input name="schedule[checkemail]" type="checkbox" class="form-check-input form-control-sm" id="ScheduleCheckEmail">
                                <label class="form-check-label" for="ScheduleCheckEmail">
                                    Send Email
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="ScheduleEmail"></label>
                                <input disabled name="schedule[checkemailtext]" type="email" class="form-control form-control-sm" id="ScheduleEmail" placeholder="email@example.com">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn-save-schedule btn btn-primary btn-sm">Add Schedule</button>
                    </div>
                </form>
                <hr>
                <h6 class="text-muted">Lista Schedulazioni</h6>
                <table class="table table-sm table-schedule">
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">    
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    $("input[name='schedule[checkemail]']").change( function() {
        $("input[name='schedule[checkemailtext]']").attr('disabled',! $(this).is(":checked") ).focus()
    })
    $("#scheduleModal").on('show.bs.modal', function (event) {
        var modal = $(this)
        modal.find("input[name='schedule[checkemail]']").prop("checked",false)
        modal.find("input[name='schedule[checkemailtext]']").val("").attr('disabled',true )
        modal.find("select[name='schedule[type]']").val("")
    })

    $(".table-schedule").on("click",".deleteschedule",function(event){
        var schedule_id = $(this).data("id")
        event.preventDefault()
        $.post( "save_schedule.php", { "deleteSchedule": 1 , "id_schedule": schedule_id } , function(data){
            if(data=='OK'){
                drawTableSchedule()
            }else{
                alert(data);
            }
        })
    })

    $(".btn-save-schedule").click( function(event){
        event.preventDefault()
        if( $("select[name='schedule[type]']").val().length == 0 ){
            alert("Schedule Type required")
            $("select[name='schedule[type]']").focus()
        }else{
            // alert("schedule type OK")
            $("form[name=addScheduleForm]").submit() 
        }
        
    })
    
    $("form[name=addScheduleForm]").submit(function(event){
        event.preventDefault()
        $.post( "save_schedule.php" , $(this).serializeArray() , function(data){
            if(data=='OK'){
                drawTableSchedule()
            }else{
                alert(data);
            }
        })
        
    })

    drawTableSchedule()

    function drawTableSchedule(){
        $("table.table-schedule tbody").html('')
        var id_component = "<?=$id?>"
        $.getJSON("get_json_schedule.php?id_component=" + id_component , function(data){
            if(data.length > 0){
                $.each(data,function(k,v){
                    $("table.table-schedule tbody").append("<tr> \
                        <td></td> \
                        <td></td> \
                        <td>"+v.type+"</td> \
                        <td>"+v.email+"</td> \
                        <td><a class='deleteschedule' href='#' data-id='"+v.id+"' ><span class='oi oi-trash'></span></a></td> \
                    </tr>")
                })
            }else{
                $("table.table-schedule tbody").append("<tr> \
                        <td colspan='5' class='table-warning'>Nessuna richiesta di performance Schedulata per questo componente</td> \
                    </tr>")
            }
        })
    }






    $('#perfModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var href = button.attr('href')
        var modal = $(this)
        var data = {
            "projectName": "<?=$component->progetto_nome?>" ,
            "componentName" : "<?=$component->componente_nome?>",
            "componentID" : <?=$component->componente_id?>
        };
        data["vms"] = [];
        i=0
        $("#vms-componente tbody tr").each(function(k,v){
            data["vms"][i] = {};
            data["vms"][i].id = $(v).find("td.vm_id").text()
            data["vms"][i].name = $(v).find("td.vm_name").text()
            i++
        })
        
        $.post("new_request.php",data,function(result){
            modal.find('.modal-content').html(result)
        })
    })
    $('#perfModal').on('hidden.bs.modal', function (event) {
        $("#main").load("show_component.php?id=<?=$id?>")
    })


    var table = $('#Requests-history-comp').DataTable( {
        // "processing": true,
        // "serverSide": true,
        "ajax": {
            url : 'historyRequests.php',
            data : { 'component_id' : "<?=$id?>" }
        },
        "columns": [
            { "data": "request_id" },
            { "data": "request_name" },
            { "data": "start_time" },
            { "data": "end_time" },
            { "data": "interval_time" },
            { "data": "type" },
            { "data": "status" },
            { "data": "request_id"}
        ],
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { 
                "targets": 7,
                "render": function ( data, type, row ) {
                            return "<a target='_blank' href='/performance_report/"+data+"/stats_"+data+".html' >HTML</a> &nbsp; \
                                    <a target='_blank' href='/performance_report/"+data+"/stats_"+data+".zip' >ZIP</a>";
                        }
            }
        ]
    } );
    var interval;
    clearInterval(interval);
    interval = setInterval( function () {
        table.ajax.reload(null, false);
    }, 10000 );






</script>