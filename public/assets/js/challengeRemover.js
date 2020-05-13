class challengeRemover {
    constructor(removerId, lineId, destination, origin) {
        this.challengeLine  = document.getElementById(lineId)
        this.destination    = document.getElementById(destination)
        this.origin         = document.getElementById(origin)
        this.challengeId    = this.challengeLine.dataset.challenge
        this.challengeName  = this.challengeLine.dataset.name
        this.dificulty      = this.challengeLine.dataset.difficulty
        this.actionner      = document.getElementById(removerId)
        this.actionner.addEventListener('click', (e)=> {
            this.transfer()
            this.removeFromDatabase()
            this.remove()
            this.initAdders()
            let fieldForOrder = document.getElementById('order-of-challenges')
            fieldForOrder.value = this.challengesOrder()

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
        const liHtml = '<li id="toadd-' + this.challengeId + '"'
            + 'class="list-group-item list-group-item-dark rounded-sm p-1 pl-2 mb-2" '
            + ' data-difficulty="' + this.dificulty + '" data-name="' + this.challengeName + '" data-challenge="' + this.challengeId + '"> '
            + '<i class="fa fa-flag mgr-7"></i>' + this.challengeName + ' <img src="/assets/images/d' + this.dificulty + '.svg">'
            + '<button id="adderBtn' + this.challengeId + '" type="button" data-target="toadd-' + this.challengeId + '" class="btn btn-dark text-white rounded-sm p-0 pl-1 pr-1 challenge-adder float-right">Add</button>'
            + '</li>';
        console.log(liHtml)
        return liHtml;
    }

    initAdders() {
        const adders = document.getElementsByClassName('challenge-adder')
        for (let i=0; i<adders.length; i++) {
            const id = adders[i].getAttribute('id')
            const lineId = adders[i].dataset.target
            const adder = new challengeAdder(id, lineId, 'list-ordered-flags', 'list-toadd-flags');
        }
        this.addNumbers()

        let challenge = new DragAndDrop();
        challenge.init({
            instance           : 'challenge',
            draggableClassName : 'draggable',
            origin             : document.getElementById('list-ordered-flags'),
            dropZone           : document.getElementById('list-ordered-flags'),
        });
    }

    challengesOrder() {
        let newContainer = document.getElementById('list-ordered-flags')
        let order = [];
        let orderedElements = newContainer.getElementsByClassName('list-group-item')
        for (let i=0; i<orderedElements.length; i++) {
            order.push(orderedElements[i].dataset.challenge)
        }
        return JSON.stringify(order);
    }

    addNumbers() {
        let newContainer = document.getElementById('list-ordered-flags')
        let orderedElements = newContainer.getElementsByClassName('list-group-item')
        for (let i=0; i<orderedElements.length; i++) {
                let num = i + 1;
                let htmlForNumber = '<span class="list-order-number">' + num + '</span> ';
                let html = orderedElements[i].innerHTML;
                let htmlToKeep = html.split('<i ');
                orderedElements[i].innerHTML = htmlForNumber + '<i ' + htmlToKeep[1];

        }
        const removers = document.getElementsByClassName('remover');
        for (let i=0; i<removers.length; i++) {
            const id = removers[i].getAttribute('id')
            const lineId = removers[i].dataset.target
            const remover = new challengeRemover(id, lineId, 'list-to-add-flags', 'list-ordered-flags');
        }
    }
}

const removers = document.getElementsByClassName('remover');
for (let i=0; i<removers.length; i++) {
    const id = removers[i].getAttribute('id')
    const lineId = removers[i].dataset.target
    const remover = new challengeRemover(id, lineId, 'list-to-add-flags', 'list-ordered-flags');
}