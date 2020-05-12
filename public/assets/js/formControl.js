
class formControl {
    /**
     *
     * @param elemId The id of the form element you want to check
     * @param type The type of control you want to apply
     * The two parameters above are optionnal but required for passwords checking
     * @param [compareId] : the second field for password to check equality
     * @param [targetCompareId] : the second field target to return error class
     */

    constructor(elemId, type, targetId, compareId='', targetCompareId='', allowEmpty = false) {
        let form = this;
        this.success_class      = 'success';
        this.error_class        = 'error';
        this.elem               = document.getElementById(elemId);
        this.type               = type;
        this.target             = document.getElementById(targetId);
        this.allowEmpty         = allowEmpty;
        if (this.elem.getAttribute('maxlength')) {
            this.maxLength = this.elem.getAttribute('maxlength');
        }
        if (this.type === 'password-copy') {
            this.passCopy   = document.getElementById(compareId);
            this.targetCopy = document.getElementById(targetCompareId);
        }

        this.elem.addEventListener('change', function(){
            if (form.type === 'name') {
                form.verif_name();
            }
            if (form.type === 'email') {
                form.verif_email();
            }
            if (form.type === 'text') {
                form.verif_text();
            }
            if (form.type === 'tel') {
                form.verif_tel();
            }
            if (form.type === 'pseudo') {
                form.verif_pseudo();
            }
            if (form.type === 'url') {
                form.verif_url();
            }
            if (form.type === 'password') {
                form.verif_password_simple();
            }
            if (form.type === 'password-copy') {
                form.comparePasswords();
            }
        });
    }


    verif_name() {
        console.log('action');
        let regex = /^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ'_\s-]+$/;
        if (this.elem.value === '' || !regex.test(this.elem.value) || this.elem.value.length>this.maxLength) {
            this.sucessOrError(false);
        } else {
            this.sucessOrError(true);
        }
    }

    verif_text() {
        let regex = /^[a-zA-Z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ.'_\s-]+$/;
        if (this.elem.value === '' || !regex.test(this.elem.value) || this.elem.value.length>this.maxLength) {
            this.sucessOrError(false);
        } else {
            this.sucessOrError(true);
        }
    }

    verif_tel() {
        let regex=/^[0-9]{10}$/;
        if (this.elem.value === '' || !regex.test(this.elem.value)) {
            this.sucessOrError(false);
        } else {
            this.sucessOrError(true);
        }
    }

    verif_email() {
        let regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (this.elem.value === '' || !regex.test(this.elem.value)) {
            this.sucessOrError(false);
        } else {
            this.sucessOrError(true);
        }
    }

    verif_pseudo() {
        let regex = /^([a-zA-Z0-9-_]{2,36})$/;
        if (this.allowEmpty === true && this.elem.value === '') {
            this.sucessOrError(true);
            return;
        } else {
            if (this.elem.value === '' || !regex.test(this.elem.value)) {
                this.sucessOrError(false);
            } else {
                this.sucessOrError(1);
            }
        }
    }

    verif_url() {
        let regex = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/;
        if (!regex.test(this.elem.value) || this.elem.value === '') {
            this.sucessOrError(false);
        } else {
            this.sucessOrError(true);
        }
    }

    verif_password() {
        let regex = /^(?=.*[0-9])(?=.*[!@#$%^&*/{/}_])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*/{/}_]{8,15}$/;
        if (regex.test(this.elem.value)) {
            return true;
        } else {
            return false;
        }
    }

    verif_password_simple() {
        let regex = /^(?=.*[0-9])(?=.*[!@#$%^&*/{/}_])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*/{/}_]{8,15}$/;
        if (regex.test(this.elem.value)) {
            this.sucessOrError(true);
        } else {
            this.sucessOrError(false);
        }
    }

    comparePasswords () {

        if ( this.elem.value === this.passCopy.value && this.verif_password(this.elem)) {
            this.target.classList.remove(this.error_class);
            this.target.classList.add(this.success_class);
            this.targetCopy.classList.remove(this.error_class);
            this.targetCopy.classList.add(this.success_class);
        } else {
            this.target.classList.add(this.error_class);
            this.target.classList.remove(this.success_class);
            this.targetCopy.classList.add(this.error_class);
            this.targetCopy.classList.remove(this.success_class);
        }
    }

    /**
     *
     * @param success int equals 1 or 0
     * @return void
     * This function add success class or error class to the target element
     */
    sucessOrError(success) {
        if (success) {
            this.target.classList.remove(this.error_class);
            this.target.classList.add(this.success_class);
        } else {
            this.target.classList.remove(this.success_class);
            this.target.classList.add(this.error_class);
        }
    }
}
