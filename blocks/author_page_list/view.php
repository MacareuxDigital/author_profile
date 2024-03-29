<?php
defined('C5_EXECUTE') or die('Access Denied.');
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;

$app = Application::getFacadeApplication();
$th = $app->make('helper/text');
$c = Page::getCurrentPage();
$dh = $app->make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */
$displayThumbnail = $displayThumbnail ?? false;
?>

<?php if (is_object($c) && $c->isEditMode() && $controller->isBlockEmpty()) { ?>
    <div class="ccm-edit-mode-disabled-item"><?=t('Empty Authors Pages Block.')?></div>
<?php } else { ?>

    <div class="ccm-block-page-list-wrapper ccm-block-author-page-list-wrapper">

        <?php if (isset($pageListTitle) && $pageListTitle): ?>
            <div class="ccm-block-page-list-header">
                <h5><?=h($pageListTitle)?></h5>
            </div>
        <?php endif; ?>

        <?php if (isset($rssUrl) && $rssUrl): ?>
            <a href="<?php echo $rssUrl ?>" target="_blank" class="ccm-block-page-list-rss-feed"><i class="fa fa-rss"></i></a>
        <?php endif; ?>

        <div class="ccm-block-page-list-pages ccm-block-author-page-list-pages">

            <?php

            $includeEntryText = false;
            if (
                (isset($includeName) && $includeName)
                ||
                (isset($includeDescription) && $includeDescription)
                ||
                (isset($useButtonForLink) && $useButtonForLink)
            ) {
                $includeEntryText = true;
            }

            foreach ($pages as $page):

                // Prepare data for each page being listed...
                $buttonClasses = 'ccm-block-page-list-read-more';
                $entryClasses = 'ccm-block-page-list-page-entry';
                $title = $th->entities($page->getCollectionName());
                $url = ($page->getCollectionPointerExternalLink() != '') ? $page->getCollectionPointerExternalLink() : $nh->getLinkToCollection($page);
                $target = ($page->getCollectionPointerExternalLink() != '' && $page->openCollectionPointerExternalLinkInNewWindow()) ? '_blank' : $page->getAttribute('nav_target');
                $target = empty($target) ? '_self' : $target;
                $description = $page->getCollectionDescription();
                $description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
                $description = $th->entities($description);
                $thumbnail = false;
                if ($displayThumbnail) {
                    $thumbnail = $page->getAttribute('thumbnail');
                }
                if (is_object($thumbnail) && $includeEntryText) {
                    $entryClasses = 'ccm-block-page-list-page-entry-horizontal';
                }

                $date = $dh->formatDateTime($page->getCollectionDatePublic(), true);


                //Other useful page data...


                //$last_edited_by = $page->getVersionObject()->getVersionAuthorUserName();

                //$original_author = Page::getByID($page->getCollectionID(), 1)->getVersionObject()->getVersionAuthorUserName();

                /* CUSTOM ATTRIBUTE EXAMPLES:
                 * $example_value = $page->getAttribute('example_attribute_handle');
                 *
                 * HOW TO USE IMAGE ATTRIBUTES:
                 * 1) Uncomment the "$ih = Loader::helper('image');" line up top.
                 * 2) Put in some code here like the following 2 lines:
                 *      $img = $page->getAttribute('example_image_attribute_handle');
                 *      $thumb = $ih->getThumbnail($img, 64, 9999, false);
                 *    (Replace "64" with max width, "9999" with max height. The "9999" effectively means "no maximum size" for that particular dimension.)
                 *    (Change the last argument from false to true if you want thumbnails cropped.)
                 * 3) Output the image tag below like this:
                 *		<img src="<?php echo $thumb->src ?>" width="<?php echo $thumb->width ?>" height="<?php echo $thumb->height ?>" alt="" />
                 *
                 * ~OR~ IF YOU DO NOT WANT IMAGES TO BE RESIZED:
                 * 1) Put in some code here like the following 2 lines:
                 * 	    $img_src = $img->getRelativePath();
                 *      $img_width = $img->getAttribute('width');
                 *      $img_height = $img->getAttribute('height');
                 * 2) Output the image tag below like this:
                 * 	    <img src="<?php echo $img_src ?>" width="<?php echo $img_width ?>" height="<?php echo $img_height ?>" alt="" />
                 */

                /* End data preparation. */

                /* The HTML from here through "endforeach" is repeated for every item in the list... */ ?>

                <div class="<?=$entryClasses?>">

                    <?php if (is_object($thumbnail)): ?>
                        <div class="ccm-block-page-list-page-entry-thumbnail">
                            <?php
                            $img = $app->make('html/image', [$thumbnail]);
                            $tag = $img->getTag();
                            $tag->addClass('img-responsive');
                            echo $tag;
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($includeEntryText): ?>
                        <div class="ccm-block-page-list-page-entry-text">

                            <?php if (isset($includeName) && $includeName): ?>
                                <div class="ccm-block-page-list-title">
                                    <?php if (isset($useButtonForLink) && $useButtonForLink) { ?>
                                        <?php echo $title; ?>
                                    <?php } else { ?>
                                        <a href="<?= h($url); ?>" target="<?php echo $target ?>"><?= h($title); ?></a>
                                    <?php } ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($includeDate) && $includeDate): ?>
                                <div class="ccm-block-page-list-date"><?=$date?></div>
                            <?php endif; ?>

                            <?php if (isset($includeDescription) && $includeDescription): ?>
                                <div class="ccm-block-page-list-description">
                                    <?= h($description); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($useButtonForLink) && $useButtonForLink && isset($buttonLinkText)): ?>
                                <div class="ccm-block-page-list-page-entry-read-more">
                                    <a href="<?= h($url); ?>" target="<?=$target?>" class="<?=$buttonClasses?>"><?= h($buttonLinkText); ?></a>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        </div>

        <?php if (count($pages) == 0 && isset($noResultsMessage)): ?>
            <div class="ccm-block-page-list-no-pages"><?= h($noResultsMessage); ?></div>
        <?php endif; ?>

    </div><!-- end .ccm-block-page-list -->


    <?php if (isset($showPagination) && $showPagination && isset($pagination)): ?>
        <?php echo $pagination; ?>
    <?php endif; ?>

<?php } ?>
