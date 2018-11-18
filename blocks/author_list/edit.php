<?php
defined('C5_EXECUTE') or die("Access Denied.");
/** @var \Concrete\Core\Form\Service\Form $form */
?>
<div class="form-group">
    <?= $form->label('authorListTitle', t('Title of Author List')); ?>
    <?= $form->text('authorListTitle', (isset($authorListTitle)) ? $authorListTitle : null); ?>
</div>
<div class="form-group">
    <?= $form->label('orderBy', t('Sort')); ?>
    <?= $form->select('orderBy', [
        'date_added' => t('Date Added'),
        'user_id' => t('User ID'),
        'user_name' => t('User Name')
    ], (isset($orderBy)) ? $orderBy : null); ?>
</div>
<div class="form-group ccm-block-author-profile-list-option">
    <label class="control-label" for="detailPage"><?=t("Link to Detail Page")?></label>
    <?=Core::make('helper/form/page_selector')->selectPage('detailPage', isset($detailPage) ? $detailPage : null)?>
</div>