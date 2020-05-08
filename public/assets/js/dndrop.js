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

// Organize items when they return in Origin Zone
    sortDivsInOrigin() {
        let elems = this.origin.querySelectorAll('.'+this.draggableClassName);
        let elemsSortToShow = new Array();
        for (let i=0; i<elems.length ; i++) {
            elemsSortToShow[elems[i].id] = elems[i];
        }
        return(elemsSortToShow);
    }

// Dragging functions
    onDragStart(ev) {
        this.dragAbleElement = ev.target;
        this.originOfElement = this.dragAbleElement.parentElement;
    }

    onDrop(ev) {

    }

    onOriginOver(ev) {
        ev.preventDefault();
    }

    onDropBack(ev) {
        ev.preventDefault();

    }
}
