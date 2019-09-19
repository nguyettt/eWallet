$(function () {
    // Sidebar toggle behavior
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar, #content').toggleClass('active');
    });
});

function delWallet(id) {
    var name = $("#name_" + id).val();
    if (confirm("Are you sure you want to delete " + name)) {
        $("#frmDel_" + id).submit();
    }
}
