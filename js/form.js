/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function submitForm(formName) {
    formName.submit();
}

$(document).ready(function () {
    /*
    $('#publishBtn').click(function() {
        if($('#fileToUpload').get(0).files.length === 0) {
            $('#selectBtn').tooltip({
                content: "Please select your image file before publish",
                show: {
                    duration: 800
                }
            }).tooltip("open");
        }
    });
    */
    
    $('#otherCategory').hide();
    $('#fileTags').change(function () {
        if ($(this).val().toString() === "other") {
            $('#otherCategory').slideDown();
        } else {
            $('#otherCategory').slideUp();
        }
    });
    
    $('#cancelUploadBtn').on('click', function() {
        $('#selectedFile').empty();
        $('#fileToUpload').empty();
    });

    $('#fileToUpload').change(function (flag) {
        $('#selectedFile').text('Selected Artwork: ' + $(this).val().split('\\').pop());
    });
});