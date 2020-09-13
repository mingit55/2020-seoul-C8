const UID = document.querySelector("#uid").value;

class App {
    constructor(){
        new IDB("seoul", ["papers", "inventory"], async db => {
            this.db = db;
            this.papers = await this.getPapers();
            this.tags = this.papers.reduce((p, c) => [...p, ...c.hash_tags], []);
            this.cartList = [];
            this.searches = [];

            this.update();
            this.setEvents();

            this.searchModule = new HashModule("#search_tags", this.tags);
            this.insertModule = new HashModule("#insert_tags", this.tags);
        });
    }

    get totalPoint(){
        return this.cartList.reduce((p, c) => p + c.totalPoint, 0);
    }

    get totalCount(){
        return this.cartList.reduce((p, c) => p + c.buyCount, 0);
    }

    async getPapers(){
        return fetch("/api/papers").then(res => res.json()).then(papers => papers.map(paper => new Paper(paper)));
        // let papers = await this.db.getAll("papers");
        
        // if(papers.length === 0){
        //     papers = (await (fetch("/json/papers.json").then(res => res.json())))
        //         .map(paper => ({
        //             ...paper,
        //             id: parseInt(paper.id),
        //             width_size: parseInt(paper.width_size.replace(/[^0-9]/g, "")),
        //             height_size: parseInt(paper.height_size.replace(/[^0-9]/g, "")),
        //             point: parseInt(paper.point.replace(/[^0-9]/g, "")),
        //             image: "/images/papers/" + paper.image,
        //             hash_tags: paper.hash_tags.map(tag => tag.substr(1))
        //         }));

        //     papers.forEach(paper => {
        //         this.db.add("papers", paper);
        //     });
        // }

        // return papers.map(paper => new Paper(paper));
    }

    update(){
        let viewList = this.papers;
        
        if(this.searches.length > 0){
            viewList = viewList.filter(item => this.searches.every(tag => item.hash_tags.includes(tag)));
        }
    
        $("#store").html('');
        viewList.forEach(item => {
            item.update();
            $("#store").append(item.$storeElem);
        });

        $("#cart").html('');
        this.cartList.forEach(item => {
            $("#cart").append(item.$cartElem);
        });

        $("#total").text(this.totalPoint.toLocaleString());
        $("#cartList").val(JSON.stringify(this.cartList));
        $("#totalPoint").val(JSON.stringify(this.totalPoint));
        $("#totalCount").val(JSON.stringify(this.totalCount));
    }

    setEvents(){
        $("#image").on("change", e => {
            let file = e.target.files.length > 0 ? e.target.files[0] : null;

            if(!file){
                alert("이미지를 업로드 해 주세요!");
                e.target.value = "";
            } else if(!["jpg", "png", "gif"].includes(file.name.substr(-3).toLowerCase())){
                alert("이미지 파일만 업로드 가능합니다.");
                e.target.value = "";
            } else if(file.size > 1024 * 1024 * 5) {
                alert("파일은 5MB를 넘을 수 없습니다.");
                e.target.value = "";
            }

            let reader = new FileReader();
            reader.onload = () => $("#base64").val(reader.result);
            reader.readAsDataURL(file);
        });

        $("#insert-form").on("submit", async e => {
            // e.preventDefault();

            // let paper = Array.from($("#insert-form input[name]"))
            //     .reduce((p, c) => {
            //         p[c.name] = c.value;
            //         return p;
            //     }, {});

            // paper.width_size = parseInt(paper.width_size);
            // paper.height_size = parseInt(paper.height_size);
            // paper.point = parseInt(paper.point);
            // paper.hash_tags = JSON.parse(paper.hash_tags);

            // paper.id = await this.db.add("papers", paper);
            // this.papers.push(new Paper(paper));
            // this.update();


            // $("#insert-form").modal("hide");
            // $("#insert-form input").val('');
        });

        $("#store").on("click", ".btn-insert", e => {
            let paper = this.papers.find(paper => paper.id == e.currentTarget.dataset.id);
            paper.buyCount++;

            if(!this.cartList.includes(paper)) {
                this.cartList.push(paper);
            }

            this.update();
        });

        $("#cart").on("input", ".buy-count", e => {
            let value = parseInt(e.target.value);
            if(isNaN(value) || value < 1) value = 1;
            else if(value > 1000) value = 1000;

            e.target.value = value;

            let paper = this.papers.find(paper => paper.id == e.currentTarget.dataset.id);
            paper.buyCount = value;

            this.update();
            e.target.focus();
        });

        $("#cart").on("click", ".btn-delete", e => {
            let paper = this.papers.find(paper => paper.id == e.currentTarget.dataset.id);
            paper.buyCount = 0;

            this.cartList = this.cartList.filter(item => item !== paper);

            this.update();
        });

        $(".btn-search").on("click", e => {
            this.searches = this.searchModule.tags;
            this.update();
        });
        
        $("#buy-form").on("submit", async e => {
            // e.preventDefault();

            // alert(`총 ${this.totalCount}개의 한지가 구매되었습니다.`);
            
            // await Promise.all(this.cartList.map(async item => {
            //     let exist = await this.db.get("inventory", item.id);
                
            //     if(exist) {
            //         exist.hasCount += item.buyCount;
            //         await this.db.put("inventory", exist);
            //     } else {
            //         await this.db.add("inventory", {
            //             id: item.id,
            //             hasCount: item.buyCount,
            //             paper_name: item.paper_name,
            //             width_size: item.width_size,
            //             height_size: item.height_size,
            //             image: item.image,
            //         });
            //     }

            //     item.buyCount = 0;
            // }));           

            // this.cartList = [];
            // this.update();
        });
    }
}

