<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">New Performance Request</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="requestBody">
        <form id="add_request" action="save_request.php">
            <?php if(isset($_POST['componentID'])) : ?>
                <input type="hidden" name="componentID" value="<?=$_POST['componentID']?>" />
            <?php endif ; ?>
            <input type="hidden" name="componentName" value="<?=$_POST['componentName']?>" />
            <input type="hidden" name="projectName" value="<?=$_POST['projectName']?>" />
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><?=$_POST['projectName']?></li>
                            <li class="breadcrumb-item active" aria-current="page"><?=$_POST['componentName']?></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="form-row">      
                <div class="form-group col-md-6">
                    <label for="">Request Name</label>
                    <input type="text"  name="requestName" class="form-control form-control-sm" value="<?=$_POST['projectName']?>_<?=$_POST['componentName']?>" />
                </div>  
            </div>
            <div class="form-row">      
                <div class="form-group col-md-6">
                    <label for="">Interval</label>
                    <input type="text" readonly name="IntervalTime" class="datetime form-control form-control-sm"  required/>
                </div>  
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="">VM Selected</label>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <?php if($_POST['projectName'] == 'Performance') : ?>
                            <thead>
                                <tr>
                                    <th style="font-size: 11px">
                                        VM Selezionate  
                                    </th>
                                    <th>
                                        <a href="#" class="hasTooltip btn btn-light btn-sm" data-toggle="modal" data-target="#vm-modal" data-placement="top" title="Aggiungi VMs">
                                            <span class="oi oi-plus"></span>
                                        </a>
                                        <a href="#" class="hasTooltip btn btn-light btn-sm" id="removeAllSeleted" data-placement="top" title="rimuovi tutto">
                                            <span class="oi oi-x"></span>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <?php endif; ?>
                            <tbody>
                            <?php if(isset($_POST['vms'])) : ?>
                                <?php foreach($_POST['vms'] as $key => $value) : ?>
                                    <tr>
                                        <td>
                                            
                                            <input type="checkbox" checked name="vms_id[]" value="<?=$value['id']?>" /> 
                                            <!-- <input type="hidden" name="vms_name[]" value="<?=$value['name']?>" /> -->
                                        </td>
                                        <td>
                                            <?=$value['name']?>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif ?>
                            </tbody>
                        </table>     
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" id="submitButton" class="btn btn-primary">Send Request  </button>
</div>

<script>
    // $('input.datetime').daterangepicker();
    
    var daterangepicker = $('input.datetime').daterangepicker({
        timePicker: true,
        timePicker24Hour:true,
        autoUpdateInput: false,
        showCustomRangeLabel : true,
        maxDate:  moment(),
        minDate: moment().subtract(30, 'days'),
        ranges: {
            'Last Hour': [moment().subtract(1, 'Hours'), moment()],
            'Last 24 Hours': [moment().subtract(24, 'Hours'), moment()],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()]
        },
        // startDate: moment().startOf('hour'),
        // endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
            format: 'DD/MM/YYYY HH:mm',
            cancelLabel: 'Clear'
        }
    });
    $('input.datetime').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
    });

    $('input.datetime').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });


        
// SUBMIT form
    $("#submitButton").click(function(){
        if( $("[required]").val().length == 0 ){
            $("[required]").focus()
            alert("Interval required!")

        }else{
            // alert("Interval OK")
            $("#add_request").submit();
        }
        
    })
    $("#add_request").submit(function(e) {
        
        var action = $(this).attr("action"); // the script where you handle the form input.
        var postData = $(this).serialize()
        $.post( action , postData , function( data ) {
            $( ".requestBody" ).html( data );
            $("#submitButton").hide();
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
</script>
    <!-- dd/mm/YYYY HH:ii:ss -->