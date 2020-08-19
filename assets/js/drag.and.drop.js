// Some of this code is derived from a tutorial I found here
// https://www.smashingmagazine.com/2018/01/drag-drop-file-uploader-vanilla-js/
// Other bits are pieces that I wrote.

// Grab the URL Param
urlParam = function (sParam) {
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

// drag and drop
let dropArea = document.getElementById('drop-area');

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

;
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

;
['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

;
['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropArea.classList.add('highlight');
}

function unhighlight(e) {
    dropArea.classList.remove('highlight');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    let dt = e.dataTransfer;
    let files = dt.files;

    handleFiles(files);
}

function handleFiles(files) {
    files = [...files];
    initializeProgress(files.length);
    files.forEach(uploadFile);
    files.forEach(previewFile);
}

function uploadFile(file, i) {
    var getId = urlParam('id');
    var url = "image-upload.php?&id=" + getId + "";
    var xhr = new XMLHttpRequest();
    var formData = new FormData();
    formData.set('upload', true);
    xhr.open('POST', url, true);

    // Add following event listener
    xhr.upload.addEventListener('progress', function (e) {
        updateProgress(i, (e.loaded * 100.0 / e.total) || 100);
    });

    xhr.addEventListener('readystatechange', function (e) {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Done. Inform the user
        } else if (xhr.readyState === 4 && xhr.status !== 200) {
            // Error. Inform the user
        }
    });

    formData.append('file', file);
    xhr.send(formData);
}

let resetBtn = document.querySelector('button#reset');
let uploadBtn = document.querySelector('button#submit');
let gallery = document.querySelector('div#gallery');
let fileInput = document.querySelector('input#image');
let progressBar = document.getElementById('progress-bar');
let previewErrors = [];
let max_upload = 2000000; // decimal. binary would be 2091752

uploadBtn.disabled = true;
uploadBtn.classList.add('w3-hide');
resetBtn.classList.add('w3-hide');

resetForm = function () {
    uploadBtn.disabled = true;
    fileInput.value = null;
    gallery.innerHTML = '';
    resetBtn.classList.add('w3-hide');
    progressBar.value = 0;
};

function setError(message) {
    if (previewErrors.indexOf(message) === -1) {
        previewErrors.push(message);
    }
}
;

function validateType(file) {
    switch (/\.(jpe?g|png|gif)$/i.test(file.name)) {
        case false:
            setError('&nbsp; ' + file.name + ' is not a supported file type.');
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

function previewFile(file) {
    switch (validateType(file) && validateSize(file.size)) {
        case true:
            fileInput.value = null;
            gallery.innerHTML = '';
            uploadBtn.disabled = false;
            resetBtn.classList.remove('w3-hide');
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function () {
                let img = document.createElement('img');
                img.src = reader.result;
                var profileImageT = document.querySelector('#profileImageTag');
                var profileImageM = document.querySelector('#profileImageModalTag');
                profileImageT.src = img.src;
                profileImageM.src = img.src;
                //document.getElementById('gallery').appendChild(img);
            };
            break;
        default:
            var errorDiv = document.querySelector('#manageProfileModal');
            var prev_errors = previewErrors.toString();
            errorDiv.innerHTML += '<br><div id="errorDiv" class="w3-container w3-display-container w3-round w3-theme-l4 w3-border w3-theme-border w3-margin-bottom w3-margin-top">\n\
                                  <i class="fa fa-exclamation-circle w3-text-danger"></i>' + prev_errors + '</div>';
    }
}

let filesDone = 0;
let filesToDo = 0;

function initializeProgress(numFiles) {
    progressBar.value = 0;
    uploadProgress = [];

    for (let i = numFiles; i > 0; i--) {
        uploadProgress.push(0);
    }
}

function updateProgress(fileNumber, percent) {
    uploadProgress[fileNumber] = percent;
    let total = uploadProgress.reduce((tot, curr) => tot + curr, 0) / uploadProgress.length;
    progressBar.value = total;
}

let uploadProgress = [];
resetBtn.onclick = function (e) {
    resetForm();
    e.preventDefault();
};