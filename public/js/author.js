$('document').ready(initAuthorActions);

function initAuthorActions()
{
    $("#deletion-success-alert").hide();
    $("#deletion-failure-alert").hide();
    bindActions();
}

function bindActions()
{
    $(".delete-author").on("click", deleteAuthorClicked);
}

function deleteAuthorClicked()
{
    if(!confirm('Do you really want to delete this author?')){
        return;
    }
    
    let id = $(this).attr('data-id');

    let request = $.ajax({url: `/author/delete/${id}`, type: 'DELETE'});

    request.done(function(data){
        if(data.success){
            $("#deletion-failure-alert").hide();
            $("#deletion-success-alert").html("Author has been successfully deleted").show();
            $(".flash-alert").hide();
            $('#authorTable').load("/author #authorTable", function(){
                bindActions();
            });
            
        } else {
            $("#deletion-success-alert").hide();
            $("#deletion-failure-alert").html("Author has not been deleted").show();
            $(".flash-alert").hide();
        }        
    });

    request.fail(function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
        $("#deletion-failure-alert").html("Author has not been deleted").show();
        $("#deletion-success-alert").hide();
        $(".flash-alert").hide();
    });
}