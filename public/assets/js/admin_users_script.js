document.addEventListener("DOMContentLoaded", function() {

        $('.toast').toast({
                autohide: true,
                delay : 10000,
        });

        const switches = document.getElementsByClassName('is-admin-manager');
        const ajaxSender = new joshuaAjax();
        for (let i = 0; i < switches.length; i++) {
                let switchId = switches[i].id;
                ajaxSender.joshuaAjaxSwitchAction('/admin/setuseradmin', switchId, 'body-toast', 'click');
        }

});