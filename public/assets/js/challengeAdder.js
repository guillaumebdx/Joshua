class challengeAdder {
    constructor(adderId, lineId, destination, origin) {
        this.challengeLine  = document.getElementById(lineId)
        this.destination    = document.getElementById(destination)
        this.origin         = document.getElementById(origin)
        this.challengeId    = this.challengeLine.dataset.challenge
        this.challengeName  = this.challengeLine.dataset.name
        this.dificulty      = this.challengeLine.dataset.difficulty
        this.actionner      = document.getElementById(adderId)
        this.actionner.addEventListener('click', (e)=> {
            this.numberOfLines  = this.destination.getElementsByClassName('list-group-item').length
            this.numberOfLines = this.numberOfLines + 1
            this.transfer()
            this.remove()
            this.initRemovers()
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


    recreate() {
        const liHtml = '<li id="challenge-id' + this.challengeId+ '" data-difficulty="' + this.dificulty + '" data-challenge="' + this.challengeId+ '" data-name="' + this.challengeName + '" '
            + 'class="list-group-item list-group-item-dark rounded-sm p-1 pl-2 mb-2 draggable">'
            + '<span class="list-order-number mgr-7">' + this.numberOfLines + '</span>'
            + '<i class="fa fa-flag mgr-7"></i>' + this.challengeName + ' <img src="/assets/images/d' + this.dificulty + '.svg"> '
            + '<a href="#" class="remover float-right" id="remover' + this.challengeId+ '" data-target="challenge-id' + this.challengeId+ '"><img src="/assets/images/close.svg"></a>'
            + '</li>';
        return liHtml;
    }

    initRemovers() {
        const removers = document.getElementsByClassName('remover');
        for (let i=0; i<removers.length; i++) {
            const id = removers[i].getAttribute('id')
            const lineId = removers[i].dataset.target
            const remover = new challengeRemover(id, lineId, 'list-to-add-flags', 'list-ordered-flags');
        }

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
}

const adders = document.getElementsByClassName('challenge-adder')
for (let i=0; i<adders.length; i++) {
    const id = adders[i].getAttribute('id')
    const lineId = adders[i].dataset.target
    new challengeAdder(id, lineId, 'list-ordered-flags', 'list-to-add-flags');
}