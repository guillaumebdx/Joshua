
function verif_name(elem, maxLength) {
    let regex = /^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]+$/;
    if (elem.value === '' || !regex.test(elem.value) || elem.value.length>maxLength) {
        return false;
    } else {
        return true;
    }
}

function verif_text(elem, maxLength) {
    let regex = /^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]+$/;
    if (elem.value === '' || !regex.test(elem.value) || elem.value.length>maxLength) {
        return false;
    } else {
        return true;
    }
}

function verif_tel(elem) {
    regex=/^[0-9]{10}$/;

    if (elem.value === '' || !regex.test(elem.value)) {
        return false;
    } else {
        return true;
    }
}

function verif_email(elem) {
    let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (elem.value === '' || !regex.test(elem.value)) {
        return false;
    } else {
        return true;
    }
}

function verif_pseudo(elem) {
    let regex = /^([0-9a-zA-Z]+[^\s @\-_'"/])$/;
    if (elem.value === '' || !regex.test(elem.value)) {
        return false;
    } else {
        return true;
    }
}

function verif_url(elem) {
    verif=parseInt(0);
    let regex = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
    if (!regex.test(elem.value) || elem.value === '') {
        return false;
    } else {
        return true;
    }
}

function verif_password(elem) {
    console.log(elem.value);
    let regex = /^(?=.*[0-9])(?=.*[!@#$%^&*/{/}_])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*/{/}_]{8,15}$/;
    if (!regex.test(elem.value)) {
        return false;
    } else {
        return true;
    }
}

function comparePasswords (pass1, target1, pass2, target2) {

    if ( pass1.value === pass2.value && verif_password(pass1)) {
        target1.classList.remove('error');
        target1.classList.add('success');
        target2.classList.remove('error');
        target2.classList.add('success');
    } else {
        target1.classList.add('error');
        target1.classList.remove('success');
        target2.classList.add('error');
        target2.classList.remove('success');
    }

}

function sucessOrError(success, id) {
    target = document.getElementById('inputGroup-'+id);
    if (success === 1) {
        target.classList.remove('error');
        target.classList.add('success');
    } else {
        target.classList.remove('success');
        target.classList.add('error');
    }
}

function showPassword(id) {
    target = document.getElementById(id);
    target.type='text';
}

function hidePassword(id) {
    target = document.getElementById(id);
    target.type='password';
}
