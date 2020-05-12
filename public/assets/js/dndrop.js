class DragAndDrop {
    constructor() {
        this.originOfElement    = null;
        this.dragableElements   = null;
        this.nameOfInstance     = null;
        this.dragAbleElement    = null;
        this.dropZone           = null;
        this.origin             = null;
        this.draggableClassName = null;
        this.list               = null;
        this.listIndex          = null;
        this.nextElement        = null;
        this.numberOfNodesByElement = 2;
        this.yPre               = null;
        this.direction          = null;
        this.spacer             = document.createElement("li");
        this.initSpacer();
    }

    initSpacer() {
        this.spacer.classList.add('list-group-item');
        this.spacer.classList.add('spacer');
    }

// Initialisation
    init(params) {
        this.nameOfInstance     = params.instance;
        this.dropZone           = params.dropZone;
        this.origin             = params.origin;
        this.draggableClassName = params.draggableClassName;
        this.dragableElements   = document.getElementsByClassName(this.draggableClassName);
        this.addDestinationAttributes();
        this.addOriginAttributes();
        this.addAttributesDragableElements();
    }

// Attributes
    addDestinationAttributes () {
        this.dropZone.setAttribute('ondrop', this.nameOfInstance+'.onDrop(event)');
        this.dropZone.setAttribute('ondragover', this.nameOfInstance + '.onDestinationOver(event)');
    }

    addOriginAttributes () {
        this.origin.setAttribute('ondrop', this.nameOfInstance + '.onDropBack(event)');
        this.origin.setAttribute('ondragover', this.nameOfInstance + '.onOriginOver(event)');
    }

    addAttributesDragableElements(event) {
        for (let i=0; i<this.dragableElements.length ; i++) {
            this.dragableElements[i].setAttribute('draggable', true);
            this.dragableElements[i].setAttribute('ondragstart', this.nameOfInstance + '.onDragStart(event)');
        }
    }

    onDragStart(e) {
        this.dragAbleElement = e.target;
        this.originOfElement = this.dragAbleElement.parentElement;
        this.list = this.originOfElement.childNodes;
        this.dragAbleElement.style.opacity = 0.1;
    }

    onDrop() {
    }

    onDestinationOver(e) {
        e.preventDefault();
    }

    onOriginOver(e) {
        e.preventDefault();
        this.hoverElement=e;
        this.mouseDirection(e)
        this.getIdInNodesList (e.target);
        if (e.target == this.spacer) {
            console.log('todo');
        }
        if (e.target != this.spacer && e.target.nodeName != 'ul') {
            this.insertSpacer();
        }
    }

    onDropBack(e) {
        e.preventDefault();
        if (this.direction === 'top') {
            this.originOfElement.insertBefore(this.dragAbleElement, this.originOfElement.childNodes[this.listIndex]);
        }
        else {
            this.originOfElement.insertBefore(this.dragAbleElement, this.originOfElement.childNodes[this.nextElement]);
        }
        this.removeSpacer();
        let fieldForOrder = document.getElementById('order-of-challenges')
        fieldForOrder.value = this.getList()
        this.addNumbers();
        this.reinitRemovers();
    }

    reinitRemovers() {
        const removers = document.getElementsByClassName('remover');
        for (let i=0; i<removers.length; i++) {
            const id = removers[i].getAttribute('id')
            const lineId = removers[i].dataset.target
            const remover = new challengeRemover(id, lineId, 'list-to-add-flags', 'list-ordered-flags');
        }
    }

    getIdInNodesList (e) {
        this.originOfElement.childNodes.forEach((val, index)=> {
            if (val == e) {
                this.listIndex   = index;
                this.nextElement = index + this.numberOfNodesByElement;
            }
        });
    }

    mouseDirection (e) {
        if (e.pageY < this.yPre) {
            this.direction = "top"
        } else if (e.pageY > this.yPre) {
            this.direction = "bottom"
        }
        this.yPre = e.pageY;
    }

    insertSpacer() {
        if (this.direction === 'top') {
            this.originOfElement.insertBefore(this.spacer, this.originOfElement.childNodes[this.listIndex]);
        }
        else {
            this.originOfElement.insertBefore(this.spacer, this.originOfElement.childNodes[this.nextElement]);
        }
    }

    removeSpacer() {
            this.spacer.remove();
            this.dragAbleElement.style.opacity = 1;
    }

    getList() {
        let newContainer = document.getElementById('list-ordered-flags')
        let order = [];
        let orderedElements = newContainer.getElementsByClassName('list-group-item')
        for (let i=0; i<orderedElements.length; i++) {
            order.push(orderedElements[i].dataset.challenge)
        }
        return JSON.stringify(order);
    }

    addNumbers() {
        let i = 0;
        this.originOfElement.childNodes.forEach((e) =>{
                if (e.nodeName =='LI') {
                    i++;
                    let htmlForNumber = '<span class="list-order-number">' + i + '</span> ';
                    let html = e.innerHTML;
                    let htmlToKeep = html.split('<i ');
                    e.innerHTML = htmlForNumber + '<i ' + htmlToKeep[1];
                }
            });
    }
}
