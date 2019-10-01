$(function () {
    $("#wallet_id").val("all");
    $("#cat_id").val("all");

    var d = new Date;
    var start = new Date(d.getFullYear(), d.getMonth(), 1);
    var end = new Date(d.getFullYear(), d.getMonth() + 1, 0);

    $("#start").val(start.getFullYear() + "-" + (start.getMonth() + 1).toString().padStart(2, 0) + "-01");
    $("#end").val(end.getFullYear() + "-" + (end.getMonth() + 1).toString().padStart(2, 0) + "-" + end.getDate().toString().padStart(2, 0));

    ajaxFilter();

    $("#search").click(function () {
        ajaxFilter();
    })
})

function ajaxFilter() {
    var wallet = $("#wallet_id").val();
    var cat = $("#cat_id").val();
    var start = $("#start").val();
    var end = $("#end").val();

    $.ajax({
        method: "POST",
        url: "/transaction/search",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            wallet: wallet,
            cat: cat,
            start: start,
            end: end
        },
        success: function (data) {
            var html = "";
            if (Object.keys(data).length > 0) {
                Object.keys(data).forEach (function (key) {
                    var item = data[key];
                    var date = item.created_at.split(" ")[0];
                    var details = item.details;
                    var amount = item.amount;
                    var id = item.id;

                    html += '<div class="col-lg-12 row pr-0">\n';
                    html += '<a href="transaction/' + id + '" class="transaction row col-lg-12 pr-0 text-dark">\n';
                    html += '<div class="col-lg-8">\n';
                    html += '<div class="col-lg-12">\n';
                    html += '<h6 class="mt-auto mb-auto">' + date + '</h6>\n';
                    html += '</div>\n';
                    html += '<div class="col-lg-12">\n';
                    html += '<span class="mt-auto mb-auto h6">' + details + '</span>\n';
                    html += '</div>\n';
                    html += '</div>\n';
                    html += '<div class="col-lg-4 pr-0 d-flex justify-content-end">\n';

                    if (item.type == 1 || item.benefit_wallet == wallet) {
                        html += '<h5 class="mt-auto mb-auto text-success">' + amount + ' đ</h5>\n';
                    } else {
                        html += '<h5 class="mt-auto mb-auto text-danger">' + amount + ' đ</h5>\n';
                    }

                    html += '</div>\n';
                    html += '</a>\n';
                    html += '</div>\n';
                    html += '<hr style="width:100%">\n';
                })

                html += '<a href="#" class="btn btn-success float-right">Export</a>\n';

                // $("#export").removeAttr("hidden");
            }
            
            $("#result").html(html);
        }
    })
}
