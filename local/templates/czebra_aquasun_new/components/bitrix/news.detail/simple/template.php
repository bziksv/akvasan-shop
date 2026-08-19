<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<div class="container-main">
    <div class="title">
        <h1><?=$arResult["NAME"]?></h1>
    </div>

    <div class="container-text">
        <?=$arResult["DETAIL_TEXT"];?>
    </div>
    
</div>