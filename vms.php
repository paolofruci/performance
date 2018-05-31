<?php 
    include("data.php");
    $mydb = new db();    
?>
<style>
    #vmsTable th, #vmsTable td{
        font-size:11px;
    }
    #vmselected td , #vmselected th{
        font-size:11px;
    }
    .dt-buttons{
        float:left;
    }
</style>
<table class="display" id="vmsTable" style="width: 100%">
    <thead>
        <tr>
            <th></th>
            <!-- <th scope="col">Vcenter</th> -->
            <th scope="col">VMName</th>
            <th scope="col">IP</th>
        </tr>
        <tr>
            <th></th>
            <!-- <th class="filter" id="filter_vcenter"></th> -->
            <th class="filter" id="filter_vmname"></th>
            <th class="filter" id="filter_ip"></th>
        </tr>
    </thead>
</table>
<!-- <script type="text/javascript" src="DataTables/DataTables-1.10.16/js/dataTables.select.min.js"></script> -->
<script type="text/javascript" src="DataTables/DataTables-1.10.16/js/dataTables.buttons.min.js"></script>
<script type="text/javascript">
var selected = {};
var table = $('#vmsTable').DataTable( {
        orderCellsTop : true,
        dom         : 'Bfrtip',
        processing  : true,
        serverSide  : true,
        ajax        : {
                        "url" : "get_json_vms.php",
                        "data" : function ( d ) {
                            // add filter search
                            $.each(d.columns , function(k,column){
                                columnname = column.name 
                                searchValue = $("#vmsTable th.filter#filter_" + columnname + " input").val()
                                searchValue = searchValue ? searchValue : ''
                                d.columns[k]["search"]["value"] = searchValue
                            });

                            d.exclude = [];
                            $.each( $("[name='vm_id[]']") ,function(k,v){
                                d.exclude.push($(v).val())
                            })
                             
                        }
                    },
        
        columns     : [
                        {   
                            "data" : "vm_id"  ,
                            "visible" : false
                        },
                        // { "data": "vcenter" , name : "vcenter"},
                        { "data": "vmname"  , name : "vmname" ,  className : "vmname" },
                        { "data": "ip" , name : "ip" },
                    ],
        rowId : "vm_id",
        rowCallback : function( row, data ) {
            if ( data.vm_id in selected ) {
                $(row).addClass('selected');
                $(this).trigger('cssClassChanged')
               
            }
        },

        buttons: [
            {
                text : 'seleziona tutto',
                className: 'btn btn-secondary btn-sm',
                action :function(){ 
                    $('#vmsTable tbody tr').each(function(){                        
                        $(this).addClass('selected');
                        $(this).trigger('cssClassChanged')
                    })
                }
            },
            {
                text : 'deseleziona tutto',
                className: 'btn btn-secondary btn-sm',
                action :function(){ 
                    selected = {}
                    $('#vmsTable tbody tr').each(function(){
                        $(this).removeClass('selected');
                        $(this).trigger('cssClassChanged')
                    })
                }
            },
            {
                text : 'Aggiungi le macchine selezionate',
                className: 'btn btn-secondary btn-sm',
                action : function(){
                    // AGGIUNTA DELLE VM SELEZIONATE
                    $.each(selected,function(k,v){
                         var vm_id = v[0]
                         var vm_name = v[1]
                        $("<tr> \
                                <td><input type='hidden' value='"+vm_id+"' name='vm_id[]' />" + vm_name + "</td> \
                                <td><a href='#' class='restore'><span class='oi oi-trash'></span></a></td> \
                            </tr>" ).appendTo("table#vmselected tbody")
                    })
                    
                    //close the modal
                    $('#vm-modal').modal('hide')
                }
            } 
        ]
    } );
 
$('#vmsTable tbody').on('click', 'tr', function () {
    $(this).toggleClass('selected');
    $(this).trigger('cssClassChanged')
} );

$("#vmsTable tbody").on("cssClassChanged", "tr", function(){
    var id = this.id;
    var name = $(this).find(".vmname").html()

    if( $(this).hasClass('selected') && !(id in selected) ){
        selected[ id ] = [id,name];
    } else {
        delete selected[id]
    }
})

// Setup - add a text input to each header cell
$('#vmsTable thead tr th.filter').each( function () {
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
} );
  
$( 'input', "#vmsTable thead th.filter" ).on( 'keyup change', function () {
    table.ajax.reload();
} );

</script>