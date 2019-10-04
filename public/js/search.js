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
    var include = null;
    if ($("#include").is(":checked")) {
        include = '1';
    }

    $.ajax({
        method: "POST",
        url: "/transaction/search",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
            wallet: wallet,
            cat: cat,
            include: include,
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
                    var amount = number_format(item.amount);
                    var id = item.id;

                    html += '<div class="col-lg-12 row p-0">\n';
                    html += '<a href="transaction/' + id + '" class="transaction row col-lg-12 p-0 text-dark">\n';
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

                html += '<form id="frmExport" method="POST" action="/export/ajax">\n';
                html += '<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '">\n';
                html += '<input id="exportData" name="json" type="hidden">\n';
                html += '</form>\n';
                html += '<div class="col-lg-12 row justify-content-end">\n';
                html += '<button id="export" class="btn btn-success">Export</button>\n';
                html += '</div>';
            }
            
            $("#result").html(html);

            $("#export").click(function (e) {
                e.preventDefault();
                $.ajax({
                    method: "POST",
                    url: "/export/ajax",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {
                        data: data,
                    },
                    success: function (url) {
                        var a = document.createElement('a');
                        a.href = url;
                        a.style.display = 'none';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }
                })
            })
        }
    })
}

function number_format(num) {
    return num.toLocaleString('en');
}
