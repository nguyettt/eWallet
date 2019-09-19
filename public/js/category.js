$(document).ready(function () {
    $("#type").change(function () {
        type_select();
    })
})

function type_select () {
    type = $("#type").val();
    switch (type) {
        case '1': {
            $(".1").css("display", "block");
            $(".2").css("display", "none");
            $(".3").css("display", "none");
            $("#parent_id").val("");
            break;
        }
        case '2': {
            $(".1").css("display", "none");
            $(".2").css("display", "block");
            $(".3").css("display", "none");
            $("#parent_id").val("");
            break;
        }
        default: {
            $(".1").css("display", "none");
            $(".2").css("display", "none");
            $(".3").css("display", "none");
            $("#parent_id").val("");
            break;
        }
    }
}
