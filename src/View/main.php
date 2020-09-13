    <!-- 비주얼 영역 -->
    <input type="checkbox" id="s-paused" hidden>
    <input type="radio" id="move-1-2" name="move" hidden>
    <input type="radio" id="move-1-3" name="move" hidden>
    <input type="radio" id="move-2-1" name="move" hidden>
    <input type="radio" id="move-2-3" name="move" hidden>
    <input type="radio" id="move-3-1" name="move" hidden checked>
    <input type="radio" id="move-3-2" name="move" hidden>
    <input type="radio" id="move-1-2-copy" name="move" hidden>
    <input type="radio" id="move-1-3-copy" name="move" hidden>
    <input type="radio" id="move-2-1-copy" name="move" hidden>
    <input type="radio" id="move-2-3-copy" name="move" hidden>
    <input type="radio" id="move-3-1-copy" name="move" hidden>
    <input type="radio" id="move-3-2-copy" name="move" hidden>
    <div class="visual wrap">
        <div class="visual__image">
            <div class="image"></div>
            <div class="image"></div>
            <div class="image"></div>
        </div>
        <div class="visual__box">
            <div class="box">
                <div class="box__item">전 주 한 지</div>
                <div class="box__item">온 누 리 에</div>
                <div class="box__item">펼 치 다</div>
            </div>
            <div class="box">
                <div class="box__item">천 년 을 뜨 고</div>
                <div class="box__item">천 년 을 잇 는</div>
                <div class="box__item">전 주 한 지</div>
            </div>
            <div class="box">
                <div class="box__item">전 주 한 지 로</div>
                <div class="box__item">희 망 을</div>
                <div class="box__item">나 누 다</div>
            </div>
        </div>
        <div class="visual__control">
            <div class="control">
                <label for="move-2-1" class="label-2"></label>
                <label for="move-2-1-copy" class="label-2-copy"></label>
                <label for="move-3-1" class="label-3"></label>
                <label for="move-3-1-copy" class="label-3-copy"></label>
            </div>
            <div class="control">
                <label for="move-1-2" class="label-1"></label>
                <label for="move-1-2-copy" class="label-1-copy"></label>
                <label for="move-3-2" class="label-3"></label>
                <label for="move-3-2-copy" class="label-3-copy"></label>
            </div>
            <div class="control">
                <label for="move-1-3" class="label-1"></label>
                <label for="move-1-3-copy" class="label-1-copy"></label>
                <label for="move-2-3" class="label-2"></label>
                <label for="move-2-3-copy" class="label-2-copy"></label>
            </div>
            <label for="s-paused" class="icon text-red">
                <i class="fa fa-play"></i>
                <i class="fa fa-pause"></i>
            </label>
        </div>
    </div>
    <!-- /비주얼 영역 -->
    <!-- 축제소개 & 알려드립니다 -->
    <div class="wrap px-0">
        <div class="line">
            <div class="line__text">
                <div>
                    <span class="title bar-right text-red border-red">축제 소개</span>
                    <div class="text-title mt-4">전주, <strong>한지로 꽃피다</strong></div>
                    <div class="textarea mt-4 auto-line">천 년 한지 이야기로 여러분을 초대합니다.
                        1997년부터 시작되어 우리 한지를 널리 알리는 전주한지문화축제
                        온라인으로 사랑하는 친구, 연인, 가족과 함께 꿈 같은 축제를 경험해보세요.</div>
                    <div class="row flex-row-reverse mt-5">
                        <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                            <a href="/artworks" class="align-center flex-column text-red">
                                <span class="icon"><i class="fa fa-yelp fa-lg"></i></span>
                                <span class="fx-n2 mt-2">참가 작품</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                            <a href="/entry" class="align-center flex-column text-red">
                                <span class="icon"><i class="fa fa-send fa-lg"></i></span>
                                <span class="fx-n2 mt-2">출품 신청</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                            <a href="/store" class="align-center flex-column text-red">
                                <span class="icon"><i class="fa fa-shopping-cart fa-lg"></i></span>
                                <span class="fx-n2 mt-2">한지 스토어</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                            <a href="/roadmap" class="align-center flex-column text-red">
                                <span class="icon"><i class="fa fa-map fa-lg"></i></span>
                                <span class="fx-n2 mt-2">오시는 길</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-4 mb-lg-0">
                            <a href="/intro" class="align-center flex-column text-red">
                                <span class="icon"><i class="fa fa-search fa-lg"></i></span>
                                <span class="fx-n2 mt-2">축제개요</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="line__image">
                <img src="/images/1.jpg" alt="축제 소개" title="축제 소개">
            </div>
        </div>
        <div class="line">
            <div class="line__text">
                <div>
                    <div class="d-between">
                        <span class="title bar-left text-yellow border-yellow">알려드립니다</span>
                        <a href="/notices" class="fx-n2 text-yellow">더 보기+</a>
                    </div>
                    <?php if(count($notices) > 0):?>
                        <?php $f = $notices[0];?>
                    <div class="text-title mt-4"><strong><?=enc($f->title)?></strong></div>
                    <div class="mt-3 fx-n2 text-muted"><?=dt($f->created_at)?></div>
                    <div class="textarea mt-4 text-ellipsis"><?=enc($f->content)?></div>
                    <div class="border-top mt-4">
                        <?php foreach(array_slice($notices, 1, 4) as $notice):?>
                        <div class="t-row">
                            <div class="cell-80 text-left text-ellipsis fx-n1" title="<?=enc($notice->title)?>">
                                <?=enc($notice->title)?>
                            </div>
                            <div class="cell-20 text-muted fx-n2"><?=dt($notice->created_at)?></div>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="line__image">
                <img src="/images/2.jpg" alt="알려드립니다" title="알려드립니다">
            </div>
        </div>
    </div>
    <!-- /축제소개 & 알려드립니다 -->

    <!-- 공예대전 갤러리 -->
    <div class="position-relative">
        <div class="background background--red">
            <img src="/images/back.jpg" alt="배경 이미지" title="배경 이미지">
        </div>
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                    <div class="d-between flex-column align-items-start h-100">
                        <div class="mb-5">
                            <span class="title bar-left text-gray border-gray">공예대전 갤러리</span>
                            <div class="text-title text-white mt-4">온라인으로 함께
                                <strong>한지공예대전</strong></div>
                        </div>
                        <a href="/artworks" class="btn-custom btn-custom--white">Read More +</a>
                    </div>
                </div>
                
                <?php foreach(array_slice($artworks, 0, 3) as $artwork):?>
                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                    <div class="accent--image">
                        <img class="bg-white fit-contain p-3 hx-200" src="/uploads/<?=$artwork->image?>" alt="갤러리 이미지" title="갤러리 이미지">
                        <div class="p-3 bg-white">
                            <div class="fx-3"><?=enc($artwork->title)?></div>
                            <div class="mt-2">
                                <span class="fx-n2 text-muted">제작자</span>
                                <span class="ml-2 fx-n1"><?=$artwork->user_name?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
    <!-- /공예대전 갤러리 -->
    <!-- 후원사 -->
    <div class="container py-5">
        <input type="checkbox" id="c-paused" hidden>
        <div class="row">
            <div class="col-lg-8 order-1 order-lg-0 mt-4 mt-lg-0">
                <div class="company">
                    <img src="/images/company/2.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/3.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/4.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/5.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/6.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/7.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/8.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/9.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/10.jpg" alt="후원사" title="후원사">
                    <img src="/images/company/11.jpg" alt="후원사" title="후원사">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-right">
                    <span class="title bar-right">축제 후원사</span>
                    <div class="text-title mt-4"><strong>전주한지문화축제</strong>와
                        함께하는 기업</div>
                    <div class="mt-4 d-lg-none text-red">
                        <label for="c-paused">
                            <i class="fa fa-play"></i>
                            <i class="fa fa-pause"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /후원사 -->
