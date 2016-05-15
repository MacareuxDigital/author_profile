<?php   defined('C5_EXECUTE') or die("Access Denied.");
$stringValidator = Core::make('helper/validation/strings');
$name = $ui->getAttribute('nick_name', 'display');
$name = ($stringValidator->notempty($name)) ? $name : $ui->getUserName();
?>
<div class="author-profile" itemscope itemtype="http://schema.org/Person">
    <h5 class="author-profile__title"><?php echo t('Written by'); ?></h4>
    <div class="clearfix">
        <?php if ($displayProfilePicture) { ?>
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
            <?php if ($displayBasicAttributes) {
                $bio = $ui->getAttribute('bio', 'display');
                if ($linkToProfilePage && $publicProfileEnabled) { ?>
                    <p class="author-profile__name lead" itemprop="name"><a href="<?php echo $ui->getUserPublicProfileUrl(); ?>" itemprop="url"><?php echo h($name); ?></a></p>
                <?php } else { ?>
                    <p class="author-profile__name lead" itemprop="name"><?php echo h($name); ?></p>
                <?php } ?>
                <?php echo $bio; ?>
            <?php } ?>
            <?php if ($displayProfileAttributes) {
                foreach($publicProfileAttributes as $ak) {
                    ?>
                    <dl class="author-profile__attributes">
                        <?php if (!in_array($ak->getAttributeKeyHandle(), array('nick_name', 'bio'))) { ?>
                            <dt><?php echo $ak->getAttributeKeyDisplayName(); ?></dt>
                            <dd><?php echo $ui->getAttribute($ak, 'displaySanitized', 'display'); ?></dd>
                        <?php } ?>
                    </dl>
                <?php }
            } ?>
            <?php if ($displayMemberListAttributes) {
                foreach($memberListAttributes as $ak) {
                    ?>
                    <dl class="author-profile__attributes">
                        <?php if (!in_array($ak->getAttributeKeyHandle(), array('nick_name', 'bio'))) { ?>
                            <dt><?php echo $ak->getAttributeKeyDisplayName(); ?></dt>
                            <dd><?php echo $ui->getAttribute($ak, 'displaySanitized', 'display'); ?></dd>
                        <?php } ?>
                    </dl>
                <?php }
            } ?>
        <?php if ($displayProfilePicture) { ?>
            </div>
        <?php } ?>
    </div>
</div>

