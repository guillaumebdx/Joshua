document.addEventListener("DOMContentLoaded", function() {

        $('.toast').toast({
                autohide: true,
                delay : 3000,
        });

        const ajaxSender = new joshuaAjax();

        const switchesAdmin = document.getElementsByClassName('is-admin-manager');
        for (let i = 0; i < switchesAdmin.length; i++) {
                let switchId = switchesAdmin[i].id;
                let user = switchesAdmin[i].dataset.user;
                ajaxSender.joshuaAjaxSwitchAction('/admin/setuseradmin', switchId, 'body-toast-'+user, 'click');
        }

        const switchesActif = document.getElementsByClassName('is-actif-manager');
        for (let i = 0; i < switchesActif.length; i++) {
                let switchId = switchesActif[i].id;
                let user = switchesActif[i].dataset.user;
                ajaxSender.joshuaAjaxSwitchAction('/admin/setuseractif', switchId, 'body-toast-'+user, 'click');
        }

});