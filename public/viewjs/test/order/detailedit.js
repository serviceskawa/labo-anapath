
function getTestmodal() {
    var test_id = $('#test_id1').val();

    // Importation des paramètres de getRemise
    var contrat_id = $('#contrat_id').val();

    let element = document.getElementById("test_id1");
    let category_test_id = element.options[element.selectedIndex].getAttribute("data-category_test_id");

    $.ajax({
        type: "POST",
        url: ROUTEGETREMISE,
        data: {
            "_token": TOKENGETREMISE,
            testId: test_id,
            contratId: contrat_id,
            categoryTestId: category_test_id
        },
        success: function (data) {

            $('#price1').val(data.data.price);

            var discount = $('#price1').val() * data.detail / 100;
            $('#remise1').val(discount);

            var total = $('#price1').val() - discount;
            $('#total1').val(total);

        },
        error: function (data) {
            console.log('Error:', data);
        }
    });

}