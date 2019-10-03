$(function () {
    type_select();
    if ($("#wallet_id").val() != null) {
        getBalance();
    }
    number_format();

    $("#type").change(function () {
        type_select();
        $("#benefit_wallet").val("");
    });

    $("#wallet_id").change(function () {
        getBalance();
    })

    $("#frmTransaction").submit(function () {
        var amount = $("#amount").val();
        if (amount != "") {
            amount = amount.replace(/\,/g, "");
        }
        $("#amount").val(amount);
        return true;
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
        num = num.replace(/\,/g, '');
        num = parseInt(num).toLocaleString('en');
    }
    $("#amount").val(num);
}

function type_select () {
    var type = $("#type").val();
    var income = data['income'];
    var outcome = data['outcome'];
    var transfer = data['transfer'];
    switch (type) {
        case ''+income: {
            $("."+income).css("display", "block");
            $("."+outcome).css("display", "none");
            $("."+transfer).css("display", "none");
            $(".benefit_wallet_block").css("display", "none");
            $(".cat").css("display", "flex");
            $("#cat_id").val("");
            break;
        }
        case ''+outcome: {
            $("."+income).css("display", "none");
            $("."+outcome).css("display", "block");
            $("."+transfer).css("display", "none");
            $(".benefit_wallet_block").css("display", "none");
            $(".cat").css("display", "flex");
            $("#cat_id").val("");
            break;
        }
        case ''+transfer: {
            $("."+income).css("display", "none");
            $("."+outcome).css("display", "none");
            $("."+transfer).css("display", "block");
            $(".benefit_wallet_block").css("display", "flex");
            $(".cat").css("display", "none");
            $("#cat_id").val($("."+transfer).val());
            break;
        }
        default: {
            $("."+income).css("display", "none");
            $("."+outcome).css("display", "none");
            $("."+transfer).css("display", "none");
            $(".benefit_wallet_block").css("display", "none");
            $(".cat").css("display", "flex");
            $("#cat_id").val("");
            break;
        }
    }
}
