$(function () {
    "use strict";

    // form repeater jquery
    // $(".invoice-repeater, .repeater-default").repeater({
    //     show: function () {
    //         $(this).slideDown();
    //         // Feather Icons
    //         // document.getElementById('itemcco').id = 'div2'
    //         console.log(this);
    //     },
    //     hide: function (deleteElement) {
    //         if (confirm("Are you sure you want to delete this element?")) {
    //             $(this).slideUp(deleteElement);
    //         }
    //     },
    // });

    $("#index_container").nestedForm({
        forms: ".nested-form",

        adder: "#index_add",

        increment: 1,

        startIndex: 0,

        maxIndex: 15,

        onBuildForm: function ($elem, index) {
            // $elem.find('input[type="number"]').val(index);
            $elem.find('input[id="itemcost"]').val(index);

            $elem.find('input[id="itemcost"]').each(function (i, input) {
                // $(input).attr("id") = $(input).attr("id") + index;
                document.getElementById("itemcost").id = "itemcost" + index;
                console.log("a");
            });
            function getTest(index) {
                var test_id = $("#test_id").val();

                $.ajax({
                    type: "GET",
                    url: "{{ url('gettest') }}" + "/" + test_id,
                    success: function (data) {
                        $("#itemcost" + index).val(data.price);
                    },
                    error: function (data) {
                        console.log("Error:", data);
                    },
                });
            }
            // showID($elem);
        },
    });

    // function showID($elem) {
    //     console.log("a");
    //     $elem.find('input[type="number"]').each(function (i, input) {
    //         $(input).val($(input).attr("id"));
    //     });
    // }
});
