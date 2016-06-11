<?php defined('C5_EXECUTE') or die("Access Denied.");
$c = Page::getCurrentPage();
$form = Core::make('helper/form');
?>
<div class="row authorpagelist-form">
    <div class="col-xs-6">

        <?php echo $form->hidden('current_page', $c->getCollectionID()); ?>
        <?php echo $form->hidden('pageListToolsDir', $uh->getBlockTypeToolsURL($bt)); ?>

        <fieldset>
            <legend><?= t('Settings') ?></legend>

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
            <div class="checkbox">
                <label>
                    <input <?php if (!is_object($featuredAttribute)) { ?> disabled <?php } ?> type="checkbox" name="displayFeaturedOnly"
                                                                                        value="1" <?php if ($displayFeaturedOnly == 1) { ?> checked <?php } ?>
                                                                                        style="vertical-align: middle"/>
                    <?= t('Featured pages only.') ?>
                </label>
                <?php if (!is_object($featuredAttribute)) { ?>
                    <span class="help-block"><?=
                        t(
                            '(<strong>Note</strong>: You must create the "is_featured" page attribute first.)'); ?></span>
                <?php } ?>
            </div>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="displayAliases"
                           value="1" <?php if ($displayAliases == 1) { ?> checked <?php } ?> />
                    <?= t('Display page aliases.') ?>
                </label>
            </div>

        </fieldset>

        <fieldset>
            <legend><?= t('Pagination') ?></legend>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="paginate" value="1" <?php if ($paginate == 1) { ?> checked <?php } ?> />
                    <?= t('Display pagination interface if more items are available than are displayed.') ?>
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend><?= t('Sort') ?></legend>
            <div class="form-group">
                <select name="orderBy" class="form-control">
                    <option value="display_asc" <?php if ($orderBy == 'display_asc') { ?> selected <?php } ?>>
                        <?= t('Sitemap order') ?>
                    </option>
                    <option value="chrono_desc" <?php if ($orderBy == 'chrono_desc') { ?> selected <?php } ?>>
                        <?= t('Most recent first') ?>
                    </option>
                    <option value="chrono_asc" <?php if ($orderBy == 'chrono_asc') { ?> selected <?php } ?>>
                        <?= t('Earliest first') ?>
                    </option>
                    <option value="alpha_asc" <?php if ($orderBy == 'alpha_asc') { ?> selected <?php } ?>>
                        <?= t('Alphabetical order') ?>
                    </option>
                    <option value="alpha_desc" <?php if ($orderBy == 'alpha_desc') { ?> selected <?php } ?>>
                        <?= t('Reverse alphabetical order') ?>
                    </option>
                    <option value="random" <?php if ($orderBy == 'random') { ?> selected <?php } ?>>
                        <?= t('Random') ?>
                    </option>
                </select>
            </div>
        </fieldset>

        <fieldset>
            <legend><?= t('Output') ?></legend>

            <div class="form-group">
                <label class="control-label"><?= t('Include Page Name') ?></label>
                <div class="radio">
                    <label>
                        <input type="radio" name="includeName"
                               value="0" <?= ($includeName ? "" : "checked=\"checked\"") ?>/> <?= t('No') ?>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="includeName"
                               value="1" <?= ($includeName ? "checked=\"checked\"" : "") ?>/> <?= t('Yes') ?>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Include Page Description') ?></label>
                <div class="radio">
                    <label>
                        <input type="radio" name="includeDescription"
                               value="0" <?= ($includeDescription ? "" : "checked=\"checked\"") ?>/> <?= t('No') ?>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="includeDescription"
                               value="1" <?= ($includeDescription ? "checked=\"checked\"" : "") ?>/> <?= t('Yes') ?>
                    </label>
                </div>
                <div class="ccm-page-list-truncate-description" <?= ($includeDescription ? "" : "style=\"display:none;\"") ?>>
                    <label class="control-label"><?=t('Display Truncated Description')?></label>
                    <div class="input-group">
                <span class="input-group-addon">
                    <input id="ccm-pagelist-truncateSummariesOn" name="truncateSummaries" type="checkbox"
                           value="1" <?= ($truncateSummaries ? "checked=\"checked\"" : "") ?> />
                </span>
                        <input class="form-control" id="ccm-pagelist-truncateChars" <?= ($truncateSummaries ? "" : "disabled=\"disabled\"") ?>
                               type="text" name="truncateChars" size="3" value="<?= intval($truncateChars) ?>" />
                <span class="input-group-addon">
                    <?= t('characters') ?>
                </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Include Public Page Date') ?></label>
                <div class="radio">
                    <label>
                        <input type="radio" name="includeDate"
                               value="0" <?= ($includeDate ? "" : "checked=\"checked\"") ?>/> <?= t('No') ?>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="includeDate"
                               value="1" <?= ($includeDate ? "checked=\"checked\"" : "") ?>/> <?= t('Yes') ?>
                    </label>
                </div>
                <span class="help-block"><?=t('This is usually the date the page is created. It can be changed from the page attributes panel.')?></span>
            </div>
            <div class="form-group">
                <label class="control-label"><?= t('Display Thumbnail Image') ?></label>
                <div class="radio">
                    <label>
                        <input type="radio" name="displayThumbnail"
                            <?= (!is_object($thumbnailAttribute) ? 'disabled ' : '')?>
                               value="0" <?= ($displayThumbnail ? "" : "checked=\"checked\"") ?>/> <?= t('No') ?>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="displayThumbnail"
                            <?= (!is_object($thumbnailAttribute) ? 'disabled ' : '')?>
                               value="1" <?= ($displayThumbnail ? "checked=\"checked\"" : "") ?>/> <?= t('Yes') ?>
                    </label>
                </div>
                <?php if (!is_object($thumbnailAttribute)) { ?>
                    <div class="help-block">
                        <?=t('You must create an attribute with the \'thumbnail\' handle in order to use this option.')?>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Use Different Link than Page Name') ?></label>
                <div class="radio">
                    <label>
                        <input type="radio" name="useButtonForLink"
                               value="0" <?= ($useButtonForLink ? "" : "checked=\"checked\"") ?>/> <?= t('No') ?>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="useButtonForLink"
                               value="1" <?= ($useButtonForLink ? "checked=\"checked\"" : "") ?>/> <?= t('Yes') ?>
                    </label>
                </div>
                <div class="ccm-page-list-button-text" <?= ($useButtonForLink ? "" : "style=\"display:none;\"") ?>>
                    <div class="form-group">
                        <label class="control-label"><?= t('Link Text') ?></label>
                        <input class="form-control" type="text" name="buttonLinkText" value="<?=$buttonLinkText?>" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Title of Page List') ?></label>
                <input type="text" class="form-control" name="pageListTitle" value="<?=$pageListTitle?>" />
            </div>

            <div class="form-group">
                <label class="control-label"><?= t('Message to Display When No Pages Listed.') ?></label>
                <textarea class="form-control" name="noResultsMessage"><?=$noResultsMessage?></textarea>
            </div>
            <fieldset>


                <div class="loader">
                    <i class="fa fa-cog fa-spin"></i>
                </div>
    </div>

    <div class="col-xs-6" id="ccm-tab-content-page-list-preview">
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
        position: absolute;
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

