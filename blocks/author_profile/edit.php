<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayBasicAttributes', 1, $displayBasicAttributes); ?>
            <?php echo t('Display Basic User Attributes'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayProfileAttributes', 1, $displayProfileAttributes); ?>
            <?php echo t('Display User Attributes for Public Profile'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayMemberListAttributes', 1, $displayMemberListAttributes); ?>
            <?php echo t('Display User Attributes for Member List'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('displayProfilePicture', 1, $displayProfilePicture); ?>
            <?php echo t('Display Profile Picture'); ?>
        </label>
    </div>
</div>
<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('linkToProfilePage', 1, $linkToProfilePage); ?>
            <?php echo t('Link to Profile Page'); ?>
        </label>
    </div>
</div>