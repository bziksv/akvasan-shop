<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?
global $USER;
?>

<div class="slider-card col-lg-6 col-md-6 col-xs-12 <? if($arResult['ACTIVE'] == 'N'):?>blur_detail<?endif; ?>">

	<? if($arResult['ACTIVE'] == 'N'):?>
		<div class="blur_in_detail">
			<span class="price">не поставляется</span>
		 </div>
	 <?endif; ?>

    <?if(is_array($arResult["DETAIL_PICTURE"])):?>

        <div class="bxslider">
            <?if (is_array($arResult["DETAIL_PICTURE"])) :
                $pic = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width' => 528, 'height' => 418), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                ?>
               <div class="slide-card"><a href="<?=$arResult["DETAIL_PICTURE"]['SRC']?>" data-fancybox="images"><img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"></a>
              </div>
               
            <?endif?>
            <?
            foreach ($arResult["MORE_PHOTO"] as $key => $photo) :
                $pic = CFile::ResizeImageGet($photo, array('width' => 528, 'height' => 418), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                ?>
                <div class="slide-card"><a  href="<?=$photo['SRC']?>" data-fancybox="images"><img src="<?=$photo['SRC']?>" alt="<?=$photo["alt"]?>"></a></div>
            <?endforeach;?>
        </div>

        <div class="mini-img">
            <div class="bx-pager-mini">
                <?if (is_array($arResult["DETAIL_PICTURE"])) :
                    $pic = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width' => 125, 'height' => 125), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    ?>
                    <a href="" data-slide-index="0"><img src="<?=$pic ["src"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"></a>
                <?endif?>
                
                <?foreach ($arResult["MORE_PHOTO"] as $key => $photo) :
                    $pic = CFile::ResizeImageGet($photo, array('width' => 125, 'height' =>125), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    ?>
                    <a href="" data-slide-index="<?=$key+1?>"><img src="<?=$pic['src']?>" alt="<?=$photo["alt"]?>"></a>
                <?endforeach;?>
            </div>
        </div>
    <?endif;?>
    <a href="" data-compare-action="add" data-compare-id="<?=$arResult["ID"]?>" class="arrow-slide hidden-lg hidden-md"></a>
</div>
