(($) => {
    "use strict";

// FileStyle Button
    $('#image').filestyle({
        badge: true,
        input: false,
        text: 'Browse Images',
        btnClass: 'btn-primary',
        htmlIcon: '<span class="os os-images"></span> '
    });

    // https://hacks.mozilla.org/2011/01/how-to-develop-a-html5-image-uploader/ -- May be helpful to rework this script

    $.urlParam = function (sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    // User Profile Image Upload
    let fileList = [],
            fileInput = $('input[type="file"]')[0],
            resetBtn = $('form#user-image-upload-form button#reset'),
            submitBtn = $('form#user-image-upload-form button#submit'),
            $upstat = $('span#statusMessages'), // upload status
            $togWrap = $('div.card-body').find('span#toggleLeft'), // toggle switch 
            max_upload = 2000000, // decimal. binary would be 2091752
            previewErrors = [];

    resetBtn.toggle();
    submitBtn.prop('disabled', true);
    function resetSubmitBtn() {
        submitBtn.prop('disabled', true);
    }
    ;
    function resetFileList() {
        fileList = [];
        $('input[type="file"]').val('');
        $('span').find('span.badge.badge-light').remove();
        $('#manageProfileModal').load(location.href + " #manageProfileModal>*", ""); // refresh the manage profile section w/original source
        $('#profileImage').load(location.href + " #profileImage>*", "");
        $('#profileLinks').load(location.href + " #profileLinks>*", "");
        resetSubmitBtn();
        previewErrors = [];
    }
    ;
    function resetForm() {
        resetBtn.on('click', function () {
            resetBtn.hide();
            $('div#validationStatus').find('ul#uploadPreview').html('');
            resetFileList();
        });
    }
    ;
    function toggleLeft() {
        switch ($togWrap.hasClass('os-toggle-off')) {
            case true:
                // if toggle is set to OFF
                // check for status messages to still be collapsed - if so, trigger the click on upstat. Otherwise, it's already open.
                $togWrap.addClass('text-success os-toggle-on').removeClass('text-primary os-toggle-off');
                $upstat.trigger('click');
                break;
            default:
                $togWrap.addClass('text-primary os-toggle-off').removeClass('text-success os-toggle-on');
                $upstat.trigger('click');
                break;
        }
    }
    ;
    function setError(messg) {
        if (previewErrors.indexOf(messg) === -1) {
            previewErrors.push(messg);
        }
    }
    ;
    function validateSize(size) {
        switch (size > max_upload || size <= 0) {
            case true:
                setError(' File size: ' + formatBytes(size, 1) + ' exceeds max size of ' + formatBytes(max_upload, 1));
                return false;
                break;
            default:
                return true;
                break;
        }
    }
    ;
    function validateType(file) {
        switch (/\.(jpe?g|png|gif)$/i.test(file.name)) {
            case false:
                setError('&nbsp; ' + file.name + ' is not an image');
                return false;
                break;
            default:
                return true;
                break;
        }
    }
    ;
    function formatBytes(bytes, decimals) {
        if (bytes === 0)
            return '0 KB';
        var k = 1000,
                dm = decimals + 1 || 3,
                sizes = ['Bytes', 'KB', 'MB', 'GB'],
                i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
    ;
    function buildFileBtn(file, i) {
        var icon = 'images';
        switch (validateType(file)) {
            case true:
                var istyle = 'text-success',
                        icolor = 'text-success',
                        imsg = ' is a permitted file type';
                break;
            case false:
                icon = 'exclamation-circle',
                        istyle = 'text-danger',
                        icolor = 'text-danger',
                        imsg = ' is not a permitted file type';
                setError(' ' + file.type + imsg);
                break;
        }
        return $('div.card-body').find('span.prev').eq(i).append('<a data-toggle="tooltip" title="' + file.type + '" class=""><span class="os os-' + icon + ' ' + icolor + '"></span> <span class="' + istyle + '">' + file.type + imsg + '</span></a>');
    }
    ;
    function buildSizeBtn(file, i) {
        // removed btn btn-app from the class of return statement so it's not the button anymore. Icon + text
        switch (validateSize(file.size)) {
            case true:
                var sizeIcon = 'paperclip',
                        sizeColor = 'text-success',
                        imsg = ' File size: ' + formatBytes(file.size, 1) + ' is within max size of ' + formatBytes(max_upload, 1);
                break;
            case false:
                var sizeIcon = 'exclamation-circle',
                        sizeColor = 'text-danger',
                        imsg = ' File size: ' + formatBytes(file.size, 1) + ' exceeds max size of ' + formatBytes(max_upload, 1);
                break;
        }
        return '<a data-toggle="tooltip" title="' + formatBytes(file.size, 1) + '" class=""><span class="os os-' + sizeIcon + ' ' + sizeColor + '"></span> <span class="' + sizeColor + '">' + imsg + '</span></a>';
    }
    ;
    function buildFilenameBtn(file, i) {
        switch (validateType(file)) {
            case true:
                var nameIcon = 'picture',
                        nameColor = 'text-success';
                break;
            case false:
                var nameIcon = 'exclamation-circle',
                        nameColor = 'text-danger';
                break;
        }
        return '<a data-toggle="tooltip" title="' + file.name + '" class=""><span class="os os-' + nameIcon + ' ' + nameColor + '"></span> <span class="' + nameColor + '">' + file.name + '</span></a>';
    }
    ;
    function buildBtn(btnClass, btnIcon, btnTitle) {
        return '<a class="' + btnClass + '"> <span class="' + btnIcon + '"></span> ' + btnTitle + '</a>';
    }
    ;
    function previewFiles(prev, file, i) {
        if (!prev) {
            buildFileBtn(file, i);
        } else {
            function preview(file) {
                switch (validateType(file)) {
                    case true:
                        var reader = new FileReader(),
                                img = new Image();
                        switch (validateSize(file.size)) {
                            case true:
                                reader.onload = function (e) {
                                    img.title = file.type;
                                    img.src = reader.result;
                                    buildFileBtn(file, i);
                                    $('div#manageProfileModal').children('img').attr('src', img.src).attr('alt', img.title).addClass('');
                                };
                                break;
                            case false:
                                buildFileBtn(file, i);
                                break;
                        }
                        reader.readAsDataURL(file);
                        break;
                    default:
                        buildFileBtn(file, i);
                        break;
                }
            }
            ;
            preview(file);
        }
    }
    ;
    function handleFiles() {
        // changing to an unordered list here instead of table. getting rid of application buttons
        var container = $('div#validationStatus'),
                li = $('<li/>', {
                    class: 'list-group-item'
                }),
                ul_list;
        //create or select ul-list
        if (!container.find('ul').length) {
            ul_list = $('<ul/>', {
                id: 'uploadPreview',
                class: 'list-group'
            });
        } else {
            ul_list = $('div#validationStatus').find('ul#uploadPreview');
            // clear previous selection
            ul_list.html('');
        }
        //build unordered list items -- for this there is only one file so each may be unnecessary
        $.each(fileInput.files, function (i, file) {
            var fileRow = ul_list;
            li.clone().html('<span class="prev"></span>').appendTo(fileRow);
            li.clone().html(buildSizeBtn(file)).appendTo(fileRow);
            li.clone().html(buildFilenameBtn(file)).appendTo(fileRow);
            li.clone().html(buildBtn('', 'os os-upload', 'Progress: <span id="progress">--</span>')).appendTo(fileRow);
            li.clone().html(buildBtn('js-rm', 'os os-times text-danger', '<span class="text-danger"> Remove</span>')).appendTo(fileRow);
            fileRow.appendTo(ul_list);
            fileList.push(file);
            switch (!container.find('ul').length) {
                case true:
                    ul_list.appendTo(container);
                    break;
            }
            previewFiles(true, file, i); // pass true to show image preview in the manage profile section
        });
    }
    ;
    //update progressbar
    function handleProgress(e, i) {
        if (e.lengthComputable) {
            var complete = Math.round((e.loaded * 100) / e.total);
            $('span#progress').eq(i).text(complete + '%');
        }
    }
    ;
    function uploadSelected() {
        var allXHR = [],
                _messages = [],
                getId = $.urlParam('id');
        switch (submitBtn.prop('disabled')) {
            case true:
                // if submitBtn is disabled, don't do the upload!
                break;
            default:
                $.each(fileList, function (i, img) {
                    // All within the loop to allow for progress handling
                    var fd = new FormData(),
                            prog = $('div.progress-bar').eq(i);
                    fd.append('upload', 'AB_XHR');
                    fd.append('file-' + i, img);
                    // Send FormData to script for processing
                    allXHR.push($.ajax({
                        type: 'POST',
                        url: "image-upload.php?&id=" + getId + "",
                        data: fd,
                        dataType: 'JSON',
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            _messages.push(data);
                            resetSubmitBtn();
                        },
                        error: function (data) {
                            // _messages.push(data);
                            if (previewErrors.length === 0) {
                                var prev_errors = data.responseText;
                            } else {
                                prev_errors = previewErrors.toString();
                            }
                            
                            $('li#selImg').replaceWith(prev_errors);
                            //$('li#selImg').replaceWith('<li id="selImg" class="list-group-item label-danger"><span class="os os-exclamation-triangle">' + prev_errors + '</span></li>');
                            $('ul').eq(i).find('li').eq(4).find('a.js-rm span:first-child').removeClass('os-times').addClass('os-exclamation-triangle');
                            $('span.os.os-upload').eq(i).addClass('text-danger');
                            $('ul').eq(i).find('li').eq(4).find('a.js-rm span:first-child').removeClass('os-times').addClass('os-exclamation-triangle');
                            $('ul').eq(i).find('li').eq(4).find('a.js-rm span:nth-child(2)').text('Error');
                            $('.upstat').show();
                            resetFileList();
                        },
                        xhr: function () {
                            //add progress handler
                            var xhr = jQuery.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.onprogress = function (e) {
                                    handleProgress(e, i, prog);
                                };
                            }
                            return xhr;
                        }
                    }).done(function () {
                        $.each(_messages, function (idx, msg) {
                            switch (msg.error) {
                                case 'true':
                                    var color = 'danger',
                                            statusIcon = 'os os-exclamation-circle',
                                            statusTxt = 'Failed';
                                    break;
                                case 'false':
                                    var color = 'success',
                                            statusIcon = 'os os-hdd',
                                            statusTxt = 'File Saved';
                                    break;
                            }
                            $('li#selImg').replaceWith('<li class="list-group-item label-' + msg.class + '"><span class="os os-' + msg.icon + '"></span> ' + msg.msg + '</li>');
                            $('span.os.os-upload').eq(i).addClass('text-' + color);
                            $(('a.js-rm span:nth-child(2)')).eq(idx).removeClass('text-danger').removeClass('os-times').addClass(statusIcon).addClass('text-' + color).text(' ' + statusTxt);
                            _messages = [];
                        });
                        $('#profileImage').load(location.href + " #profileImage>*", "");
                        $('#profileLinks').load(location.href + " #profileLinks>*", "");
                        $('#manageProfileModal').load(location.href + " #manageProfileModal>*", "");
                        resetFileList();
                    }));
                });
        }
    }
    ;
    $('div#validationStatus').on('click', 'a.btn.btn-app', function (e) {
        e.preventDefault();
    });
    resetBtn.on('click', resetForm());

    submitBtn.on('click', function (e) {
        e.preventDefault();
        $(this).trigger('blur');
        uploadSelected();
    });
    $('div#validationStatus').on('click', 'a.js-rm', function () {
        var link = $(this),
                removed,
                filename = link.closest('li').children().eq(2).text();
        $.each(fileList, function (i, item) {
            if (item.name === filename) {
                removed = i;
            }
        });
        fileList.splice(removed, 1);
        switch (fileList.length) {
            case 0:
                resetBtn.click();
                break;
            default:
                $('label').find('span.badge.badge-light').text(fileList.length);
                break;
        }
        link.closest('li').remove();
    });
    $(fileInput).on('change', function () {
        fileList = [];
        resetBtn.show();
        submitBtn.prop('disabled', false);
        handleFiles();
    });

})(jQuery); // End of use strict