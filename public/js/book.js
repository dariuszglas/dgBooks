$('document').ready(initBookActions);

function initBookActions()
{
    $("#deletion-success-alert").hide();
    $("#deletion-failure-alert").hide();
    bindActions();
}

function bindActions()
{
    $(".delete-book").on("click", deleteBookClicked);
}

function deleteBookClicked()
{
    if(!confirm('Do you really want to delete this book?')){
        return;
    }
    
    let id = $(this).attr('data-id');

    let request = $.ajax({url: `/book/delete/${id}`, type: 'DELETE'});

    request.done(function(data){
        if(data.success){
            $("#deletion-failure-alert").hide();
            $("#deletion-success-alert").html("Book has been successfully deleted").show();
            $(".flash-alert").hide();
            $('#bookTable').load("/book #bookTable", function(){
                bindActions();
            });
            
        } else {
            $("#deletion-success-alert").hide();
            $("#deletion-failure-alert").html("Book has not been deleted").show();
            $(".flash-alert").hide();
        }        
    });

    request.fail(function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
        $("#deletion-failure-alert").html("Book has not been deleted").show();
        $("#deletion-success-alert").hide();
        $(".flash-alert").hide();
    });
}