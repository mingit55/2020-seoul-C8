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
        <div>
            <div class="title bar-left border-red text-red">알려드립니다 - <?=$notice->id?></div>
            <div class="text-title mt-4"><?=enc($notice->title)?></div>
            <div class="mt-3">
                <span class="fx-n1 text-muted">작성일</span>
                <span class="ml-3"><?=dt($notice->created_at)?></span>
            </div>
        </div>
        <div>
            <?php if(admin()):?>
                <button data-toggle="modal" data-target="#update-form" class="btn-filled">수정하기</button>
                <a href="/delete/notices/<?=$notice->id?>" class="btn-bordered">삭제하기</a>
            <?php endif;?>
        </div>
    </div>
    <div class="mt-3 textarea"><?=enc($notice->content)?></div>
    <div class="mt-4 row">
        <?php foreach($notice->files as $file):?>
            <?php if(isImage($file->name)):?>
                <div class="col-lg-3">
                    <img src="<?=$file->viewname?>" alt="이미지">
                </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>
    <div class="mt-5">
        <div class="title bar-left border-red text-red">첨부 파일</div>
        <div class="mt-3">
            <?php foreach($notice->files as $file):?>
            <div class="py-3 border-top d-between">
                <div>   
                    <div class="fx-2"><?=$file->name?></div>
                    <div class="fx-n1 text-muted mt-2"><?=$file->size?></div>
                </div>
                <a href="/download/<?=$file->filename?>" class="btn-filled">다운로드</a>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>


<form action="/update/notices/<?=$notice->id?>" id="update-form" class="modal fade" method="post" enctype="multipart/form-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">공지 수정</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" class="form-control" name="title" maxlength="50" value="<?=$notice->title?>" required>
                </div>
                <div class="form-group">
                    <label>내용</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" required><?=$notice->content?></textarea>
                </div>
                <div class="form-group">
                    <label>첨부 파일</label>
                    <div class="custom-file">
                    <?php $cnt = count($notice->files);?>
                        <label for="upload" class="custom-file-label"><?=$cnt == 0 ? '파일을 선택하세요' : "{$cnt}개의 파일"?></label>
                        <input type="file" name="files[]" multiple id="upload" class="custom-file-input">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">수정 완료</button>
            </div>
        </div>
    </div>
</form>