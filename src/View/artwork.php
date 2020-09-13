<!-- 비주얼 영역 -->
<div class="position-relative hx-350">
    <div class="background background--black">
        <img src="./images/visual/1.jpg" alt="비주얼 이미지" title="비주얼 이미지">
    </div>
    <div class="position-center text-center w-100">
        <div class="fx-8 text-white">참가작품</div>
        <div class="fx-4 text-gray mt-3">전주한지공예대전 > 참가작품</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container py-5">
    <div class="row">
        <div class="col-lg-5">
            <img src="/uploads/<?=$artwork->image?>" alt="이미지" class="fit-cover hx-300">
        </div>
        <div class="col-lg-7">
            <div class="text-title"><?=$artwork->title?></div>
            <div class="mt-2">
                <span class="fx-n1 text-muted">제작일자</span>
                <span class="ml-2"><?=dt($artwork->created_at)?></span>
            </div>
            <div class="mt-2">
                <span class="fx-n1 text-muted">평점</span>
                <span class="ml-2"><?=($artwork->score)?></span>
            </div>
            <div class="mt-2 text-muted fx-n2 d-flex flex-wrap">
                <?php foreach($artwork->hash_tags as $tag):?>
                    <span class="m-1">#<?=$tag?></span>
                <?php endforeach;?>
            </div>
            <div class="mt-4 textarea">
                <?= enc($artwork->content) ?>
            </div>
            <div class="mt-2">
                <?php if(user() && user()->id == $artwork->uid):?>
                    <button class="btn-filled" data-toggle="modal" data-target="#update-form">수정하기</button>
                    <a href="/delete/artworks/<?=$artwork->id?>" class="btn-bordered">삭제하기</a>
                <?php elseif(admin()):?>
                    <button class="btn-filled" data-toggle="modal" data-target="#delete-form">삭제하기</button>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<?php if(user() && !$artwork->reviewed && user()->id !== $artwork->uid):?>
<div class="container py-5">
    <form action="/insert/scores" method='post' class="border bg-light p-3 align-center">
        <input type="hidden" name="aid" value="<?=$artwork->id?>">
        <select name="score" id="score" class="fa text-red form-control" style="width: auto;">
            <option class="fa" value="5"><?= str_repeat("&#xf005;", 5) ?><?= str_repeat("&#xf006;", 0) ?></option>
            <option class="fa" value="4.5"><?= str_repeat("&#xf005;", 4) ?>&#xf089;<?= str_repeat("&#xf006;", 1 - 1) ?></option>
            <option class="fa" value="4"><?= str_repeat("&#xf005;", 4) ?><?= str_repeat("&#xf006;", 1) ?></option>
            <option class="fa" value="3.5"><?= str_repeat("&#xf005;", 3) ?>&#xf089;<?= str_repeat("&#xf006;", 2 - 1) ?></option>
            <option class="fa" value="3"><?= str_repeat("&#xf005;", 3) ?><?= str_repeat("&#xf006;", 2) ?></option>
            <option class="fa" value="2.5"><?= str_repeat("&#xf005;", 2) ?>&#xf089;<?= str_repeat("&#xf006;", 3 - 1) ?></option>
            <option class="fa" value="2"><?= str_repeat("&#xf005;", 2) ?><?= str_repeat("&#xf006;", 3) ?></option>
            <option class="fa" value="1.5"><?= str_repeat("&#xf005;", 1) ?>&#xf089;<?= str_repeat("&#xf006;", 4 - 1) ?></option>
            <option class="fa" value="1"><?= str_repeat("&#xf005;", 1) ?><?= str_repeat("&#xf006;", 4) ?></option>
            <option class="fa" value="0.5"><?= str_repeat("&#xf005;", 0) ?>&#xf089;<?= str_repeat("&#xf006;", 5 - 1) ?></option>
        </select>
        <button class="btn-filled">확인</button>
    </form>
</div>
<?php endif;?>

<div class="container py-5">
    <div class="border bg-light p-3 align-center">
        <img src="/uploads/<?=$writer->image?>" alt="이미지" width="80" height="80">
        <div class="ml-4">
            <div class="fx-4"><?=$writer->user_name?></div>
            <div class="fx-n1 text-muted">
                <?= $writer->user_email  ?>
                <span class="mx-1">·</span>
                <?= $writer->type == 'company' ? '기업' : '일반' ?>
            </div>
        </div>
    </div>      
</div>

<form action="/update/artworks/<?=$artwork->id?>" id="update-form" class="modal fade" method='post'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">수정하기</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>제목</label>
                    <input type="text" class="form-control" name="title" required value="<?=$artwork->title?>">
                </div>
                <div class="form-group">
                    <label>설명</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" required><?=$artwork->content?></textarea>
                </div>
                <div class="form-group">
                    <label>해시태그</label>
                    <div id="update_tags" data-name="hash_tags"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">수정 완료</button>
            </div>
        </div>
    </div>
</form>

<script>
    let module = new HashModule("#update_tags");
    module.tags = <?=json_encode($artwork->hash_tags)?>;
    module.update();
</script>

<form action="/delete-admin/artworks/<?=$artwork->id?>" id="delete-form" class="modal fade" method='post'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="fx-4">삭제하기</div>
            </div>
            <div class="modal-body">
                <label>삭제 사유</label>
                <textarea name="rm_reason" id="rm_reason" cols="30" rows="10" class="form-control" required></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">삭제 완료</button>
            </div>
        </div>
    </div>
</form>