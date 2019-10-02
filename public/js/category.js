$(document).ready(function () {
    type_select();

    $("#type").change(function () {
        type_select();
    })
})



function delCat(id) {
    var name = $("#cat_" + id).val();
    if (confirm("Are you sure to delete " + name + " and it's sub categories?")) {
        $("#frmCatDel_" + id).submit();
    }
}
