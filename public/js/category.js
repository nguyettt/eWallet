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

function delCat(id) {
    var name = $("#cat_" + id).val();
    if (confirm("Are you sure to delete " + name + " and it's sub categories?")) {
        $("#frmCatDel_" + id).submit();
    }
}
