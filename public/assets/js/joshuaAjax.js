/**
 * Instance of this class gives any  html object (id = actionerId) the possibility to get
 * the results of a PHP function (url) and return them as html
 * in a target (id=targetId) on the page.
 *
 * *************************************
 * Example of use
 * const myButton = new joshuaAjax('/ajax/test', 'check', 'datazone');
 * That's it :-)
 * **************************************
 * */

class joshuaAjax {

    constructor () {
        this.interval = null;
    }

    /**
     * @param string url
     * @param string actionnerId
     * @param string targetId
     * @param string event
     * @return void
     */

    joshuaAjaxEvent (url, actionnerId, targetId, event) {
        let target = document.getElementById(targetId);;
        document.getElementById(actionnerId).addEventListener(event, function() {
            fetch(url, {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
            }).then(function (response) {
                return response.text();
            }).then(function (html) {
                var parser = new DOMParser();
                var result = parser.parseFromString(html, "text/html");
                target.innerHTML = result.body.innerHTML;
            });
        });
    }

    /**
     * @param string url
     * @param string targetId
     * @param int timer in ms
     * @return void
     */

    joshuaAjaxTimer (url, targetId, time) {
        let target = document.getElementById(targetId);
        this.interval = setInterval(function() {
            fetch(url, {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
            }).then(function (response) {
                return response.text();
            }).then(function (html) {
                var parser = new DOMParser();
                var result = parser.parseFromString(html, "text/html");
                target.innerHTML = result.body.innerHTML;
            });
        }, time);
    }

    joshuaClearTimer () {
        clearInterval(this.interval);
    }

}

