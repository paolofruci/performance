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
        <a  href="new_request.php" class="btn btn-light btn-sm getPerformance" 
            data-toggle="modal" 
            data-target="#perfModal" 
            data-action="newRequestperComponent"
            data-returnto="show_component.php?id=<?=$id?>"
            data-parentid="<?=$id?>">
            <span class="oi oi-bar-chart"></span> Get Performance
        </a>
        <a href="#" class="btn btn-light btn-sm getSchedule" id="dropdownschedulelink" data-toggle="modal" data-target="#scheduleModal">
            <span class="oi oi-timer"></span> Schedule
        </a>
    </div>
</div>
<hr>

<div class="d-flex justify-content-between">
    <h6></h6>
    <h6 class="text-muted" >Performance Last hour Max value </h6> 
    <h8 class="text-muted" >Last Update: <?=$component->lastupdate?> &nbsp;</h8> 
</div>


<table id="vms-componente" class="display" style='text-align:center;vertical-align:middle'>
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
</table>
<hr>
<h6 class="text-muted">Performance History</h6>
<div class="row">
    <div class="col-sm-12" id="history-request"></div>
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
<!-- <div id="perfModal" class="modal fade get-performance-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div> -->
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


    $(document).trigger("loadpage_show_component" , [<?=$id?>]);

    var table_vms_componente = $("#vms-componente").DataTable({
        "dom": 't',
        "ajax": {
            url : 'get_json_vms_per_component.php',
            data : { 'component_id' : "<?=$id?>" }
        },
        "columns": [
            { "data": "vm_id" , className:"d-none vm_id"},
            { "data": "vmname" , className: "vm_name" },
            { 
                "data": "powerstate" ,  
                "render" : function(data,type, row){
                    if(data=='PoweredOff')
                        return "<span class='text-muted'>"+data+"</span>";
                    else
                        return data;
                }
            },
            { 
                "data": "badge" ,
                "render" : function(data,type, row){
                    if(data){
                         if (data == 'green'){
                            if(row.powerstate == 'PoweredOff')
                                return '<span style="color:#ccc" class="oi oi-circle-check"></span>'
                            else
                                return '<span class="text-success oi oi-circle-check"></span>' 
                         }
                        else if (data == 'yellow') 
                            return '<span class="text-warning oi oi-warning"></span>'
                        else if (data == 'red') 
                            return '<span class="text-danger oi oi-warning"></span>'
                    }else{
                        return ""
                    }
                }    
            },
            { "data": "ip" },
            { "data": "ncpu" },
            { "data": "memorymb" },
            { 
                "data": "mem_max_perc.value" , 
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass(rowData.mem_max_perc.className)
                }
            },
            {   
                "data": "cpu_max_perc.value",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass(rowData.cpu_max_perc.className)
                }
            },
            { 
                "data": "disk_max_io.value",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass(rowData.disk_max_io.className)
                }
            },
            { 
                "data": "net_max_io.value",
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass(rowData.net_max_io.className)
                }
            }    
        ],
        "order": [[ 1, "asc" ]]
    })

    var table = $('#Requests-history-comp').DataTable( {
        "ajax": {
            url : 'historyRequests.php',
            data : { 'component_id' : "<?=$id?>" }
        },
        "columns": [
            { "data": "request_id" },
            { "data": "request_name" },
            { "data": "start_time", orderable:false },
            { "data": "end_time" ,  orderable:false  },
            { "data": "interval_time", orderable:false },
            { "data": "type" },
            { "data": "status",  orderable:false  },
            { "data": "request_id" , orderable:false }
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
        table_vms_componente.ajax.reload(null, false);
    }, 10000 );






</script>