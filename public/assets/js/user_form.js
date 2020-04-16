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


document.addEventListener("DOMContentLoaded", () => {

    let controlName = new formControl('lastname', 'name', 'inputGroup-lastname');
    let controlFirstName = new formControl('firstname', 'name', 'inputGroup-firstname');
    let mail = new formControl('email', 'email', 'inputGroup-email');
    let pseudo = new formControl('joshua-pseudo', 'pseudo', 'inputGroup-pseudo');
    let github = new formControl('github', 'pseudo', 'inputGroup-github');
    let pass1 = new formControl('password', 'password', 'inputGroup-password', 'password-copy', 'inputGroup-password2');
    let pass2 = new formControl('password-copy', 'password', 'inputGroup-password2', 'password', 'inputGroup-password');

});