class Paper {
    constructor({id, image, paper_name, company_name, width_size, height_size, point, hash_tags, uid}){
        this.id = id;
        this.image = image;
        this.paper_name = paper_name;
        this.company_name = company_name;
        this.width_size = width_size;
        this.height_size = height_size;
        this.point = point;
        this.hash_tags = hash_tags;
        this.uid = uid;

        this.buyCount = 0;

        this.$storeElem = $(`<div class="col-lg-3 mb-4">
                                <div class="border bg-white">
                                    <img src="${this.image}" alt="한지 이미지" class="fit-cover hx-200">
                                    <div class="p-3 border-top">
                                        <div class="fx-3">${this.paper_name}</div>
                                        <div class="mt-2">
                                            <span class="fx-n2 text-muted">업체명</span>
                                            <span class="ml-2 fx-n1">${this.company_name}</span>
                                        </div>
                                        <div class="mt-2">
                                            <span class="fx-n2 text-muted">사이즈</span>
                                            <span class="ml-2 fx-n1">${this.width_size}px × ${this.height_size}px</span>
                                        </div>
                                        <div class="mt-2">
                                            <span class="fx-n2 text-muted">포인트</span>
                                            <span class="ml-2 fx-n1">${this.point}p</span>
                                        </div>
                                        <div class="mt-2 d-flex flex-wrap fx-n2 text-muted">
                                            ${this.hash_tags.map(tag => `<span class="m-1">#${tag}</span>`).join('')}
                                        </div>
                                        ${
                                            uid == UID ? '' 
                                            : `<button class="btn-insert btn-filled mt-4" data-id="${this.id}">구매하기</button>`
                                        }
                                    </div>
                                </div>
                            </div>`);

        this.$cartElem = $(`<div class="t-row">
                                <div class="cell-50">
                                    <div class="align-center">
                                        <img src="${this.image}" alt="상품 이미지" width="80" height="80">
                                        <div class="ml-4 text-left">
                                            <div class="fx-3">${this.paper_name}</div>
                                            <div class="fx-n1 text-muted mt-1">
                                                ${this.company_name}
                                                <span class="mx-1">·</span>
                                                ${this.point}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cell-20">
                                    <input type="number" class="buy-count" min="1" value="1" data-id="${this.id}">
                                </div>
                                <div class="cell-20">
                                    <span class="total">${this.totalPoint.toLocaleString()}</span>
                                    p
                                </div>
                                <div class="cell-10">
                                    <button class="btn-filled btn-delete" data-id="${this.id}">삭제</button>
                                </div>
                            </div>`);
    }

    get totalPoint(){
        return this.buyCount * this.point;
    }

    update(){
        this.$storeElem.find(".btn-insert").text(this.buyCount > 0 ? `추가하기(${this.buyCount.toLocaleString()}개)` : "구매하기");
        this.$cartElem.find(".buy-count").val(this.buyCount);
        this.$cartElem.find(".total").text(this.totalPoint.toLocaleString());
    }
}

$(function(){
    let app = new App();
});