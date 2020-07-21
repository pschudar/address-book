function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}
;

let location_arr = window.location.pathname.split('/'),
        // current working directory
        cwd = location_arr[location_arr.length - 2],
        // name of the current file
        thisFile = location_arr[location_arr.length - 1];
if (cwd === 'contacts' || cwd === 'admins') {
    switch (thisFile) {
        case 'index.php':
            /**
             * Only execute the following code when on index.php for a module
             */

            // select the Delete contact form on the Delete Contact Modal
            var deleteContactForm = document.querySelector('#deleteContactForm');
            // select the cancel button on the Delete Modal
            var cancelDelete = document.querySelector('button#cancelDeleteContact');
            // select the span element where the contacts name should be written to
            var nameSpan = document.querySelector('span#deleteContactName');
            // select all delete contact links on the page
            const deleteContactLinks = document.querySelectorAll('[data-id]');

            // add an event listener to each delete contact link
            for (let i = 0; i < deleteContactLinks.length; i++) {
                deleteContactLinks[i].addEventListener('click', function () {
                    // select contact's id via a data attribute
                    var newId = this.getAttribute('data-id');
                    // select the contact's name via a data attribute
                    var newName = this.getAttribute('data-name');
                    // create a text node containing the contact's name
                    var name = document.createTextNode(newName);
                    // update the form action to include the contact's id
                    deleteContactForm.setAttribute('action', '/address_book/staff/'+cwd+'/delete.php?id=' + newId);

                    // update the modal-header to include the contact's name
                    nameSpan.appendChild(name);
                });
            }

            // if the cancel button is clicked, clear the previously added contact name 
            cancelDelete.onclick = function () {
                nameSpan.textContent = '';
            };
            break;
    }
}