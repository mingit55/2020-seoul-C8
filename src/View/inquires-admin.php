<!-- 비주얼 영역 -->
<div class="position-relative hx-350">
    <div class="background background--black">
        <img src="/images/visual/1.jpg" alt="비주얼 이미지" title="비주얼 이미지">
    </div>
    <div class="position-center text-center w-100">
        <div class="fx-8 text-white">1:1문의</div>
        <div class="fx-4 text-gray mt-3">축제공지사항 > 1:1문의</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container py-5">
    <div class="d-between">
        <div class="title bar-left text-red border-red">1:1문의</div>
    </div>
    <div class="t-head mt-3">
        <div class="cell-10">상태</div>
        <div class="cell-60">제목</div>
        <div class="cell-20">문의일자</div>
        <div class="cell-10">+</div>
    </div>
    <?php foreach($inquires as $inquire):?>
        <!-- 데이터 행 -->
        <div class="t-row" data-toggle="modal" data-target="#view-modal-<?=$inquire->id?>">
            <div class="cell-10"><?=$inquire->answer ? "완료" : "진행 중"?></div>
            <div class="cell-60"><?=enc($inquire->title)?></div>
            <div class="cell-20"><?=dt($inquire->created_at)?></div>
            <div class="cell-10">
                <?php if(!$inquire->answer):?>
                    <button class="btn-filled" data-toggle="modal" data-target="#insert-form-<?=$inquire->id?>">답변하기</button>
                <?php endif;?>
            </div>
        </div>
        <!-- /데이터 행 -->

        <!-- 보기 모달 -->
        <div id="view-modal-<?=$inquire->id?>" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="fx-4">문의 내역</div>
                    </div>
                    <div class="modal-body">
                        <div class="pb-3 border-bottom">
                            <div class="fx-4"><?=enc($inquire->title)?></div>
                            <div class="mt-2 align-center fx-n1 text-muted">
                                <?=$inquire->user_name?>(<?=$inquire->user_email?>)
                                <span class="mx-1">·</span>
                                <?=dt($inquire->created_at)?>
                            </div>
                            <div class="mt-3 textarea">
                                <?=enc($inquire->content)?>
                            </div>
                        </div>
                        <div class="pt-3">
                            <?php if($inquire->answer):?>
                                <div class="fx-n2 text-muted"><?=dt($inquire->answered_at)?></div>
                                <div class="mt-2 textarea"><?=enc($inquire->answer)?></div>
                            <?php else:?>
                                <div class="textarea">문의에 대한 답변이 오지 않았습니다.</div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /보기 모달 -->

        <!-- 답변 모달 -->
        <form action="/insert/answers" method="post" id="insert-form-<?=$inquire->id?>" class="modal fade">
            <input type="hidden" name="iid" value="<?=$inquire->id?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="fx-4">문의하기</div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>내용</label>
                            <textarea name="answer" id="answer" cols="30" rows="10" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-filled">작성 완료</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- /답변 모달 -->
    <?php endforeach;?>
</div>

