(function ($) {
    $.fn.livesearch = function () {
        var fileJson = "search.php";
        var noResultTxt = "No Result";
        var list_group_class = "list-group list-group-flush";
        var list_Item_class  = "list-group-item list-group-item-action d-flex justify-content-between";

        


        this.each(function () {
            var _this = $(this);
            var width = _this.width()
            var offset= _this.offset()
            var height= _this.css("height")

            var result_box = $("<div>")
                    .addClass("shadow  position-absolute d-none") //this classes (shadow, position-absolute) is from bootstrap 4.1
                    .css({  
                                "width": width + "px" , 
                                "max-height":"300px",
                                "background-color":"white",
                                "top" : height,
                                "left": offset.left+"px",
                                "overflow-y": "auto",
                                "z-index":"1000"
                    })
                    .insertAfter("body")
            var listgroup = $("<ul>").addClass(list_group_class).appendTo(result_box) //this classes (list-group list-group-flush) is from bootstrap 4.1

            _this.on("keyup keypress blur change click", function() {
                var searchText = this.value
                if( searchText.length < 4 ) {
                    result_box.addClass("d-none") //this classes (d-none) is from bootstrap 4.1
                }
                else {
                    
                    $.getJSON(fileJson,{search:searchText},function(data){
                        listgroup.empty()
                        if(data.length == 0 ){
                            $("<li>").addClass("list-group-item").html(noResultTxt).appendTo(listgroup)
                        }else{
                            $.each(data,function(key,value){
                                var item = $("<li>").addClass(list_Item_class).appendTo(listgroup)
                                // var item = $("<a href='show_component.php?id="+value.componente_id+"' class='link2main'>").appendTo(listgroup)
                                $("<a class='link2main' href='show_component.php?id="+value.componente_id+"'>" + value.vmname + "</a>").appendTo(item)
                                $("<span>").html(value.ip).appendTo(item)
                                $("<span>").html(value.vcenter).appendTo(item)
                            })
                        }
                    })
                    result_box.removeClass("d-none")
                }
            })
        
            $(document).click(function() {
                result_box.addClass("d-none")
            });
            _this.click(function(event){
                event.stopPropagation();
            })
        })
    }
}(jQuery))
