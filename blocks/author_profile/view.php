<?php   defined('C5_EXECUTE') or die("Access Denied.");
$c = Concrete\Core\Page\Page::getCurrentPage();
/** @var \Concrete\Core\Utility\Service\Validation\Strings $stringValidator */
$stringValidator = Core::make('helper/validation/strings');
/** @var \Concrete\Core\User\UserInfo $ui */
if (isset($ui) && is_object($ui)) {
    $name = $ui->getAttribute('nick_name', 'display');
    $name = ($stringValidator->notempty($name)) ? $name : $ui->getUserName();
    ?>
    <aside class="author-profile" itemscope itemtype="http://schema.org/Person">
        <h5 class="author-profile__title"><?php echo t('Written by'); ?></h5>
            <div class="clearfix">
                <?php if (isset($displayProfilePicture) && $displayProfilePicture) { ?>
                <div class="author-profile__image">
                    <?php
                    $avatar = $ui->getUserAvatar();
                    $img = new \HtmlObject\Image();
                    echo $img->src($avatar->getPath())
                        ->itemprop('image')
                        ->alt($name);
                    ?>
                </div>
                <div class="author-profile__body">
                    <?php } ?>
                    <?php if (isset($displayBasicAttributes) && $displayBasicAttributes) {
                        $bio = $ui->getAttribute('bio', 'display');
                        if (isset($linkToProfilePage) && $linkToProfilePage && isset($publicProfileEnabled) && $publicProfileEnabled) { ?>
                            <p class="author-profile__name lead" itemprop="name"><a
                                    href="<?php echo $ui->getUserPublicProfileUrl(); ?>"
                                    itemprop="url"><?php echo h($name); ?></a></p>
                        <?php } elseif (isset($detailPage) && is_object($detailPage)) { ?>
                            <p class="author-profile__name lead" itemprop="name"><a
                                        href="<?= URL::to($detailPage, 'view_user_detail', $ui->getUserID()); ?>"
                                        itemprop="url"><?php echo h($name); ?></a></p>
                        <?php } else { ?>
                            <p class="author-profile__name lead" itemprop="name"><?php echo h($name); ?></p>
                        <?php } ?>
                        <?php echo $bio; ?>
                    <?php } ?>
                    <?php if (isset($displayProfileAttributes) && $displayProfileAttributes && is_array($publicProfileAttributes)) {
                        foreach ($publicProfileAttributes as $ak) {
                            ?>
                            <dl class="author-profile__attributes">
                                <?php if (!in_array($ak->getAttributeKeyHandle(), array('nick_name', 'bio'))) { ?>
                                    <dt><?php echo $ak->getAttributeKeyDisplayName(); ?></dt>
                                    <dd><?php echo $ui->getAttribute($ak, 'displaySanitized', 'display'); ?></dd>
                                <?php } ?>
                            </dl>
                        <?php }
                    } ?>
                    <?php if (isset($displayMemberListAttributes) && $displayMemberListAttributes && isset($memberListAttributes)) {
                        foreach ($memberListAttributes as $ak) {
                            ?>
                            <dl class="author-profile__attributes">
                                <?php if (!in_array($ak->getAttributeKeyHandle(), array('nick_name', 'bio'))) { ?>
                                    <dt><?php echo $ak->getAttributeKeyDisplayName(); ?></dt>
                                    <dd><?php echo $ui->getAttribute($ak, 'displaySanitized', 'display'); ?></dd>
                                <?php } ?>
                            </dl>
                        <?php }
                    } ?>
                    <?php if (isset($displayProfilePicture) && $displayProfilePicture) { ?>
                </div>
            <?php } ?>
            </div>
    </aside>
    <?php
} elseif ($c->isEditMode()) {
    ?>
    <div class="ccm-edit-mode-disabled-item"><?php echo t('Empty Author Profile Block.') ?></div>
    <?php
}