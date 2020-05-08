let addPic;

class DragAndDrop {
    constructor(){
        this.dragAble           = null;
        this.counter            = 0;
        this.draggedElements    = null;
        this.originOfElement    = null;
        this.dragableElements   = null;
        this.nameOfInstance     = null;
        // Paramaters initialized in init() function
        this.dragAbleElement    = null;
        this.dropZone           = null;
        this.origin             = null;
        this.draggableClassName = null;
        this.hoverElement       = null;
        this.list               = null;
        this.listIndex          = null;
        this.nextElement        = null;
        this.numberOfNodesByElement = 2;
        this.yPre               = null;
        this.direction          = null;
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


// Dragging functions
    onDragStart(e) {
        this.dragAbleElement = e.target;
        this.originOfElement = this.dragAbleElement.parentElement;
        this.list = this.originOfElement.childNodes;
    }

    onDrop() {
    }

    onDestinationOver(e) {
        e.preventDefault();
    }

    onOriginOver(e) {
        this.hoverElement=e;
        this.mouseDirection(e)
        console.log(this.direction);
        this.getIdInNodesList (e.target);
        e.preventDefault();
    }

    onDropBack(e) {
        e.preventDefault();
        if (this.direction === 'top') {
            this.originOfElement.insertBefore(this.dragAbleElement, this.originOfElement.childNodes[this.listIndex]);
        }
        else {
            this.originOfElement.insertBefore(this.dragAbleElement, this.originOfElement.childNodes[this.nextElement]);
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
}
