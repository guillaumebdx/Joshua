const adders = document.getElementsByClassName('challenge-adder')
for (let i=0; i<adders.length; i++) {
    adders[i].addEventListener('click', (e) => {
        let challengeId = e.target.dataset.challenge
        let container = document.getElementById(e.target.dataset.target)
        let child = container
        let destination = document.getElementById('challenge-list')
        let numberOfLines = destination.getElementsByClassName('list-group-item').length
        numberOfLines++
        let html = child.innerHTML
        html = html.split('<button')
        html = html[0]
        destination.insertAdjacentHTML('beforeend', '<li data-challenge="'+challengeId+'" id="challenge-id'+challengeId+'" class="list-group-item list-group-item-dark rounded-sm p-1 pl-2 mb-2 draggable"><span class="list-order-number">'+numberOfLines+'</span>'+html+'</li>')
        container.remove()
        let newContainer = document.getElementById('challenge-list')
        let order = []
        let orderedElements = newContainer.getElementsByClassName('list-group-item')
        for (let i=0; i<orderedElements.length; i++) {
            order.push(orderedElements[i].dataset.challenge)
        }
        let fieldForOrder = document.getElementById('order-of-challenges')
        fieldForOrder.value = JSON.stringify(order)
        console.log(JSON.stringify(order))
        let challenge = new DragAndDrop();
        challenge.init({
            instance           : 'challenge',
            draggableClassName : 'draggable',
            origin             : document.getElementById('challenge-list'),
            dropZone           : document.getElementById('challenge-list'),
        });
    })
}
