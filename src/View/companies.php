<!-- 비주얼 영역 -->
<div class="position-relative hx-350">
    <div class="background background--black">
        <img src="/images/visual/1.jpg" alt="비주얼 이미지" title="비주얼 이미지">
    </div>
    <div class="position-center text-center w-100">
        <div class="fx-8 text-white">한지업체</div>
        <div class="fx-4 text-gray mt-3">한지상품판매관 > 한지업체</div>
    </div>
</div>
<!-- /비주얼 영역 -->

<div class="container py-5">
    <div class="mb-3 pb-3 border-bottom">
        <div class="title bar-left text-red border-red">우수 업체</div>
    </div>
    <div class="row">
        <?php foreach($rankers as $company):?>
            <div class="col-lg-3">
                <div class="border bg-white">
                    <img src="/uploads/<?=$company->image?>" alt="이미지" class="fit-contain hx-200 p-2">
                    <div class="p-3 border-top">
                        <div class="fx-3"><?=$company->user_name?></div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">이메일</span>
                            <span class="fx-n1 ml-2"><?=$company->user_email?></span>
                        </div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">누적포인트</span>
                            <span class="fx-n1 ml-2"><?=$company->totalPoint?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
<div class="container py-5">
    <div class="mb-3 pb-3 border-bottom">
        <div class="title bar-left text-red border-red">모든 업체</div>
    </div>
    <div class="row">
        <?php foreach($companies->data as $company):?>
            <div class="col-lg-3">
                <div class="border bg-white">
                    <img src="/uploads/<?=$company->image?>" alt="이미지" class="fit-contain hx-200 p-2">
                    <div class="p-3 border-top">
                        <div class="fx-3"><?=$company->user_name?></div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">이메일</span>
                            <span class="fx-n1 ml-2"><?=$company->user_email?></span>
                        </div>
                        <div class="mt-2">
                            <span class="fx-n2 text-muted">누적포인트</span>
                            <span class="fx-n1 ml-2"><?=$company->totalPoint?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="d-center mt-5">
        <a href="/companies?page=<?=$companies->prevPage?>" class="icon mx-1 bg-red text-white" <?=!$companies->prev ? "disabled" : ""?>>
            <i class="fa fa-angle-left"></i>
        </a>
        <?php for($i = $companies->start; $i <= $companies->end; $i++):?>
            <a href="/companies?page=<?=$i?>" class="icon mx-1 <?=$i == $companies->page ? 'bg-red text-white' : 'border border-red text-red'?>"><?=$i?></a>
        <?php endfor;?>
        <a href="/companies?page=<?=$companies->nextPage?>" class="icon mx-1 bg-red text-white <?=!$companies->next ? "disabled": ""?>">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>