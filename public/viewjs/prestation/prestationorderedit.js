function getprestation() {
    var prestation_id = $('#prestation_id2').val();

    $.ajax({
        type: "POST",
        url: ROUTEGETPRESTATIONORDER,
        data: {
            "_token":TOKENGETPRESTATIONORDER,
            prestationId: prestation_id,
        },
        success: function(data) {
            console.log(data.total);
            $('#total2').val(data.total);
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });

}