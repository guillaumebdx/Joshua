
class formControl {
    /**
     *
     * @param elemId The id of the form element you want to check
     * @param type The type of control you want to apply
     */
    constructor(elemId, type, targetId) {
        let form = this;
        this.success_class = 'success';
        this.error_class   = 'error';
        this.elem          = document.getElementById(elemId);
        this.type          = type;
        this.target        = document.getElementById(targetId);
        if (this.elem.getAttribute('maxlength')) {
            this.maxLength = this.elem.getAttribute('maxlength');
        }

        this.elem.addEventListener('change', function(){

            if (form.type === 'name') {
                form.verif_name();
            }
        });
    }

    verif_name() {
        console.log('action');
        let regex = /^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]+$/;
        if (this.elem.value === '' || !regex.test(this.elem.value) || this.elem.value.length>this.maxLength) {
            this.sucessOrError(0);
        } else {
            this.sucessOrError(1);
        }
    }

    verif_text() {
        let regex = /^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ._\s-]+$/;
        if (this.elem.value === '' || !regex.test(this.elem.value) || this.elem.value.length>this.maxLength) {
            return false;
        } else {
            return true;
        }
    }

    verif_tel() {
        let regex=/^[0-9]{10}$/;
        if (this.elem.value === '' || !regex.test(this.elem.value)) {
            return false;
        } else {
            return true;
        }
    }

    verif_email() {
        let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (this.elem.value === '' || !regex.test(this.elem.value)) {
            return false;
        } else {
            return true;
        }
    }

    verif_pseudo() {
        let regex = /^([0-9a-zA-Z]+[^\s @\-_'"/])$/;
        if (this.elem.value === '' || !regex.test(this.elem.value)) {
            return false;
        } else {
            return true;
        }
    }

    verif_url() {
        let regex = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
        if (!regex.test(this.elem.value) || this.elem.value === '') {
            return false;
        } else {
            return true;
        }
    }

    verif_password() {
        let regex = /^(?=.*[0-9])(?=.*[!@#$%^&*/{/}_])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*/{/}_]{8,15}$/;
        if (!regex.test(this.elem.value)) {
            return false;
        } else {
            return true;
        }
    }

    comparePasswords (pass1, target1, pass2, target2) {
        if ( pass1.value === pass2.value && verif_password(pass1)) {
            target1.classList.remove(this.error_class);
            target1.classList.add(this.success_class);
            target2.classList.remove(this.error_class);
            target2.classList.add(this.success_class);
        } else {
            target1.classList.add(this.error_class);
            target1.classList.remove(this.success_class);
            target2.classList.add(this.error_class);
            target2.classList.remove(this.success_class);
        }

    }

    sucessOrError(success) {
        let target = this.target;
        if (success === 1) {
            target.classList.remove(this.error_class);
            target.classList.add(this.success_class);
        } else {
            target.classList.remove(this.success_class);
            target.classList.add(this.error_class);
        }
    }

    showPassword(id) {
        let target = document.getElementById(id);
        target.type='text';
    }

    hidePassword(id) {
        let target = document.getElementById(id);
        target.type='password';
    }
}
