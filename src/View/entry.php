<!-- 비주얼 영역 -->
<div class="position-relative hx-350">
    <div class="background background--black">
        <img src="./images/visual/1.jpg" alt="비주얼 이미지" title="비주얼 이미지">
    </div>
    <div class="position-center text-center w-100">
        <div class="fx-8 text-white">출품신청</div>
        <div class="fx-4 text-gray mt-3">전주한지문화축제 > 출품신청</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div id="workspace">
    <canvas width="1150" height="800"></canvas>
    <div class="tool">
        <div title="선택" class="tool__item" data-role="select"><i class="fa fa-mouse-pointer"></i></div>
        <div title="회전" class="tool__item" data-role="spin"><i class="fa fa-repeat"></i></div>
        <div title="자르기" class="tool__item" data-role="cut"><i class="fa fa-cut"></i></div>
        <div title="붙이기" class="tool__item" data-role="glue"><i class="fa fa-object-group"></i></div>
        <div title="추가" class="tool__item" data-target="#insert-modal" data-toggle="modal"><i class="fa fa-folder"></i></div>
        <div title="삭제" class="btn-delete tool__item"><i class="fa fa-trash"></i></div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="title bar-left text-red border-red mb-4">출품정보</div>
            <form action="/insert/artworks" id="entry-form" method="post">
                <input type="hidden" id="image" name="image">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="form-group">
                    <label>설명</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>해시태그</label>
                    <div id="entry_tags" data-name="hash_tags"></div>
                </div>
                <div class="form-group mt-3 text-right">
                    <button class="btn-bordered">출품하기</button>
                </div>
            </form>
        </div>
        <div class="col-lg-6">
            <div class="title bar-left text-red border-red mb-4">도움말</div>
            <input type="radio" id="focus-select" name="focus" hidden checked>
            <input type="radio" id="focus-spin" name="focus" hidden>
            <input type="radio" id="focus-cut" name="focus" hidden>
            <input type="radio" id="focus-glue" name="focus" hidden>
            <div class="help">
                <div class="help-search align-center">
                    <input type="text" class="border bg-white fx-n2">
                    <button class="btn-search icon text-red"><i class="fa fa-search"></i></button>
                    <button class="btn-prev icon text-red"><i class="fa fa-angle-left"></i></button>
                    <button class="btn-next icon text-red"><i class="fa fa-angle-right"></i></button>
                    <p class="help-message ml-2 fx-n2 text-muted"></p>
                </div>
                <div class="help-header mt-3">
                    <label for="focus-select" class="tab">선택</label>
                    <label for="focus-spin" class="tab">회전</label>
                    <label for="focus-cut" class="tab">자르기</label>
                    <label for="focus-glue" class="tab">붙이기</label>
                </div>
                <div class="help-body">
                    <div class="tab" data-target="#focus-select">
                        선택 도구는 가장 기본적인 도구로써, 작업 영역 내의 한지를 선택할 수 있게 합니다. 마우스 클릭으로 한지를 활성화하여 이동시킬 수 있으며, 선택된 한지는 삭제 버튼으로 삭제시킬 수 있습니다.
                    </div>
                    <div class="tab" data-target="#focus-spin">
                        회전 도구는 작업 영역 내의 한지를 회전할 수 있는 도구입니다. 마우스 더블 클릭으로 회전하고자 하는 한지를 선택하면, 좌우로 마우스를 끌어당겨 회전시킬 수 있습니다. 회전한 뒤에는 우 클릭의 콘텍스트 메뉴로 '확인'을 눌러 한지의 회전 상태를 작업 영역에 반영할 수 있습니다.
                    </div>
                    <div class="tab" data-target="#focus-cut">
                        자르기 도구는 작업 영역 내의 한지를 자를 수 있는 도구입니다. 마우스 더블 클릭으로 자르고자 하는 한지를 선택하면 마우스를 움직임으로써 자르고자 하는 궤적을 그릴 수 있습니다. 궤적을 그린 뒤에는 우 클릭의 콘텍스트 메뉴로 '자르기'를 눌러 그려진 궤적에 따라 한지를 자를 수 있습니다.
                    </div>
                    <div class="tab" data-target="#focus-glue">
                        붙이기 도구는 작업 영역 내의 한지들을 붙일 수 있는 도구입니다. 마우스 더블 클릭으로 붙이고자 하는 한지를 선택하면 처음 선택한 한지와 근접한 한지들을 선택할 수 있습니다. 붙일 한지를 모두 선택한 뒤에는 우 클릭의 콘텍스트 메뉴로 '붙이기'를 눌러 선택한 한지를 붙일 수 있습니다.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="insert-modal" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">추가하기</div>
            </div>
            <div class="modal-body">
                <div class="row">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/entry/Tool.js"></script>
<script src="/js/entry/Select.js"></script>
<script src="/js/entry/Spin.js"></script>
<script src="/js/entry/Cut.js"></script>
<script src="/js/entry/Glue.js"></script>
<script src="/js/entry/Part.js"></script>
<script src="/js/entry/Source.js"></script>
<script src="/js/entry/Workspace.js"></script>
<script src="/js/entry.js"></script>