
$('.dropify').dropify();
ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });
ClassicEditor
    .create(document.querySelector('#editor2'))
    .catch(error => {
        console.error(error);
    });