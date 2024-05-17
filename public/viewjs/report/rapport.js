$(document).ready(function() {
    $('form #rapports-datatables').on('submit', function(e) {
        e.preventDefault();

        var month = $('#month').val();
        var year = $('#year').val();

        $.ajax({
            url: '/suivi/index',
            type: 'GET',
            data: {
                month: month,
                year: year
            },
            success: function(response) {

                // Construire l'URL dynamiquement avec les valeurs de month et year
                var newUrl = "http://127.0.0.1:8000/report/suivi/index?year=" + year + "&month=" + month + "#rapports";
                location.href = newUrl;
            }
        });
    });
});