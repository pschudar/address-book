var configTrigger = document.querySelector('#config-trigger');
configTrigger.addEventListener('click', toggleMenu, false);
var configPanel = document.querySelector('#config-panel');
var configClose = document.querySelector('#config-close');
const colorOptions = document.querySelectorAll('[data-style]');

function showMenu() {
    configPanel.classList.add('show');
}

function hideMenu() {
    configPanel.classList.remove('show');
}

function toggleMenu(e) {
    e.preventDefault();
    if (configPanel.classList.contains('show')) {
        hideMenu();
    } else {
        showMenu();
    }
}

// setting an event listener on each option within colorOptions object
for (let i = 0; i < colorOptions.length; i++) {
    colorOptions[i].addEventListener('click', function (e) {
        // select the clicked style
        var stylesheet = this.getAttribute('data-style');
        // select the link to the stylesheet
        var styleLink = document.querySelector('#theme-style');
        // set the style sheet to the clicked style
        styleLink.setAttribute('href', stylesheet);
        // set the cache in localStorage to the clicked stylesheet
        localStorage.setItem('stylePreference', stylesheet);
        // select all list items & remove the active class
        var listItemActive = document.getElementsByClassName('list-inline-item active');
        while(listItemActive[0]) {
            listItemActive[0].classList.remove('active');
        }
        // select the list item that was clicked
        var clickedListItem = this.closest('li');
        // add the active class
        clickedListItem.classList.add('active');

        e.preventDefault();
    });
}

// Dark mode

var body = document.querySelector('#site-mode'); // selects 'body' tag
var modeToggle = document.querySelector('#darkmode');
var siteModeDescription = document.querySelector('#site-mode-description');

modeToggle.addEventListener('change', function (e) {
    if (this.checked) {
        addDarkMode();
    } else {
        addLightMode();
    }
});

/**
 * Clears out the #site-mode-description
 * 
 * @returns {void}
 */
function clearModeTitle() {
    siteModeDescription.textContent = '';
}

/**
 * Creates a new text node and appends it to #site-mode-description
 * 
 * mTitle represents the 'mode title'. Use this parameter to define the title, 
 * which appears just above the dark mode / light mode switch on the theme-switcher panel
 * 
 * @param {string} mTitle
 * @returns {void}
 */
function addModeTitle(mTitle) {
    var newTitle = document.createTextNode(mTitle);
    siteModeDescription.appendChild(newTitle);
}

/**
 * Removes the light mode class & replaces it w/the dark mode class in the body tag
 * 
 * @returns {void}
 */
function addDarkMode() {
    body.classList.remove('w3-theme-light');
    body.classList.add('w3-theme-dark');
    modeToggle.checked = true;
    localStorage.setItem('styleMode', 'darkmode');
    clearModeTitle();
    addModeTitle('Dark Mode');
}

/**
 * Removes the dark mode class and replaces it w/the light mode class in the body tag
 * 
 * @returns {void}
 */
function addLightMode() {
    body.classList.remove('w3-theme-dark');
    body.classList.add('w3-theme-light');
    modeToggle.checked = false;
    localStorage.setItem('styleMode', 'lightmode');
    clearModeTitle();
    addModeTitle('Light Mode');
}

/**
 * Checks that localStorage styleMode is set, then adds the correct site-mode
 * 
 * This could be darkmode or lightmode. Defaults to light mode.
 * 
 * @returns {void}
 */
function checkStyleMode() {
    if (localStorage.getItem('styleMode') !== null) {
        var styleMode = localStorage.getItem('styleMode');
    } else {
        styleMode = 'lightmode';
    }
    if (styleMode === 'darkmode') {
        addDarkMode();
    } else if (styleMode === 'lightmode') {
        addLightMode();
    }
}

/**
 * Allows a user to click the close icon in the upper right as well as the 
 * config-trigger tab to close the theme-switcher panel.
 * 
 * @param {event} e
 * @returns {void}
 */
configClose.onclick = function(e) {
    configTrigger.click();
    e.preventDefault();
};

checkStyleMode();