document.addEventListener("DOMContentLoaded", function() {

        $('.toast').toast({
                autohide: true,
                delay : 3000,
        });

        const ajaxSender = new joshuaAjax();

        const switchesAdmin = document.getElementsByClassName('is-admin-manager');
        for (let i = 0; i < switchesAdmin.length; i++) {
                let switchId = switchesAdmin[i].id;
                let user     = switchesAdmin[i].dataset.user;
                ajaxSender.joshuaAjaxSwitchAction('/admin/setuseradmin', switchId, 'body-toast-'+user, 'click');
        }

        const switchesActive = document.getElementsByClassName('is-actif-manager');
        for (let i = 0; i < switchesActive.length; i++) {
                let switchId = switchesActive[i].id;
                let user     = switchesActive[i].dataset.user;
                ajaxSender.joshuaAjaxSwitchAction('/admin/setuseractive', switchId, 'body-toast-'+user, 'click');
        }
});
