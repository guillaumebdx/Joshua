class challengeRemover {
    constructor(removerId, lineId, destination, origin) {
        this.challengeLine  = document.getElementById(lineId)
        this.destination    = document.getElementById(destination)
        this.origin         = document.getElementById(origin)
        this.challengeId    = this.challengeLine.dataset.challenge
        this.challengeName  = this.challengeLine.dataset.name
        this.actionner      = document.getElementById(removerId)
        this.actionner.addEventListener('click', (e)=> {
            this.transfer()
            this.removeFromDatabase()
            this.remove()
            this.initAdders()
        })
    }

    remove() {
        this.challengeLine.remove()
    }

    transfer() {
        this.destination.innerHTML = this.destination.innerHTML + this.recreate();
    }

    removeFromDatabase() {
        //todo fetch
    }

    recreate() {
        const liHtml = '<li id="challenge-id' + this.challengeId+ '" data-challenge="' + this.challengeId+ '" '
            + 'class="list-group-item list-group-item-dark rounded-sm p-1 pl-2 mb-2 draggable">'
            + '<i class="fa fa-flag"></i>' + this.challengeName
            + '</li>';
        return liHtml;
    }

    initAdders() {
        //TODO Reinitialiser les adders
    }
}

const removers = document.getElementsByClassName('remover');
for (let i=0; i<removers.length; i++) {
    const id = removers[i].getAttribute('id')
    const lineId = removers[i].dataset.line
    const remover = new challengeRemover(id, lineId, 'destination', 'source');
}