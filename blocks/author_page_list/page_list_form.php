<?php defined('C5_EXECUTE') or die('Access Denied.');
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;

/** @var Controller $controller */
$c = Page::getCurrentPage();
$app = Application::getFacadeApplication();
$form = $app->make('helper/form');
$num = $num ?? 0;
$ptID = $ptID ?? 0;
$displayFeaturedOnly = $displayFeaturedOnly ?? false;
$displayAliases = $displayAliases ?? false;
$paginate = $paginate ?? false;
$orderBy = $orderBy ?? null;
$truncateSummaries = $truncateSummaries ?? false;
$truncateChars = $truncateChars ?? 0;
$includeDate = $includeDate ?? false;
$displayThumbnail = $displayThumbnail ?? false;
$useButtonForLink = $useButtonForLink ?? false;
$buttonLinkText = $buttonLinkText ?? null;
$pageListTitle = $pageListTitle ?? null;
$noResultsMessage = $noResultsMessage ?? null;
?>
<div class="row authorpagelist-form">
    <div class="col-xs-6" style="width:50%;">
        
        <?php echo $form->hidden('current_page', $c->getCollectionID()); ?>
        <input type="hidden" name="pageListPreviewPane" value="<?= h($controller->getActionURL('preview_pane')) ?>"/>

        <fieldset>
            <legend><?= t('Settings') ?></legend>

            <div class="form-group">
                <?=$form->label('displayMode', t('Display Mode'))?>
                <?=$form->select('displayMode', [
                    'S' => t('Display pages written by the author of current page.'),
                    'E' => t('Enable other blocks to filter pages by the user.'),
                ], (isset($displayMode)) ? $displayMode : null);
                ?>
            </div>

            <div class="form-group ccm-block-author-page-list-option">
                <div class="checkbox">
                    <label>
                        <?php echo $form->checkbox('hideNotViewableUser', 1, (isset($hideNotViewableUser)) ? $hideNotViewableUser : null); ?>
                        <?php echo t('Hide not viewable in author list user'); ?>
                    </label>
                    <span class="help-block"><?= t("If you enable this option, this block won't display the pages written by the user when the user's %sis_viewable_on_author_list%s attribute value is not true.", '<code>', '</code>'); ?></span>
                </div>
            </div>

            <div class="form-group">
                <label class='control-label'><?= t('Number of Pages to Display') ?></label>
                <input type="text" name="num" value="<?= h($num) ?>" class="form-control">
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Page Type') ?></label>
                <?php
                $ctArray = PageType::getList();

                if (is_array($ctArray)) {
                    ?>
                    <select class="form-control" name="ptID" id="selectPTID">
                        <option value="0">** <?php echo t('All') ?> **</option>
                        <?php
                        foreach ($ctArray as $ct) {
                            ?>
                            <option
                                value="<?= $ct->getPageTypeID() ?>" <?php if ($ptID == $ct->getPageTypeID()) { ?> selected <?php } ?>>
                                <?= $ct->getPageTypeDisplayName() ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
            </div>
        </fieldset>

        <fieldset>
            <legend><?= t('Filters') ?></legend>

            <div class="form-check">
                <?php
                    $miscFields = [];
                    if (!is_object($featuredAttribute)) {
                        $miscFields['disabled'] = 'disabled';
                    }
                ?>
                <?php echo $form->checkbox('displayFeaturedOnly', '1', $displayFeaturedOnly, $miscFields); ?>
                <?php echo $form->label('displayFeaturedOnly', t('Featured pages only.'), ['class' => 'form-check-label']); ?>
                <?php if (!is_object($featuredAttribute)) { ?>
                    <span class="help-block"><?=
                            t(
                                    '(<strong>Note</strong>: You must create the "is_featured" page attribute first.)'); ?></span>
                <?php } ?>
            </div>
            
            <div class="form-check">
                <?php echo $form->checkbox('displayAliases', '1', $displayAliases); ?>
                <?php echo $form->label('displayAliases', t('Display page aliases.'), ['class' => 'form-check-label']); ?>
            </div>

        </fieldset>

        <fieldset>
            <legend><?= t('Pagination') ?></legend>
            <div class="form-check">
                <?php echo $form->checkbox('paginate', '1', $paginate); ?>
                <?php echo $form->label('paginate', t('Display pagination interface if more items are available than are displayed.'), ['class' => 'form-check-label']); ?>
            </div>
        </fieldset>

        <fieldset>
            <div class="form-group">
                <?php
                    echo $form->label('orderBy', t('Sort'));
                    echo $form->select('orderBy', [
                            'display_asc' => t('Sitemap order'),
                            'chrono_desc' => t('Most recent first'),
                            'chrono_asc' => t('Earliest first'),
                            'alpha_asc' => t('Alphabetical order'),
                            'alpha_desc' => t('Reverse alphabetical order'),
                            'random' => t('Random'),
                    ], $orderBy);
                ?>
            </div>
        </fieldset>

        <fieldset>
            <legend><?= t('Output') ?></legend>

            <div class="form-group">
                <label class="control-label"><?= t('Include Page Name') ?></label>
                <div class="form-check">
                    <?php echo $form->radio('includeName', '0', $includeName); ?>
                    <?php echo $form->label('includeName', t('No'), ['No' => 'form-check-label']); ?>
                </div>
                <div class="form-check">
                    <?php echo $form->radio('includeName', '1', $includeName); ?>
                    <?php echo $form->label('includeName', t('Yes'), ['No' => 'form-check-label']); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Include Page Description') ?></label>
                <div class="form-check">
                    <?php echo $form->radio('includeDescription', '0', $includeDescription); ?>
                    <?php echo $form->label('includeDescription', t('No'), ['No' => 'form-check-label']); ?>
                </div>
                <div class="form-check">
                    <?php echo $form->radio('includeDescription', '1', $includeDescription); ?>
                    <?php echo $form->label('includeDescription', t('Yes'), ['No' => 'form-check-label']); ?>
                </div>
                
                <div class="ccm-page-list-truncate-description" <?= ($includeDescription ? '' : 'style="display:none;"') ?>>
                    <label class="control-label"><?=t('Display Truncated Description')?></label>
                    <div class="input-group">
                <?php
                $config = $app->make('config');
                $codeVersion = $config->get('concrete.version');
                if (version_compare($codeVersion, '9.0.0', '<')) {
                    $class = "class='input-group-addon'";
                } else {
                    $class = "class='input-group-text'";
                }
                ?>
                <span <?=$class?>>
                    <input id="ccm-pagelist-truncateSummariesOn" name="truncateSummaries" type="checkbox"
                           value="1" <?= ($truncateSummaries ? 'checked="checked"' : '') ?> />
                </span>
                        <input class="form-control" id="ccm-pagelist-truncateChars" <?= ($truncateSummaries ? '' : 'disabled="disabled"') ?>
                               type="text" name="truncateChars" size="3" value="<?= (int) $truncateChars ?>" />
                <span <?=$class?>>
                    <?= t('characters') ?>
                </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Include Public Page Date') ?></label>

                <div class="form-check">
                    <?php echo $form->radio('includeDate', '0', $includeDate); ?>
                    <?php echo $form->label('includeDate', t('No'), ['No' => 'form-check-label']); ?>
                </div>
                <div class="form-check">
                    <?php echo $form->radio('includeDate', '1', $includeDate); ?>
                    <?php echo $form->label('includeDate', t('Yes'), ['No' => 'form-check-label']); ?>
                </div>
                
                <span class="help-block"><?=t('This is usually the date the page is created. It can be changed from the page attributes panel.')?></span>
            </div>
            <div class="form-group">
                <label class="control-label"><?= t('Display Thumbnail Image') ?></label>
                <?php
                    $miscFields = [];
                    if (!is_object($thumbnailAttribute)) {
                        $miscFields['disabled'] = 'disabled';
                    }
                ?>
                <div class="form-check">
                    <?php echo $form->radio('displayThumbnail', '0', $displayThumbnail, $miscFields); ?>
                    <?php echo $form->label('displayThumbnail', t('No'), ['No' => 'form-check-label']); ?>
                </div>
                <div class="form-check">
                    <?php echo $form->radio('displayThumbnail', '1', $displayThumbnail, $miscFields); ?>
                    <?php echo $form->label('displayThumbnail', t('Yes'), ['No' => 'form-check-label']); ?>
                </div>
                <?php if (!is_object($thumbnailAttribute)) { ?>
                    <div class="help-block">
                        <?=t('You must create an attribute with the \'thumbnail\' handle in order to use this option.')?>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Use Different Link than Page Name') ?></label>
                <div class="form-check">
                    <?php echo $form->radio('useButtonForLink', '0', $useButtonForLink); ?>
                    <?php echo $form->label('useButtonForLink', t('No'), ['No' => 'form-check-label']); ?>
                </div>
                <div class="form-check">
                    <?php echo $form->radio('useButtonForLink', '1', $useButtonForLink); ?>
                    <?php echo $form->label('useButtonForLink', t('Yes'), ['No' => 'form-check-label']); ?>
                </div>
                <div class="ccm-page-list-button-text" <?= ($useButtonForLink ? '' : 'style="display:none;"') ?>>
                    <div class="form-group">
                        <label class="control-label"><?= t('Link Text') ?></label>
                        <input class="form-control" type="text" name="buttonLinkText" value="<?=$buttonLinkText?>" maxlength="255" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Title of Page List') ?></label>
                <input type="text" class="form-control" name="pageListTitle" value="<?=$pageListTitle?>" maxlength="255" />
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Message to Display When No Pages Listed.') ?></label>
                <textarea class="form-control" name="noResultsMessage" maxlength="255"><?=$noResultsMessage?></textarea>
            </div>
            <fieldset>


                <div class="loader">
                    <i class="fa fa-cog fa-spin"></i>
                </div>
    </div>

    <div class="col-xs-6" id="ccm-tab-content-page-list-preview" style="width:50%;">
        <fieldset>
            <legend><?= t('Included Pages') ?></legend>
            <div class="preview">
                <div class="render">

                </div>
                <div class="cover"></div>
            </div>
        </fieldset>
    </div>

</div>

<style type="text/css">
    div.authorpagelist-form div.loader {
        position: absolute;
        line-height: 34px;
    }

    div.authorpagelist-form div.cover {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    div.authorpagelist-form div.render .ccm-page-list-title {
        font-size: 12px;
        font-weight: normal;
    }

    div.authorpagelist-form label.checkbox,
    div.authorpagelist-form label.radio {
        font-weight: 300;
    }

</style>
<script type="application/javascript">
    Concrete.event.publish('authorpagelist.edit.open');
</script>

