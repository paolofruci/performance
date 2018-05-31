    
    
  $(document).ready(function(){  
    // LOAD SIDEBAR 
    $(".sidebar").load("sidebar.php");
    
    setInterval(function(){
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
        }) // this will run after every 5 seconds
    }, 10000); 
    
    
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
        $(this).unbind()
        var querystring = $('#edit-prj-modal').find("form").serialize()
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
                    $(".sidebar").load("sidebar.php", function(){  // ricarico la sidebar e ri-seleziono il progetto per refresh 
                        $("body a.link2main#project" + projectid ).click()
                    }); 
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

    

})