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

 */

document.addEventListener("DOMContentLoaded", () => {

    /**
     * Adjust labels sizes to make them equals
     */
    $labels = document.getElementsByClassName('input-group-text');

    let maxSize = 0;
    for (let i=0 ; i<$labels.length ; i++) {
        if ($labels[i].offsetWidth > maxSize) {
            maxSize = $labels[i].offsetWidth;
        }
    }
    for (let i=0 ; i<$labels.length ; i++) {
        $labels[i].style.width = maxSize+'px';
    }
    /**
     *
     * @type {formControl}
     * On loaded DOM content, prepare the fields of the form to check
     * refers to FormControl Class
     */

    let controlName = new formControl('lastname', 'name', 'inputGroup-lastname');
    let controlFirstName = new formControl('firstname', 'name', 'inputGroup-firstname');
    let mail = new formControl('email', 'email', 'inputGroup-email');
    let pseudo = new formControl('joshuapseudo', 'pseudo', 'inputGroup-pseudo');
    let github = new formControl('github', 'pseudo', 'inputGroup-github','', '', true);
    let pass1 = new formControl('password', 'password', 'inputGroup-password', 'password-copy', 'inputGroup-password2');
    let pass2 = new formControl('password-copy', 'password', 'inputGroup-password2', 'password', 'inputGroup-password');
});
