$(document).ready(function () {
    type_select();

    $("#type").change(function () {
        type_select();
    });
})

function type_select () {
    type = $("#type").val();
    switch (type) {
        case '1': {
            $(".1").css("display", "block");
            $(".2").css("display", "none");
            $(".3").css("display", "none");
            $(".benefit_wallet_block").css("display", "none");
            $(".cat").css("display", "flex");
            $("#cat_id").val("");
            $("#benefit_wallet").val("");
            break;
        }
        case '2': {
            $(".1").css("display", "none");
            $(".2").css("display", "block");
            $(".3").css("display", "none");
            $(".benefit_wallet_block").css("display", "none");
            $(".cat").css("display", "flex");
            $("#cat_id").val("");
            $("#benefit_wallet").val("");
            break;
        }
        case '3': {
            $(".1").css("display", "none");
            $(".2").css("display", "none");
            $(".3").css("display", "block");
            $(".benefit_wallet_block").css("display", "flex");
            $(".cat").css("display", "none");
            $("#cat_id").val($(".3").val());
            $("#benefit_wallet").val("");
            break;
        }
        default: {
            $(".1").css("display", "none");
            $(".2").css("display", "none");
            $(".3").css("display", "none");
            $(".benefit_wallet_block").css("display", "none");
            $(".cat").css("display", "flex");
            $("#cat_id").val("");
            $("#benefit_wallet").val("");
            break;
        }
    }
}
