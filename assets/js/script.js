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
// select all delete contact links on the page
const deleteContactLinks = document.querySelectorAll('[data-id]');

// Accordion
function toggleAccordion(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf('w3-show') === -1) {
        x.className += ' w3-show';
        x.previousElementSibling.className += ' w3-theme-d1';
    } else {
        x.className = x.className.replace('w3-show', '');
        x.previousElementSibling.className =
                x.previousElementSibling.className.replace(' w3-theme-d1', '');
    }
}

// Used to toggle the menu on smaller screens when clicking on the menu button
function openNav() {
    var x = document.getElementById('smallNav');
    if (x.className.indexOf('w3-show') === -1) {
        x.className += ' w3-show';
    } else {
        x.className = x.className.replace(' w3-show', '');
    }
}

function showDeleteModal() {
    // select the Delete contact form on the Delete Contact Modal
    var deleteContactForm = document.querySelector('#deleteContactForm');
    // select the cancel button on the Delete Modal
    var cancelDelete = document.querySelector('#cancelDeleteContact');
    // select the span element where the contacts name should be written to
    var nameSpan = document.querySelector('span#deleteContactName');
    // add an event listener to each delete contact link
    for (let i = 0; i < deleteContactLinks.length; i++) {
        deleteContactLinks[i].addEventListener('click', function (e) {
            // select contact's id via a data attribute
            var newId = this.getAttribute('data-id');
            // select the contact's name via a data attribute
            var newName = this.getAttribute('data-name');
            // create a text node containing the contact's name
            var name = document.createTextNode(newName);
            // update the form action to include the contact's id
            // @todo must select the current action and just append the newId to it
            deleteContactForm.setAttribute('action', 'delete.php?id=' + newId);

            // update the modal-header to include the contact's name
            nameSpan.appendChild(name);

            // launch the modal
            document.querySelector('#deleteContact').style.display = 'block';
            e.preventDefault();
        });
    }

    // if the cancel button is clicked, clear the previously added contact name 
    cancelDelete.onclick = function () {
        nameSpan.textContent = '';
        document.getElementById('deleteContact').style.display = 'none';
    };
}

// Select the Logout Modal
var logoutModal = document.querySelector('#logoutModal');

// Launch the Logout Modal - Main Menu
if (thisFile !== 'login.php') {
    var logoutLaunch = document.querySelector('#launchLogoutModal');
    logoutLaunch.onclick = function (e) {
        logoutModal.style.display = 'block';
        e.preventDefault();
    };
// Launch the Logout Modal - Small Screen Menu
    var logoutLaunchSmall = document.querySelector('#launchLogoutModalSmall');
    logoutLaunchSmall.onclick = function (e) {
        logoutModal.style.display = 'block';
        e.preventDefault();
    };
}

// Selects the cancel button on each modal.
var cancelLogout = document.querySelector('#cancelLogout');
var cancelDeleteContact = document.querySelector('#cancelDeleteContact');
var cancelDeleteProfileImage = document.querySelector('#cancelDeleteProfileImage');
// hide and close logout modal when requested
cancelLogout.onclick = function () {
    document.querySelector('#logoutModal').style.display = 'none';
};

