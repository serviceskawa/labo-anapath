var baseUrl = "{{url('/')}}";

$(document).ready(function () {
    $("form #rapports-datatables").on("submit", function (e) {
        e.preventDefault();

        var month = $("#month").val();
        var year = $("#year").val();

        $.ajax({
            url: "/suivi/index",
            type: "GET",
            data: {
                month: month,
                year: year,
            },
            success: function (response) {
                // Construire l'URL dynamiquement avec les valeurs de month et year
                var newUrl =
                    baseUrl +
                    "/suivi/index?year=" +
                    year +
                    "&month=" +
                    month +
                    "#rapports";
                location.href = newUrl;
            },
        });
    });
});

$(document).ready(function () {
    $("form #rapports-datatables-report").on("submit", function (e) {
        e.preventDefault();

        var month = $("#month").val();
        var year = $("#year").val();

        $.ajax({
            url: "/report/list",
            type: "GET",
            data: {
                month: month,
                year: year,
            },
            success: function (response) {
                // Construire l'URL dynamiquement avec les valeurs de month et year
                var newUrl =
                    baseUrl +
                    "/report/list?year=" +
                    year +
                    "&month=" +
                    month +
                    "#rapports";
                location.href = newUrl;
            },
        });
    });
});
