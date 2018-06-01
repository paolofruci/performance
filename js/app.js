    
    
  $(document).ready(function(){  
    // LOAD SIDEBAR 
    $(".sidebar").load("sidebar.php");
    

    function reloadSidebar(){
        var id_projectOpened = []
        $(".sidebar").find(".show[id*='prjCollapse']").each(function(){
            id_projectOpened.push($(this).attr("id"))
        })
        var id_prjactive = $(".sidebar").find(".nav-link.active[id*='project']").attr("id")
        var id_compactive = $(".sidebar").find(".nav-link.active[id*='component']").attr("id")

        // console.log(id_projectOpened,id_prjactive,id_compactive)
        $(".sidebar").load("sidebar.php",function(){
            $.each(id_projectOpened,function(k,v) {
                $("#"+v).addClass("show")
            })
            if(id_prjactive){
                $("#"+id_prjactive).addClass("active")
            }
            if(id_compactive){
                $("#"+id_compactive).addClass("active")
            }
        })
    }

    setInterval(reloadSidebar, 10000); 
    
    
    $("#main").load("homepage.php");



    $(document).on("click", ".link2main", function(e){
        e.preventDefault();
        $(".link2main").removeClass("active");
        $(this).addClass("active");          
        var link = $(this).attr('href')
        $("#main").load(link)
    })


    $("#vm-modal").on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var href = button.attr('href')
        var modal = $(this)
        modal.find(".modal-body").load("vms.php")
    })


    /* ##### TRIGGER EVENTS ON SHOW AND HIDE PROJECT MODAL */
    $('#edit-prj-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var modal = $(this)
        var prjID = button.data('prjid')
        var prjName = button.data('prjname')
        if(prjID != null && prjName != null){
            // modal.find("form").append("<input type='hidden' name='project-id' value='"+prjID+"' />")
            modal.find("[name='project-name']").val(prjName)
            modal.find("[name='project-id']").val(prjID)
            modal.find('.modal-title').text('Edit project: ' + prjName)
        } else {
            modal.find('.modal-title').text('New project')
        }
        
    })
    $('#edit-prj-modal').on('hidden.bs.modal', function (event) {
        var modal = $(this)
        modal.find("[name='project-name']").val("")
        modal.find("[name='project-id']").val("")
    })

    $(".save-prj-btn").off().click(function(){
        $("#form_save_project").submit()
    })
    $("#form_save_project").submit(function(e){
        e.preventDefault()
        var querystring = $(this).serialize()
        $.post("save_project.php",querystring ,function(data){
            if(!data.error){
                $('#edit-prj-modal').modal("hide");
                $(".sidebar").load("sidebar.php"); 
                $("#main").load("show_project.php?id_project=" + data.output)
            }
        },"json")
    })
    /* ##### //TRIGGER EVENTS ON SHOW AND HIDE PROJECT MODAL */



    /**** HIDE AND SHOW PULSANTE RIMUOVI COMPONENTI (show_project.php)  ****/
    $(document).on({
        mouseenter: function () {
            //stuff to do on mouse enter
            $(this).find(".delete-btn-float").removeClass("d-none")
        },
        mouseleave: function () {
            //stuff to do on mouse leave
            $(this).find(".delete-btn-float").addClass("d-none")
        }
    }, ".item-container");



    /* ##### TRIGGER EVENTS ON SHOW AND HIDE DELETE PROJECT/COMPONENT CONFIRM MODAL */ 
    $('#delete-prj-comp-modal').on( 'show.bs.modal', function (event) {
        var button      = $(event.relatedTarget) // Button that triggered the modal
        var modal       = $(this)
        var actfunction = button.data('function')
        var itemid      = button.data('itemid')
        var itemname    = button.data("itemname")
        var prjid       = button.data("prjid")
        $('#delete-prj-comp-modal').find(".delete-prj-comp-btn").show()
        modal.find(".done-btn").html("Annulla") 
        if(actfunction != null && itemid != null && itemname != null && prjid != null)
        {
            modal.find(".alert")
                    .html("Confermi l'eliminazione di "+ itemname +" ?")
                    .removeClass()
                    .addClass("alert alert-warning")

            modal.find("[name='item-id']").val(itemid)
            modal.find("[name='prj-id']").val(prjid)
            modal.find("[name='action-function']").val(actfunction)
        } 
        else 
        {
           alert("problemi con il recupero dei dati!")
        }
    })
     
    /* Click sul bottone "conferma Eliminazione" */
    $(".delete-prj-comp-btn").off().click( function(){
        var querystring = $('#delete-prj-comp-modal').find("form").serialize()
        var projectid   = $('#delete-prj-comp-modal input[name="prj-id"]').val()
        $.post( "delete_item.php" , querystring , function( data ){
            var modalAlert = $('#delete-prj-comp-modal').find(".alert")
            if( !data.error )
            {
                if( $("[name='action-function']").val() == 'deleteComponent' ) // se sto eliminando un componente
                {   
                    reloadSidebar()
                    $("body a.link2main#project" + projectid ).click()
                }  
                else                // se sto eliminando un progetto
                {
                    $(".sidebar").load("sidebar.php") // ricarico la sidebar 
                    $("#main").load("homepage.php")  // ricarico la homepage 
                }
                modalAlert.removeClass().addClass("alert alert-success").html(data.output)
                $('#delete-prj-comp-modal').find(".delete-prj-comp-btn").hide() 
                $('#delete-prj-comp-modal .done-btn').html("Fatto")              
            }
            else
            {
                modalAlert.removeClass().addClass("alert alert-danger").html(data.error)
                $('#delete-prj-comp-modal').find(".delete-prj-comp-btn").hide()
            }
        },"json")
    })
    /* ##### // TRIGGER EVENTS ON SHOW AND HIDE DELETE PROJECT/COMPONENT CONFIRM MODAL */ 

    /* SHOW COMPONENT PAGE */
        /* EVENTS ADDED ON LOAD COMPONENT PAGE  */
        $(document).on("loadpage_show_component", function(event,component_id){
            
            // Attiva o disattiva casella email schedule con la checkbox 
            $("input[name='schedule[checkemail]']").change( function() {
                $("input[name='schedule[checkemailtext]']").attr('disabled',! $(this).is(":checked") ).focus()
            })

            // Reset dei campi del form Schedule
            $("#scheduleModal").on('show.bs.modal', function (event) {
                var modal = $(this)
                modal.find("input[name='schedule[checkemail]']").prop("checked",false)
                modal.find("input[name='schedule[checkemailtext]']").val("").attr('disabled',true )
                modal.find("select[name='schedule[type]']").val("")
            })

            // funzione di compilazione tabella delle schedulazioni
            function drawTableSchedule(id_component){
                $(".table-schedule tbody").html('')
                $.getJSON("get_json_schedule.php?id_component=" + id_component , function(data){
                    if(data.length > 0){
                        $.each(data,function(k,v){
                            $("table.table-schedule tbody").append("<tr> \
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

            drawTableSchedule(component_id)

            // Aggiunta di una schedulazione (Post su save_schedule.php)
            $(".btn-save-schedule").click( function(event){
                event.preventDefault()
                if( $("select[name='schedule[type]']").val().length == 0 ){
                    alert("Schedule Type required")
                    $("select[name='schedule[type]']").focus()
                }else{
                    $.post( "save_schedule.php" ,
                            { "schedule" : {
                                    "type" : $("select[name='schedule[type]']").val(),
                                    "componentid" : $("[name='schedule[componentid]']").val(),
                                    "checkemailtext" : $("[name='schedule[checkemailtext]']").val()
                                }
                            }, 
                            function(data){
                                if(data=='OK'){ drawTableSchedule(component_id) }
                                else{ alert(data) }
                            }
                    )
                }
            })

            // Elimina una schedulazione
            $(".table-schedule").on("click",".deleteschedule",function(event){
                event.preventDefault()
                var schedule_id = $(this).data("id")
                $.post( "save_schedule.php", { "deleteSchedule": 1 , "id_schedule": schedule_id } , function(data){
                    if(data=='OK'){ drawTableSchedule(component_id) }
                    else{ alert(data) }
                })
            })


        })
    /*/SHOW COMPONENT PAGE */

    /* EDIT COMPONENT PAGE */
        $(document).on("loadpage_edit_component", function(event,project_id,component_id=null){
            // Pulisco i reload a tempo
            var interval;
            clearInterval(interval);

            $(".hasTooltip").tooltip();

            // azione che rimuove una vm selezionata
            $("#vmselected").on("click",".restore",function(e){
                $(this).closest("tr").remove();
            })
            // azione che rimuove tutte le vms selezionate
            $("#removeAllSeleted").click(function(){
                $("#vmselected tbody tr").remove();
            })

            // SUBMIT form
            $("#form_edit_component").submit(function(e) {
                e.preventDefault(); 
                var num_vms = parseInt($("[name='vm_id[]']").length) // controllo quante vm ho selezionato
                var action = $(this).attr("action"); // the script where you handle the form input.
                var postData = $(this).serialize()
                if($("[name='componentName']").val().trim() == '' ){
                    alert("Inserisci il nome del componente")
                    $("[name='componentName']").focus()
                    return false
                }else{
                    if(num_vms > 0){ // se ho selezionato almeno una vm
                        $.post( action , postData , function( data ) {
                            if(data.OK && !data.error){
                                $( "#main" ).load( 'show_component.php?id=' + data.OK );
                                reloadSidebar()
                            }else{
                                alert(data.error);
                            }
                            
                        },"json");
                    }else{ //se non ho selezionato nemmeno una vm
                        alert("Devi selezionare almeno una VM!")
                        return false
                    }
                }
            })
        })
    /* /EDIT COMPONENT PAGE */


})