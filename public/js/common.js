class IDB {
    constructor(dbname, stores, callback = () => {}){
        let req = indexedDB.open(dbname, 1);
        req.onupgradeneeded = () => {
            let db = req.result;
            stores.forEach(store => {
                db.createObjectStore(store, {keyPath:"id", autoIncrement: true});
            });
        };
        req.onsuccess = () => {
            this.db = req.result;
            callback(this);
        }
    }

    objectStore(storeName){
        return this.db.transaction(storeName, "readwrite").objectStore(storeName);
    }

    add(storeName, data){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.add(data);
            req.onsuccess = () => res(req.result);
        });
    }

    delete(storeName, data){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.delete(data);
            req.onsuccess = () => res(req.result);
        });
    }

    put(storeName, data){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.put(data);
            req.onsuccess = () => res(req.result);
        });
    }

    get(storeName, data){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.get(data);
            req.onsuccess = () => res(req.result);
        });
    }

    getAll(storeName){
        return new Promise(res => {
            let os = this.objectStore(storeName);
            let req = os.getAll();
            req.onsuccess = () => res(req.result);
        });
    }
}


class HashModule {
    constructor(selector, list){
        this.$root = $(selector);
        this.hasList = list;
        this.showList = [];
        this.index = 0;
        this.tags = [];
        
        this.init();
        this.setEvents();
    }

    get focusItem(){
        return this.showList[this.index];
    }

    init(){
        this.$root.html(`<div class="hash-module">
                            <input type="hidden" name="${this.$root.data('name')}" class="value" value="[]">
                            <div class="hash-module__input">
                                #   
                                <input type="text" class="input">
                                <div class="example">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="error text-red mt-2 fx-n2"></div>`);
        this.$input = this.$root.find(".input");
        this.$value = this.$root.find(".value");
        this.$list = this.$root.find(".example");
        this.$container = this.$root.find(".hash-module");
        this.$error = this.$root.find(".error");
    }

    update(){
        this.$list.html('');
        this.showList.forEach((tag, i) => {
            this.$list.append(`<div class="example__item ${this.index == i ? 'active' : ''}" data-idx="${i}">#${tag}</div>`);
        });


        this.$container.find(".hash-module__item").remove();
        this.tags.forEach((tag, i) => {
            this.$container.append(`<div class="hash-module__item">
                                        #${tag}
                                        <span class="remove" data-idx="${i}">×</span>
                                    </div>`);
        });


        this.$value.val(JSON.stringify(this.tags));
    }

    addTag(tag){
        if(tag.length < 2 || 30 < tag.length) return
        else if(this.tags.includes(tag)) this.$error.text("이미 추가한 태그입니다.")
        else if(this.tags.length >= 10) this.$error.text("태그는 10개까지만 추가할 수 있습니다.");
        else {
            this.tags.push(tag);
            
            this.$input.val('');
            this.showList = [];
            this.index = null;
        }
    }

    setEvents(){
        this.$input.on("input", e => {
            let value = e.target.value.replace(/[^a-zA-Z0-9ㄱ-ㅎㅏ-ㅣ가-힣_]/g, "");
            e.target.value = value;
            
            this.showList = [];
            this.index = null;
            this.$error.text('');
            if(value.length > 0){
                let regex = new RegExp(
                    "^" + value.replace(/([.+*?^$\(\)\[\]\\\\\\/])/g, "\\$1"),
                );

                this.hasList.forEach(tag => {
                    if(regex.test(tag) && !this.showList.includes(tag)){
                        this.showList.push(tag);
                    }
                });

            }

            this.update();
        });

        this.$input.on("keydown", e => {
            if(this.index !== null && e.keyCode == 13){
                e.preventDefault();
                this.$input.val(this.focusItem);
                this.showList = [];
                this.index = null;
            } 
            else if([13, 32, 9].includes(e.keyCode)){
                e.preventDefault();
                this.addTag( this.$input.val() );
            }
            else if(e.keyCode === 38){
                e.preventDefault();
                this.index = this.index === null ? 0 
                    : this.index - 1 < 0 ? this.showList.length - 1
                    : this.index - 1;
            }
            else if(e.keyCode === 40){
                e.preventDefault();
                this.index = this.index === null ? 0
                    : this.index + 1 >= this.showList.length ? 0
                    : this.index + 1;
            }
            this.update();
        });

        this.$container.on("click", ".remove", e => {
            let index = parseInt(e.currentTarget.dataset.idx);
            this.tags.splice(index, 1);
            this.update();
            this.$error.text('');
        });

        this.$list.on("click", ".example__item", e => {
            this.index = parseInt(e.currentTarget.dataset.idx);
            this.update();
            this.$input.focus();
        });

        this.$input.on("blur", e => {
            this.showList = [];
            this.index = null;
            this.update();
            
        });
    }
}

function createCanvas(w, h){
    let canvas = document.createElement("canvas");
    canvas.width = w;
    canvas.height = h;
    return canvas;
}

$(function(){
    $(".custom-file-input").on("change", e => {
        let $label = $(e.target).siblings(".custom-file-label");
        let len = e.target.files.length;
        if(len > 0) {
            $label.text(len+ "개의 파일");
        } else {
            $label.text("파일을 선택하세요");
        }
    });

    $("[data-toggle='modal']").on("click", e => {
        e.stopPropagation();

        let target = $(e.currentTarget).data("target");
        $(target).modal("show");
    });
});