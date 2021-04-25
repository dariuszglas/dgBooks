$('document').ready(initPublishingHouseActions);

function initPublishingHouseActions()
{
    $("#deletion-success-alert").hide();
    $("#deletion-failure-alert").hide();
    bindActions();
}

function bindActions()
{
    $(".delete-publishing-house").on("click", deletePublishingHouseClicked);
}

function deletePublishingHouseClicked()
{
    if(!confirm('Do you really want to delete this publishing house?')){
        return;
    }
    
    let id = $(this).attr('data-id');

    let request = $.ajax({url: `/publishing_house/delete/${id}`, type: 'DELETE'});

    request.done(function(data){
        if(data.success){
            $("#deletion-failure-alert").hide();
            $("#deletion-success-alert").html("Publishing house has been successfully deleted").show();
            $(".flash-alert").hide();
            $('#publishingHouseTable').load("/publishing_house #publishingHouseTable", function(){
                bindActions();
            });
            
        } else {
            $("#deletion-success-alert").hide();
            $("#deletion-failure-alert").html("Publishing house has not been deleted").show();
            $(".flash-alert").hide();
        }        
    });

    request.fail(function(jqXHR, textStatus, errorThrown){
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
        $("#deletion-failure-alert").html("Publishing house has not been deleted").show();
        $("#deletion-success-alert").hide();
        $(".flash-alert").hide();
    });
}