$(function () {
    var time = $("#time").text();
    time = time.split(" - ");
    var month = time[0];
    var year = time[1];
    var prev = [];
    var next = [];
    switch (month) {
        case 1: {
            prev = {
                'month': 12,
                'year': parseInt(year) - 1
            }
            next = {
                'month': 2,
                'year': year
            }
        }
        case 12: {
            prev = {
                'month': 11,
                'year': year
            }
            next = {
                'month': 1,
                'year': parseInt(year) + 1
            }
        }
        default: {
            prev = {
                'month': parseInt(month) - 1,
                'year': year
            }
            next = {
                'month': parseInt(month) + 1,
                'year': year
            }
        }
    }
    var id = $("#wallet_id").val();
    prev = prev['month'].toString().padStart(2, "0") + "-" + prev['year'].toString().padStart(2, "0");
    next = next['month'].toString().padStart(2, "0") + "-" + next['year'].toString().padStart(2, "0");
    $("#prevMonth").attr("href", "wallet/" + id + "?time=" + prev);
    $("#nextMonth").attr("href", "wallet/" + id + "?time=" + next);
    // debugger;
})
