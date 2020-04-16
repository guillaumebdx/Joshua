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
/*


    let password1 = document.getElementById('password');
    let password2 = document.getElementById('password-copy');

    password1.addEventListener('change', function() {
        pass1 = this;
        target1 = document.getElementById('inputGroup-password');
        pass2 = document.getElementById('password-copy');
        target2 = document.getElementById('inputGroup-password2');
        comparePasswords (pass1, target1, pass2, target2);
    });
    password2.addEventListener('change', function() {
        pass2 = this;
        target1 = document.getElementById('inputGroup-password');
        pass1 = document.getElementById('password');
        target2 = document.getElementById('inputGroup-password2');
        comparePasswords (pass1, target1, pass2, target2);
    });
*/
});
