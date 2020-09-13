class Select extends Tool {
    constructor(){
        super(...arguments);
    }

    onmousedown(e){
        this.unselectAll();

        let target = this.getMouseTarget(e);
        if(target){
            target.active = true;
            this.selected = this.mouseTarget = target;

            this.downXY = this.getXY(e);
            this.firstXY = [target.x, target.y];
        }
    }

    onmousemove(e){
        if(!this.mouseTarget) return;
        
        let [x,y] = this.getXY(e);
        let [fx,fy] = this.firstXY;
        let [dx, dy] = this.downXY;

        this.selected.x = fx + x - dx;
        this.selected.y = fy + y - dy;
    }

    onmouseup(){
        this.mouseTarget = null;
    }
}