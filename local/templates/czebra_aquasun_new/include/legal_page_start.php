<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var string $legalTitle */
/** @var string|null $legalSubtitle */
?>
<div class="container-main legal-page col-lg-12 col-md-12 col-xs-12">
    <div class="legal-page__head">
        <h1 class="legal-page__title"><?=htmlspecialcharsbx($legalTitle)?></h1>
        <?php if (!empty($legalSubtitle)): ?>
            <p class="legal-page__subtitle"><?=htmlspecialcharsbx($legalSubtitle)?></p>
        <?php endif; ?>
    </div>
    <div class="legal-page__body">
