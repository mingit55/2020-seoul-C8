class Spin extends Tool {
    constructor(){
        super(...arguments);
    }

    ondblclick(e){
        let target = this.getMouseTarget(e);
        if(target && !this.selected){
            target.active = true;
            target.recalculate();

            this.selected = target;
            this.prevImage = target.src;
            this.prevSliced = createCanvas(target.width, target.height);
            let psctx = this.prevSliced.getContext("2d");
            psctx.drawImage(target.sliced, 0, 0);

            this.image = createCanvas(target.width, target.height);
            let ctx = this.image.getContext("2d");
            ctx.putImageData(target.src.imageData, 0, 0);

            this.sliced = createCanvas(target.width, target.height);
            let sctx = this.sliced.getContext("2d");
            sctx.drawImage(target.sliced, 0, 0);

            let spinSize = Math.sqrt( Math.pow(target.width, 2) + Math.pow(target.height, 2) );
            let imgX = (spinSize - target.width) / 2;
            let imgY = (spinSize - target.height) / 2;

            target.canvas.width = target.canvas.height = spinSize;
            target.sliced.width = target.sliced.height = spinSize;
            target.x -= imgX;
            target.y -= imgY;

            this.canvas = createCanvas(spinSize, spinSize);
            this.ctx = this.canvas.getContext("2d");
            
            this.ctx.drawImage(this.image, imgX, imgY);
            target.src = new Source( this.ctx.getImageData(0, 0, spinSize, spinSize) );
            
            target.sctx.clearRect(0, 0, spinSize, spinSize);
            target.sctx.drawImage(this.sliced, imgX, imgY);
        }
    }

    onmousedown(e){
        if(!this.selected) return;
        this.prevX = e.pageX;
    }

    onmousemove(e){
        if(!this.selected) return;
        
        let target = this.selected;
        let angle = (this.prevX - e.pageX) * Math.PI / 180;
        this.prevX = e.pageX;

        let size = this.canvas.width;
        let imgX = (size - this.image.width) / 2;
        let imgY = (size - this.image.height) / 2;
        
        this.ctx.translate(size / 2, size / 2)
        this.ctx.rotate(angle);
        this.ctx.translate(-size / 2, -size / 2)

        this.ctx.clearRect(0, 0, size, size);
        this.ctx.drawImage(this.image, imgX, imgY);
        target.src = new Source( this.ctx.getImageData(0, 0, size, size) );

        this.ctx.clearRect(0, 0, size, size);
        this.ctx.drawImage(this.sliced, imgX, imgY);
        target.sctx.putImageData(this.ctx.getImageData(0, 0, size, size), 0, 0);
    }

    oncontextmenu(makeFunc){
        if(!this.selected) return;
        makeFunc([
            {name: "확인", onclick: this.accept},
            {name: "취소", onclick: this.cancel}
        ]);
    }

    accept = e => {
        if(!this.selected) return;
        this.selected.recalculate();
        this.unselectAll();
    };  

    cancel = e => {
        if(!this.selected) return;  
        
        let target = this.selected;
        let imgX = (this.canvas.width - this.image.width) / 2;
        let imgY = (this.canvas.height - this.image.height) / 2;

        target.x += imgX;
        target.y += imgY;

        target.canvas.width = this.prevImage.width;
        target.canvas.height = this.prevImage.height;

        target.src = this.prevImage;

        target.sliced = this.prevSliced;
        target.sctx = target.sliced.getContext("2d");
 
        this.unselectAll();
    };  
}