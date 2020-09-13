<!-- 비주얼 영역 -->
<div class="position-relative hx-350">
    <div class="background background--black">
        <img src="/images/visual/1.jpg" alt="비주얼 이미지" title="비주얼 이미지">
    </div>
    <div class="position-center text-center w-100">
        <div class="fx-8 text-white">알려드립니다</div>
        <div class="fx-4 text-gray mt-3">축제공지사항 > 알려드립니다</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container py-5">
    <div class="d-between">
        <div class="title bar-left border-red text-red">알려드립니다</div>
        <?php if(admin()):?>
            <button class="btn-custom" data-toggle="modal" data-target="#insert-form">공지 작성</button>
        <?php endif;?>
    </div>
    <div class="t-head mt-4">
        <div class="cell-10">글 번호</div>
        <div class="cell-60">제목</div>
        <div class="cell-30">작성일</div>
    </div>
    <?php foreach($notices->data as $notice):?>
        <div class="t-row" onclick="location.href='/notices/<?=$notice->id?>';">
            <div class="cell-10"><?=$notice->id?></div>
            <div class="cell-60"><?=enc($notice->title)?></div>
            <div class="cell-30"><?=dt($notice->created_at)?></div>
        </div>
    <?php endforeach;?>
    <div class="d-center mt-5">
        <a href="/notices?page=<?=$notices->prevPage?>" class="icon mx-1 bg-red text-white" <?=!$notices->prev ? "disabled" : ""?>>
            <i class="fa fa-angle-left"></i>
        </a>
        <?php for($i = $notices->start; $i <= $notices->end; $i++):?>
            <a href="/notices?page=<?=$i?>" class="icon mx-1 <?=$i == $notices->page ? 'bg-red text-white' : 'border border-red text-red'?>"><?=$i?></a>
        <?php endfor;?>
        <a href="/notices?page=<?=$notices->nextPage?>" class="icon mx-1 bg-red text-white <?=!$notices->next ? "disabled": ""?>">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>

<form action="/insert/notices" method="post" enctype="multipart/form-data" id="insert-form" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">공지 작성</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" class="form-control" name="title" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label>내용</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label>첨부 파일</label>
                    <div class="custom-file">
                        <label for="upload" class="custom-file-label">파일을 선택하세요</label>
                        <input type="file" name="files[]" multiple id="upload" class="custom-file-input">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">작성 완료</button>
            </div>
        </div>
    </div>
</form>