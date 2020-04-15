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

    let lastname = document.getElementById('lastname');
    lastname.addEventListener('change', function () {
        if (verif_name(this, 45)) {
            sucessOrError(1, 'lastname');
        } else {
            sucessOrError(0, 'lastname');
        }
    });

    let firstname = document.getElementById('firstname');
    firstname.addEventListener('change', function () {
        if (verif_name(this, 45)) {
            sucessOrError(1, 'firstname');
        } else {
            sucessOrError(0, 'firstname');
        }
    });

    let email = document.getElementById('email');
    // Vérifier dans la base que le mail n'existe pas

    email.addEventListener('change', function () {
        if (verif_email(this)) {
            sucessOrError(1, 'email');
        } else {
            sucessOrError(0, 'email');
        }
    });

    let pseudo = document.getElementById('joshua-pseudo');
    // Vérifier dans la base que le pseudo n'existe pas

    pseudo.addEventListener('change', function () {
        if (verif_pseudo(this)) {
            sucessOrError(1, 'pseudo');
        } else {
            sucessOrError(0, 'pseudo');
        }
    });

    let pseudog = document.getElementById('github');
    // Vérifier dans la base que le pseudo n'existe pas

    pseudog.addEventListener('change', function () {
        if (verif_pseudo(this)) {
            sucessOrError(1, 'github');
        } else {
            sucessOrError(0, 'github');
        }
    });

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

});
