class Part {
    constructor(src){
        this.src = src;
        this.x = 0;
        this.y = 0;
        this.active = false;
        
        this.canvas = createCanvas(this.width, this.height);
        this.ctx = this.canvas.getContext("2d");

        this.sliced = createCanvas(this.width, this.height);
        this.sctx = this.sliced.getContext("2d");
    }
    get width(){
        return this.src.width;
    }
    get height(){
        return this.src.height;
    }

    update(){
        this.ctx.clearRect(0, 0, this.width, this.height);

        if(this.active) this.ctx.putImageData(this.src.borderData, 0, 0);
        else this.ctx.putImageData(this.src.imageData, 0, 0);

        this.ctx.drawImage(this.sliced, 0, 0);
    }

    recalculate(){
        let [X,Y,W,H] = this.src.getSize();
        let src = new Source( new ImageData(W, H) );
        
        for(let x = X; x < X + W; x++){
            for(let y = Y; y < Y + H; y++){
                let color = this.src.getColor(x, y);
                if(color){
                    src.setColor(x - X, y - Y, color);
                }
            }
        }

        this.x += X;
        this.y += Y;

        this.canvas.width = W;
        this.canvas.height = H;

        this.src = src;
        this.src.borderData = this.src.getBorderData();
 
        let slicedSrc = new Source(this.sctx.getImageData(X, Y, W, H));
        this.sliced.width = W;
        this.sliced.height = H;
        this.sctx.clearRect(0, 0, W, H);
        for(let x = 0; x < W; x++){
            for(let y = 0; y < H; y++){
                if(slicedSrc.getColor(x, y) && this.src.isSlicedPixel(x, y)){
                    this.sctx.fillRect(x, y, 1, 1);
                }
            }
        }
    }

    isNear(part){
        for(let y = this.y; y < this.y + this.height; y++){
            for(let x = this.x; x < this.x + this.width; x++){
                let tx = x - this.x;
                let ty = y - this.y;

                let px = x - part.x;
                let py = y - part.y;

                if(part.src.getColor(px, py) && this.src.getColor(tx, ty)){
                    return true;
                }
            }
        }
        return false;
    }
}