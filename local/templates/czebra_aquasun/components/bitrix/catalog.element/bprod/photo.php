<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<div class="product-img col-lg-6 col-md-6 col-xs-12">

    <?if(is_array($arResult["DETAIL_PICTURE"])):?>
        <div class="mini-img mini-img-slider col-lg-2 col-md-3">
            <div class="bx-pager-mini">
                <?if (is_array($arResult["DETAIL_PICTURE"])) :
                    $pic = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_EXACT, true);
                    ?>
                    <a href="" data-slide-index="0"><img src="<?=$pic ["src"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"></a>
                <?endif?>
                <?
                foreach ($arResult["MORE_PHOTO"] as $key => $photo) :
                    $pic = CFile::ResizeImageGet($photo, array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_EXACT, true);
                    ?>
                    <a href="" data-slide-index="<?=$key+1?>"><img src="<?=$pic['src']?>" alt="<?=$photo["alt"]?>"></a>
                <?endforeach;?>
            </div>
        </div>
        <div class="big-img col-lg-10 col-md-9 col-xs-12">
            <ul class="bxslider">
                <?if (is_array($arResult["DETAIL_PICTURE"])) :
                    $pic = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width' => 470, 'height' => 470), BX_RESIZE_IMAGE_EXACT, true);
                    ?>
                    <li class="active"><a data-fancybox="gallery" href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"><img src="<?=$pic ["src"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>" class="img-responsive"></a></li><?/*470*/?>
                <?endif?>
                <?
                foreach ($arResult["MORE_PHOTO"] as $key => $photo) :
                    $pic = CFile::ResizeImageGet($photo, array('width' => 470, 'height' => 470), BX_RESIZE_IMAGE_EXACT, true);
                    ?>
                    <li><a data-fancybox="gallery" href="<?=$photo["SRC"]?>"><img src="<?=$pic['src']?>" alt="<?=$photo["alt"]?>"></a></li>
                <?endforeach;?>
            </ul>
        </div>

        <div class="mini-img-mob col-xs-12">
            <div class="bx-pager-mini">
                <?if (is_array($arResult["DETAIL_PICTURE"])) :
                $pic = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_EXACT, true);
                ?>
                <a href="" data-slide-index="0"><img src="<?=$pic ["src"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"></a>
                <?endif?>
                <?
                foreach ($arResult["MORE_PHOTO"] as $key => $photo) :
                $pic = CFile::ResizeImageGet($photo, array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_EXACT, true);
                ?>
                <a href="" data-slide-index="<?=$key+1?>"><img src="<?=$pic['src']?>" alt="<?=$photo["alt"]?>"></a>
                <?endforeach;?>
            </div>
        </div>
    <?else:?>
        <div class="wrap-image-no-photo"><img src="/upload/template/no_photo.png" alt="<?=$arResult["NAME"]?>" /></div>
    <?endif?>

</div>
