class StoryTeller {

    constructor(refresh, contestId, endDate) {
        this.container = document.getElementById('console' + contestId);
        this.intervalTime = refresh;
        this.urlRanking = '/contest/getRankingInContest/' + contestId;
        this.url = '/contest/getHistoryOfContest/' + contestId;
        this.storyZone = document.getElementById('story');
        this.rankingZone = document.getElementById('ranking');
        this.timerId = 'timer' + contestId;
        this.closer = document.getElementById('console-closer-' + contestId);
        this.closer.addEventListener('click', ()=> {
            this.hide();
        });
        this.endDate = endDate;
    }

    show() {
        this.initContent(this.url, this.storyZone);
        this.startConsole(this.url, this.storyZone, this.intervalTime);
        this.timer(this.endDate, this.timerId);
        this.initContent(this.urlRanking, this.rankingZone);
        this.startRanker(this.urlRanking, this.rankingZone, this.intervalTime);
        this.container.classList.remove('hide');
    }

    hide() {
        this.stop();
        this.container.classList.add('hide');
    }

    fetcherHtml(url, target) {
        fetch(url, {
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
        }).then(function (response) {
            return response.text();
        }).then(function (html) {
            let parser = new DOMParser();
            let result = parser.parseFromString(html, "text/html");
            target.innerHTML = result.body.innerHTML;
        });
    }

    initContent(url, target) {
         this.fetcherHtml(url, target)
    }

    startConsole(url, target, time) {
        this.consoleUpdater = setInterval(() => {
            this.fetcherHtml(url, target)
        }, time);

    }
    startRanker(url, target, time) {
        this.rankerUpdater = setInterval(() => {
            this.fetcherHtml(url, target)
        }, time);
    }

    stop() {
        clearInterval(this.consoleUpdater);
        clearInterval(this.rankerUpdater);
    }

    timer(endDate, timerId) {
        let timer = new contestTimer(endDate, timerId,
            function() {
                stop();
            });
    }
}
