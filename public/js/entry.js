class App {
    constructor(){
        this.helps = [
            `선택 도구는 가장 기본적인 도구로써, 작업 영역 내의 한지를 선택할 수 있게 합니다. 마우스 클릭으로 한지를 활성화하여 이동시킬 수 있으며, 선택된 한지는 삭제 버튼으로 삭제시킬 수 있습니다.`,
            `회전 도구는 작업 영역 내의 한지를 회전할 수 있는 도구입니다. 마우스 더블 클릭으로 회전하고자 하는 한지를 선택하면, 좌우로 마우스를 끌어당겨 회전시킬 수 있습니다. 회전한 뒤에는 우 클릭의 콘텍스트 메뉴로 '확인'을 눌러 한지의 회전 상태를 작업 영역에 반영할 수 있습니다.`,
            `자르기 도구는 작업 영역 내의 한지를 자를 수 있는 도구입니다. 마우스 더블 클릭으로 자르고자 하는 한지를 선택하면 마우스를 움직임으로써 자르고자 하는 궤적을 그릴 수 있습니다. 궤적을 그린 뒤에는 우 클릭의 콘텍스트 메뉴로 '자르기'를 눌러 그려진 궤적에 따라 한지를 자를 수 있습니다.`,
            `붙이기 도구는 작업 영역 내의 한지들을 붙일 수 있는 도구입니다. 마우스 더블 클릭으로 붙이고자 하는 한지를 선택하면 처음 선택한 한지와 근접한 한지들을 선택할 수 있습니다. 붙일 한지를 모두 선택한 뒤에는 우 클릭의 콘텍스트 메뉴로 '붙이기'를 눌러 선택한 한지를 붙일 수 있습니다.`
        ];  
        this.findList = [];
        this.index = null;   

        new IDB("seoul", ["inventory"], async db => {
            this.db = db;

            this.ws = new Workspace(this);
            
            this.setEvents();

            let artworks = await (fetch("/json/craftworks.json").then(res => res.json()));
            let tags = artworks.reduce((p, c) => [...p, ...c.hash_tags.map(tag => tag.substr(1))], []);
            this.entryModule = new HashModule("#entry_tags", tags);
        });
    }

    get focusItem(){
        return this.findList[this.index];
    }

    makeContextMenu(menus, x, y){
        $(".context-menu").remove();
        let $menus = $(`<div class="context-menu" style="left: ${x}px; top: ${y}px;"></div>`);
        menus.forEach(({name, onclick}) => {
            let $menu = $(`<div class="context-menu__item">${name}</div>`);
            $menu.on("mousedown", onclick);
            $menus.append($menu);
        });
        
        $(document.body).append($menus);
    }

    setEvents(){
        $(".tool__item").on("click", e => {
            let role = e.currentTarget.dataset.role;
            $(".tool__item").removeClass("active");
            
            if(this.ws.tool){
                this.ws.tool.cancel && this.ws.tool.cancel();
                this.ws.tool.unselectAll();
            }

            if(this.ws.selected == role){
                this.ws.selected = null;
            } else {
                this.ws.selected = role;
                $(e.currentTarget).addClass("active");
            }

        });

        $(".btn-delete").on("mousedown", e => {
            if(this.ws.selected == "select" && this.ws.tool.selected){
                this.ws.parts = this.ws.parts.filter(part => part != this.ws.tool.selected);
            } else {
                alert("한지를 선택해 주세요.");
            }
        });

        $(window).on("click", e => {
            $(".context-menu").remove();
        }); 

        $("[data-target='#insert-modal']").on("click", async e => {
            let list = await ( fetch("/api/inventory").then(res => res.json()) );
            
            $("#insert-modal .row").html('');
            list.forEach(item => {
                $("#insert-modal .row").append(`<div class="col-lg-3">
                                                    <div class="item bg-white border" data-id="${item.id}">
                                                        <img class="fit-cover hx-200" src="${item.image}" alt="한지">
                                                        <div class="p-3 border-top">
                                                            <div class="fx-3">${item.paper_name}</div>
                                                            <div class="mt-2">
                                                                <span class="fx-n2 text-muted">사이즈</span>
                                                                <span class="fx-n1 ml-2">${item.width_size}px × ${item.height_size}px</span>
                                                            </div>
                                                            <div class="mt-2">
                                                                <span class="fx-n2 text-muted">소지 수량</span>
                                                                <span class="fx-n1 ml-2">${item.hasCount < 0 ? '∞' : item.hasCount}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>`);
            });
        });

        $("#insert-modal").on("click", ".item", async e => {
            let id = e.currentTarget.dataset.id;
            let list = await ( fetch("/api/inventory").then(res => res.json()) );
            let item = list.find(item => item.id == id);
            item.hasCount--;
            if(item.hasCount == 0){
                // await this.db.delete("inventory", item.id);
                $.post("/delete/inventory/" + id);
            } else {
                $.post("/update/inventory/" + id, {count: item.hasCount});
            }

            this.ws.pushPart(item);

            $("#insert-modal").modal("hide");
        });

        $("#entry-form").on("submit", e => {
            e.preventDefault();

            this.ws.tool && this.ws.tool.unselectAll();
            $("#image").val( this.ws.canvas.toDataURL("image/jpeg") );
            

            $("#entry-form")[0].submit();
        });

        // 도움말 영역
        var search = () => {
            let input = $(".help-search > input").val();
            if(input.length == 0) return;
            
            let regex = new RegExp(input.replace(/([.+*?^$\(\)\[\]\\\\\\/])/g, "\\$1"), "g");
            this.helps.forEach((text, i) => {
                let replaced = text.replace(regex, m1 => `<span>${m1}</span>`);
                $(".help-body .tab").eq(i).html(replaced);
            });
            
            this.findList = Array.from( $(".help-body span") );;
            if(this.findList.length > 0){
                this.index = 0;
                this.focusItem.classList.add("active");

                let target = this.focusItem.parentElement.dataset.target;
                $(target)[0].checked = true;

                $(".help-message").text(`${this.findList.length}개 중 ${this.index + 1}번째`);
            }
            else {
                this.index = null;
                $(".help-message").text("일치하는 내용이 없습니다.");
            }
        };
        $(".btn-search").on("click", () => search());
        $(".help-search > input").on("keydown", e => {
            if(e.keyCode === 13)
                search();
        });


        $(".btn-prev").on("click", e => {
            if(this.findList.length === 0) return;
            this.focusItem.classList.remove("active");
            this.index = this.index - 1 < 0 ? this.findList.length - 1 : this.index - 1;
            this.focusItem.classList.add("active");

            let target = this.focusItem.parentElement.dataset.target;
            $(target)[0].checked = true;

            $(".help-message").text(`${this.findList.length}개 중 ${this.index + 1}번째`);
        });

        $(".btn-next").on("click", e => {
            if(this.findList.length === 0) return;
            this.focusItem.classList.remove("active");
            this.index = this.index + 1 >= this.findList.length ? 0 : this.index + 1;
            this.focusItem.classList.add("active");

            let target = this.focusItem.parentElement.dataset.target;
            $(target)[0].checked = true;

            $(".help-message").text(`${this.findList.length}개 중 ${this.index + 1}번째`);
        });
    }
}


$(function(){
    let app = new App();
});
