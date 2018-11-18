<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayBasicAttributes', 1, (isset($displayBasicAttributes)) ? $displayBasicAttributes : null); ?>
            <?php echo t('Display Basic User Attributes'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayProfileAttributes', 1, (isset($displayProfileAttributes)) ? $displayProfileAttributes : null); ?>
            <?php echo t('Display User Attributes for Public Profile'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayMemberListAttributes', 1, (isset($displayMemberListAttributes)) ? $displayMemberListAttributes : null); ?>
            <?php echo t('Display User Attributes for Member List'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayProfilePicture', 1, (isset($displayProfilePicture)) ? $displayProfilePicture : null); ?>
            <?php echo t('Display Profile Picture'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <?=$form->label('displayMode', t('Display Mode'))?>
    <?=$form->select('displayMode', [
        'S' => t('Display the author of current page.'),
        'E' => t('Get the user from list block on another page.'),
    ], (isset($displayMode)) ? $displayMode : null);
    ?>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('linkToProfilePage', 1, (isset($linkToProfilePage)) ? $linkToProfilePage : null); ?>
            <?php echo t('Link to Profile Page'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <label class="control-label" for="detailPage"><?=t("Link to Detail Page")?></label>
    <?=Core::make('helper/form/page_selector')->selectPage('detailPage', isset($detailPage) ? $detailPage : null)?>
</div>
<div class="form-group ccm-block-author-profile-list-option">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('hideNotViewableUser', 1, (isset($hideNotViewableUser)) ? $hideNotViewableUser : null); ?>
            <?php echo t('Hide not viewable in author list user'); ?>
        </label>
        <span class="help-block"><?= t("If you enable this option, this block won't display the user when the user's %sis_viewable_on_author_list%s attribute value is not true.", '<code>', '</code>'); ?></span>
    </div>
</div>
<script>
    $(function(){ author_profile.init(); });
</script>