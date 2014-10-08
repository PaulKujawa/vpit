var itemTemplate =
    '<div class="dz-preview dz-file-preview">'                          +
        '<div class="dz-details">'                                      +
            '<img data-dz-thumbnail />'                                 +
            '<div class="dz-filename">'                                 +
                '<span data-dz-name></span>'                            +
            '</div>'                                                    +
        '</div>'                                                        +
        '<div class="dz-progress">'                                     +
            '<span class="dz-upload" data-dz-uploadprogress></span>'    +
        '</div>'                                                        +
        '<div class="dz-size" data-dz-size></div>'                      +

        /*'<a class="dz-remove" href="#" data-dz-remove>'               +
            '<span class="glyphicon glyphicon-minus"></span>'           +
        '</a>'                                                          +*/

        '<div class="dz-success-mark">'                                 +
            '<span>✔</span>'                                            +
        '</div>'                                                        +
        '<div class="dz-error-mark">'                                   +
            '<span>✘</span>'                                            +
        '</div>'                                                        +
        '<div class="dz-error-message">'                                +
            '<span data-dz-errormessage></span>'                        +
        '</div>'                                                        +
    '</div>';



Dropzone.options.dropzoneId = {
    parallelUploads: 3,
    maxFilesize: 4, // in MB, according to server & DB validation
    thumbnailWidth: null, // height:100%, width:auto (100)
    acceptedFiles: "image/*",
    previewTemplate: itemTemplate,

    init: function() {
        var thisDropzone = this;

        $.ajax({
            url: $('#dropzoneId').attr('data-getAllLink'),
            type: "POST"
        }).done(function(response) {
            $.each(response.files, function(index, file) {
                var image = {name: file.title, size: file.size};
                thisDropzone.options.addedfile.call(thisDropzone, image);
                thisDropzone.options.thumbnail.call(thisDropzone, image, "/vpit/web/uploads/documents/" + file.filename);
                addRemoveLink(file.id, image);
            });
        });

        this.on("success", function(file, response) {
            addRemoveLink(response.id, file);
        });
    }
 };



var addRemoveLink = function(id, file) {
    var url = $('#dropzoneId').attr('data-removeLink').slice(0, -1) + id;
    var removeIcon = Dropzone.createElement(
        "<a class='dz-remove' href="+url+" data-dz-remove>" +
            "<span class='glyphicon glyphicon-minus'></span>" +
        "</a>"
    );
    file.previewElement.appendChild(removeIcon);
}