if (cwd === 'admins') {
    
    /** Only excecute the following code while within the admins directory **/

    if (thisFile === 'edit.php' || thisFile === 'add.php') {

        /** Only execute this code while on edit.php OR add.php **/

        var changePasswordChk = document.querySelector('#pwd_change');
        var saveBtn = document.querySelector('button#saveBtn');
        var pwd_div = document.querySelector('div#password');
        saveBtn.disabled = true;
        // Select the password inputs
        var passwordInput = document.querySelector('input#password');
        var passwordConf = document.querySelector('input#conf_password');
        
        // do not execute this function on add.php -- only edit.php
        
        if (thisFile === 'edit.php') {
            /**
             * Hides or displays the password and confirm password boxes
             * 
             * @returns {undefined}
             */
            function changePasswordCheck() {
                var sel = changePasswordChk.checked;
                // if checked, show the pw and pw conf inputs
                pwd_div.style.display = sel ? 'block' : 'none';
                saveBtn.disabled = sel ? true : false;
                passwordInput.value = '';
                passwordConf.value = '';
            }
            changePasswordChk.addEventListener('change', changePasswordCheck, false);
            
        }

        // apply event listeners to passwordInput and passwordConf inputs
        passwordInput.addEventListener('input', passwordRequired, false);
        passwordInput.addEventListener('change', passwordRequired, false);
        passwordInput.addEventListener('keydown', passwordRequired, false);
        passwordInput.addEventListener('keyup', passwordRequired, false);
        passwordInput.addEventListener('paste', passwordRequired, false);

        passwordConf.addEventListener('input', passwordRequired, false);
        passwordConf.addEventListener('change', passwordRequired, false);
        passwordConf.addEventListener('keydown', passwordRequired, false);
        passwordConf.addEventListener('keyup', passwordRequired, false);
        passwordConf.addEventListener('paste', passwordRequired, false);

        // the class to apply to the list item once criteria is satisfied
        var acceptedTheme = 'w3-theme-d1';

        /**
         * Just a seemingly quicker / cleaner way to select a series of elements
         * 
         * @param {string} sel
         * @returns {Element}
         */
        function quickSelect(sel) {
            var selector = document.querySelector(sel);
            return selector;
        }

        /**
         * Adds the acceptedTheme class to the requested list item
         * 
         * @param {string} sel
         * @returns {undefined}
         */
        function addAcceptedClass(sel) {
            var selector = document.querySelector(sel);
            selector.classList.add(acceptedTheme);
        }

        /**
         * Removes the acceptedTheme class from the requested list item
         * 
         * @param {string} sel
         * @returns {undefined}
         */
        function removeAcceptedClass(sel) {
            var selector = document.querySelector(sel);
            selector.classList.remove(acceptedTheme);
            // saveBtn should always be disabled if something isn't accepted
            saveBtn.disabled = true;
        }

        /**
         * Tests for the passwordInput password input containing 0 characters.
         * 
         * If blank, the acceptedTheme class is removed from the respective list item
         * Otherwise, the acceptedTheme class is added to the respective list item.
         * 
         * @returns {undefined}
         */
        function passwordIsBlank() {
            switch (passwordInput.value.length === 0) {
                case true:
                    removeAcceptedClass('#pw_blank');
                    break;
                case false:
                    addAcceptedClass('#pw_blank');
                    break;
            }
        }

        /**
         * Tests that the passwordInput contains at least 5 characters.
         * 
         * If less than, the acceptedTheme class is removed from the respective list item.
         * Otherwise, the respective class is added to the list item.
         * 
         * @returns {undefined}
         */
        function passwordMeetsLength() {
            switch (passwordInput.value.length < 5) {
                case true:
                    removeAcceptedClass('#pw_length');
                    break;
                case false:
                    addAcceptedClass('#pw_length');
                    break;
            }
        }

        /**
         * Tests the passwordInput value to ensure it contains at least one number.
         * 
         * @returns {undefined}
         */
        function passwordHasNumber() {
            var number = /\d/;
            switch (number.test(passwordInput.value)) {
                case true:
                    addAcceptedClass('#pw_number');
                    break;
                case false:
                    removeAcceptedClass('#pw_number');
                    break;
            }
        }

        /**
         * Tests that the passwordInput value contains at least one symbol
         * 
         * @returns {undefined}
         */
        function passwordHasSymbol() {
            var symbol = /\W|_/;
            switch (symbol.test(passwordInput.value)) {
                case true:
                    addAcceptedClass('#pw_symbol');
                    break;
                case false:
                    removeAcceptedClass('#pw_symbol');
                    break;
            }
        }

        /**
         * Tests that the passwordInput value contains at least one uppercase character.
         * 
         * @returns {undefined}
         */
        function passwordHasUppercase() {
            var upper = /[A-Z]/;
            switch (upper.test(passwordInput.value)) {
                case true:
                    addAcceptedClass('#pw_upper');
                    break;
                case false:
                    removeAcceptedClass('#pw_upper');
                    break;
            }
        }

        /**
         * Tests that the passwordInput value contains at least one lowercase character.
         * 
         * @returns {undefined}
         */
        function passwordHasLowercase() {
            var lower = /[a-z]/;
            switch (lower.test(passwordInput.value)) {
                case true:
                    addAcceptedClass('#pw_lower');
                    break;
                case false:
                    removeAcceptedClass('#pw_lower');
                    break;
            }
        }

        /**
         * Tests that passwordInput and passwordConf are not blank and that they are identical.
         * 
         * If they are not blank and match, the acceptedTheme class is added to the respective
         * list item to give the user a visual to know that this criteria has been met.
         * @returns {undefined}
         */
        function passwordsMatch() {
            switch (passwordInput.value === passwordConf.value && passwordInput.value !== '' && passwordConf.value !== '') {
                case true:
                    addAcceptedClass('#pw_match');
                    break;
                case false:
                    removeAcceptedClass('#pw_match');
                    break;
            }
        }

        /**
         * Tests each individual list item for the acceptedTheme class. 
         * 
         * In the event that it exists in each list item, it is assumed that all 
         * criteria has been met and the saveBtn (submit button) is enabled.
         * 
         * @returns {undefined}
         */
        function enableSaveButton() {
            if (
                    quickSelect('#pw_blank').classList.contains(acceptedTheme) &&
                    quickSelect('#pw_length').classList.contains(acceptedTheme) &&
                    quickSelect('#pw_lower').classList.contains(acceptedTheme) &&
                    quickSelect('#pw_upper').classList.contains(acceptedTheme) &&
                    quickSelect('#pw_symbol').classList.contains(acceptedTheme) &&
                    quickSelect('#pw_number').classList.contains(acceptedTheme) &&
                    quickSelect('#pw_match').classList.contains(acceptedTheme)
                    )
            {
                saveBtn.disabled = false;
            }
        }

        /**
         * Runs all tests on the password and password confirm input boxes
         * 
         * @returns {undefined}
         */
        function passwordRequired() {
            passwordIsBlank();
            passwordMeetsLength();
            passwordHasNumber();
            passwordHasSymbol();
            passwordHasUppercase();
            passwordHasLowercase();
            passwordsMatch();
            enableSaveButton();

        }
    }
}

