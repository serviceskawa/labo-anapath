$('.dropify').dropify();

    var s2 = $("#selectRoles").select2({
        placeholder: "Choose event type",
        tags: true
    });
    console.log(vals)

    vals.forEach(function(e) {
        if (!s2.find('option:contains(' + e + ')').length)
            s2.append($('<option value=' + e.id + '>').text(e.name));
    });

    array.forEach(element => {

    });
    s2.val(vals).trigger("change");