$(document).ready(function () {
    $("#type").change(function () {
        type = $("#type").val();
        if (type == "outcome") {
            $(".income").css("display", "none");
            $(".outcome").css("display", "block");
        } else {
            $(".outcome").css("display", "none");
            $(".income").css("display", "block");
        }
    })
})