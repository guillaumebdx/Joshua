/**
 *
 * @type {HTMLElement}
 * Get the github avatar from user and showing it
 */
if (document.getElementById('github')) {
    let pseudo = document.getElementById('github');
    pseudo.addEventListener('change', function () {
        let pseudoUser = this.value;
        let avatar = document.getElementById('avatar');

        if (pseudoUser === "") {
            avatar.src = 'https://avatars.githubusercontent.com/github';
        } else {
            avatar.src = 'https://avatars.githubusercontent.com/' + pseudoUser;
        }
    });
}

/**
 *
 */

let showHide     = document.getElementsByClassName('show-hide-pass');
//onmousedown="showPassword('passwordcopy')" onmouseup="hidePassword('passwordcopy')"
for (let i=0; i<showHide.length; i++) {
    let this_target = showHide[i].dataset.target;
    showHide[i].addEventListener('mousedown', ()=> {
        showPassword(this_target);
    });
    showHide[i].addEventListener('mouseup', ()=> {
        hidePassword(this_target);
    });

}


/**

 */

document.addEventListener("DOMContentLoaded", () => {

    /**
     * Adjust labels sizes to make them equals
     */
    if (document.getElementsByClassName('input-group-text')) {
        $labels = document.getElementsByClassName('input-group-text');

        let maxSize = 0;
        for (let i = 0; i < $labels.length; i++) {
            if ($labels[i].offsetWidth > maxSize) {
                maxSize = $labels[i].offsetWidth;
            }
        }
        for (let i = 0; i < $labels.length; i++) {
            $labels[i].style.width = maxSize + 'px';
        }
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
    let pass1 = new formControl('password', 'password', 'inputGroup-password', 'passwordcopy', 'inputGroup-password2');
    let pass2 = new formControl('passwordcopy', 'password-copy', 'inputGroup-password2', 'password', 'inputGroup-password');
});
