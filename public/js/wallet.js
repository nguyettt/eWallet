$(function () {

    $("#calendar").click(function (e) {
        $("#datepicker").datepicker({
            dateFormat: "dd-mm-yy"
        });
        e.preventDefault();
        $("#datepicker").datepicker("show");
    })

    $("#datepicker").change(function () {
        var date = $("#datepicker").val();
        console.log(window.location.href);
        // window.location.href = "?time="+date+"#date";
    });
})
