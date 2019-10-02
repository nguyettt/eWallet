$(function () {
    type_select();
    getBalance();
    number_format();

    $("#type").change(function () {
        type_select();
        $("#benefit_wallet").val("");
    });

    $("#wallet_id").change(function () {
        getBalance();
    })
})

function getBalance () {
    var id = $("#wallet_id").val();
    $.ajax({
        method:"POST",
        url:"wallet/getbalance",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {id : id},
        success: function (data) {
            $("#balance").val(data + " đ");
        }
    });
}

function number_format() {
    var num = $("#amount").val();
    if (num != '') {
        num = num.replace(/\./g, '');
        num = parseInt(num).toLocaleString('en');
    }
    $("#amount").val(num);
}