if (cwd === 'contacts' || cwd === 'admins') {
    switch (thisFile) {
        case 'index.php':
            /**
             * Only execute the following code when on index.php for a module
             */

            showDeleteModal();

            break;
        case 'edit.php':

            /**
             * Only execute the following code when on edit.php for either contacts or admins
             */

            cancelDeleteProfileImage.onclick = function () {
                document.querySelector('#deleteProfileImage').style.display = 'none';
            };
            // Select the "Delete Image" link
            var deleteProfileImageLink = document.querySelector('#triggerDeleteProfileImage');
            // Select the "Update Image" Link
            var addProfileImageLink = document.querySelector('#triggerAddProfileImage');
            // select the cancel button on the Upload Profile Image Modal
            var cancelAddProfileImage = document.querySelector('#cancelAddProfileImage');
            // Show the modal when the Delete Image link is clicked
            if (deleteProfileImageLink !== null) {
                deleteProfileImageLink.onclick = function (e) {
                    document.querySelector('#deleteProfileImage').style.display = 'block';
                    e.preventDefault();
                };
            }
            if (addProfileImageLink !== null) {
                addProfileImageLink.onclick = function (e) {
                    document.querySelector('#addProfileImage').style.display = 'block';
                    e.preventDefault();
                };
            }
            cancelAddProfileImage.onclick = function (e) {
                document.querySelector('#addProfileImage').style.display = 'none';
                e.preventDefault();
            };
            break;
        case 'view.php':
            showDeleteModal();

            break;
    }
}