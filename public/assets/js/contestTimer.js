class joshuaTimer {

    /**
     * The end Time of contest format like aaaa-mm-jj hh:mm:ss
     * @param string endTime
     * The id of the div where to render the timer
     * @param string target
     */
    constructor(endTime, target) {
        this.target=document.getElementById(target);
        this.intervalTime = 1000;
        this.end = new Date(endTime);
        this.contestTimer = null;
        this.show(this.formatDiffTime(this.diffTime()));
    }


    diffTime()  {
        let restTime =  this.end.getTime() - Date.now();
        return  restTime;
    }

    formatDiffTime(restTime) {
        let thours = Math.floor(restTime/1000/3600);
        let restDate=new Date(restTime);
        let hours = (thours<10) ? '0'+thours+ ' : ' : thours + ' : ';
        let minutes = (restDate.getMinutes()<10) ? '0'+restDate.getMinutes()+ ' : ' : restDate.getMinutes() + ' : ';
        let seconds = (restDate.getSeconds()<10) ? '0'+restDate.getSeconds() : restDate.getSeconds();
        return hours + minutes + seconds;
    }

    start() {
        this.contestTimer = setInterval(()=>{
            if (this.diffTime() > 0) {
                this.show(this.formatDiffTime(this.diffTime()));
            } else {
                this.stop();
            }
        }, this.intervalTime);
    }

    stop() {
        clearInterval(this.contestTimer);
        this.target.innerHTML = 'Terminé';
    }

    show(restTime) {
        this.target.innerHTML = restTime;
    }

}