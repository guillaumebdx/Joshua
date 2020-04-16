/**
 *
 * @type {HTMLElement}
 * Get the github avatar from user and showing it
 */

let pseudo = document.getElementById('github');
pseudo.addEventListener('change', function() {
    let pseudoUser = this.value;
    let avatar = document.getElementById('avatar');

    if (pseudoUser === "") {
        avatar.src = 'https://avatars.githubusercontent.com/github';
    } else {
        avatar.src = 'https://avatars.githubusercontent.com/' + pseudoUser;
    }
});

/**
 *
 * @param id
 * Show or hide password on click on the eye;
 * these functions is implemented in html with onmousedown and onmouseup events
 */

function showPassword(id) {
    let target = document.getElementById(id);
    target.type='text';
}
function hidePassword(id) {
    let target = document.getElementById(id);
    target.type='password';
}

/**
 * On loaded DOM content, prepare the fields of the form to check
 * refers to FormControl Class
 */

document.addEventListener("DOMContentLoaded", () => {

    let controlName = new formControl('lastname', 'name', 'inputGroup-lastname');
    let controlFirstName = new formControl('firstname', 'name', 'inputGroup-firstname');
    let mail = new formControl('email', 'email', 'inputGroup-email');
    let pseudo = new formControl('joshua-pseudo', 'pseudo', 'inputGroup-pseudo');
    let github = new formControl('github', 'pseudo', 'inputGroup-github','', '', true);
    let pass1 = new formControl('password', 'password', 'inputGroup-password', 'password-copy', 'inputGroup-password2');
    let pass2 = new formControl('password-copy', 'password', 'inputGroup-password2', 'password', 'inputGroup-password');
});
