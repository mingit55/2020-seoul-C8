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
    <form class="bg-light border p-2 d-center">
        <div id="search_tags" data-name="searches" class="w-100">
            
        </div>
        <button class="btn-search icon ml-3 text-red"><i class="fa fa-search"></i></button>
    </form>
</div>
<script>
    let module = new HashModule("#search_tags", <?=json_encode($tags)?>);
    module.tags = <?=json_encode($searches)?>;
    module.update();
</script>


<div class="container py-5">
    <div class="mb-3 pb-3 border-bottom">
        <div class="title bar-left text-red border-red">등록한 작품</div>
    </div>
    <div class="row">
        <?php foreach($myList as $artwork):?>
        <div class="col-lg-3">
            <div class="border bg-white" onclick="location.href='/artworks/<?=$artwork->id?>'" <?=$artwork->rm_reason ? 'disabled': ''?>>
                <img src="/uploads/<?=$artwork->image?>" alt="이미지" class="fit-contain hx-200 p-2">
                <div class="p-3 border-top">
                    <div class="fx-3"><?=enc($artwork->title)?></div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">작성자</span>
                        <span class="fx-n1 ml-2"><?=$artwork->user_name?></span>
                        <span class="badge badge-primary"><?=$artwork->type == 'company' ?'기업' : '일반'?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작일자</span>
                        <span class="fx-n1 ml-2"><?=dt($artwork->created_at)?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">평점</span>
                        <span class="fx-n1 ml-2"><?=($artwork->score)?></span>
                    </div>
                    <div class="mt-2 text-muted d-flex flex-wrap fx-n2">
                        <?php foreach($artwork->hash_tags as $tag):?>
                            <span class="m-1">#<?=$tag?></span>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <?php if($artwork->rm_reason):?>
                <div class="border">
                    <div class="fx-n1">삭제 사유</div>
                    <div class="textarea"><?=enc($artwork->rm_reason)?></div>
                </div>
            <?php endif;?>
        </div>
        <?php endforeach;?>
    </div>
</div>

<div class="container py-5">
    <div class="mb-3 pb-3 border-bottom">
        <div class="title bar-left text-red border-red">우수 작품</div>
    </div>
    <div class="row">
        <?php foreach($rankers as $artwork):?>
        <div class="col-lg-3">
            <div class="border bg-white" onclick="location.href='/artworks/<?=$artwork->id?>'">
                <img src="/uploads/<?=$artwork->image?>" alt="이미지" class="fit-contain hx-200 p-2">
                <div class="p-3 border-top">
                    <div class="fx-3"><?=enc($artwork->title)?></div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">작성자</span>
                        <span class="fx-n1 ml-2"><?=$artwork->user_name?></span>
                        <span class="badge badge-primary"><?=$artwork->type == 'company' ?'기업' : '일반'?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작일자</span>
                        <span class="fx-n1 ml-2"><?=dt($artwork->created_at)?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">평점</span>
                        <span class="fx-n1 ml-2"><?=($artwork->score)?></span>
                    </div>
                    <div class="mt-2 text-muted d-flex flex-wrap fx-n2">
                        <?php foreach($artwork->hash_tags as $tag):?>
                            <span class="m-1">#<?=$tag?></span>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>

<div class="container py-5">
    <div class="mb-3 pb-3 border-bottom">
        <div class="title bar-left text-red border-red">모든 작품</div>
    </div>
    <div class="row">
        <?php foreach($artworks->data as $artwork):?>
        <div class="col-lg-3">
            <div class="border bg-white" onclick="location.href='/artworks/<?=$artwork->id?>'">
                <img src="/uploads/<?=$artwork->image?>" alt="이미지" class="fit-contain hx-200 p-2">
                <div class="p-3 border-top">
                    <div class="fx-3"><?=enc($artwork->title)?></div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">작성자</span>
                        <span class="fx-n1 ml-2"><?=$artwork->user_name?></span>
                        <span class="badge badge-primary"><?=$artwork->type == 'company' ?'기업' : '일반'?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">제작일자</span>
                        <span class="fx-n1 ml-2"><?=dt($artwork->created_at)?></span>
                    </div>
                    <div class="mt-2">
                        <span class="fx-n2 text-muted">평점</span>
                        <span class="fx-n1 ml-2"><?=($artwork->score)?></span>
                    </div>
                    <div class="mt-2 text-muted d-flex flex-wrap fx-n2">
                        <?php foreach($artwork->hash_tags as $tag):?>
                            <span class="m-1">#<?=$tag?></span>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <div class="d-center mt-5">
        <a href="/artworks?page=<?=$artworks->prevPage?>" class="icon mx-1 bg-red text-white" <?=!$artworks->prev ? "disabled" : ""?>>
            <i class="fa fa-angle-left"></i>
        </a>
        <?php for($i = $artworks->start; $i <= $artworks->end; $i++):?>
            <a href="/artworks?page=<?=$i?>" class="icon mx-1 <?=$i == $artworks->page ? 'bg-red text-white' : 'border border-red text-red'?>"><?=$i?></a>
        <?php endfor;?>
        <a href="/artworks?page=<?=$artworks->nextPage?>" class="icon mx-1 bg-red text-white <?=!$artworks->next ? "disabled": ""?>">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>
