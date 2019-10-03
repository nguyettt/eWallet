$(document).ready(function () {
    type_select();
    $("#type").change(function () {
        type_select();
    })
})

function type_select () {
    type = $("#type").val();
    var income = data['income'];
    var outcome = data['outcome'];
    switch (type) {
        case ""+income: {
            $("." + income).css("display", "block");
            $("." + outcome).css("display", "none");
            $("#parent_id").val("");
            break;
        }
        case ""+outcome: {
            $("." + income).css("display", "none");
            $("." + outcome).css("display", "block");
            $("#parent_id").val("");
            break;
        }
        default: {
            $("." + income).css("display", "none");
            $("." + outcome).css("display", "none");
            $("#parent_id").val("");
            break;
        }
    }
}


