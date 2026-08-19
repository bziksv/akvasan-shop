<? use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if ($arResult['CATEGORY_TAGS']) { ?>
    <div class="sotbit-seometa-tags-common-wrapper">
        <div class="sotbit-seometa-tags-wrapper" id="tagsWrapper">
            <?
            $i = 1;
            foreach ($arResult['CATEGORY_TAGS'] as $cat_tag) {
                ?>
                <div class="sotbit-seometa-tags-wrapper-category <?= $i > $arParams['CNT_TAGS'] ? 'hide-tag' : '' ?>">
                    <span class="sotbit-seometa-tags-padding"><?= $cat_tag['TITLE'] ?>: </span>
                    <? foreach ($cat_tag['ITEMS'] as $item) {
                        $count = $arParams['PRODUCT_COUNT'] == 'Y' ? ' (' . $item['PRODUCT_COUNT'] . ')' : ''; ?>
                        <div class="sotbit-seometa-tags-padding <?= $i > $arParams['CNT_TAGS'] ? 'hide-tag' : '' ?>">
                            <a class="sotbit-seometa-tag-link" href="<?= $item['URL'] ?>"
                               title="<?= $item['TITLE'] . $count ?>">
                                <?= $item['TITLE'] . $count ?>
                            </a>
                        </div>
                        <?
                        $i++;
                    }
                    ?>
                </div>
            <? } ?>
        </div>
        <div class="sotbit-seometa-btn-more <?= ($i - 1) > $arParams['CNT_TAGS'] ? 'show-btn' : '' ?>">
            <a class="sotbit-seometa-tag-link" id="showMoreButton"
               href="javascript:void(0);"><?= Loc::getMessage('SEO_META_TAGS_MORE') ?></a>
        </div>
    </div>
    <?
}
?>

<script>
    BX.message({
        'more_btn': '<?=Loc::getMessage('SEO_META_TAGS_MORE')?>',
        'less_btn': '<?=Loc::getMessage('SEO_META_TAGS_LESS')?>',
    });

    new CategoryTags({
        tagsWrapper: 'tagsWrapper',
        showMoreButton: 'showMoreButton',
        categoryTags: '.sotbit-seometa-tags-wrapper-category',
        hideTag: '.hide-tag',
        expanded: 'expanded',
        cntTags: <?= (int) $arParams['CNT_TAGS'] ?>,
    });
</script>
