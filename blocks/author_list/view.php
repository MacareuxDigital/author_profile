<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

        <?php if (isset($authorListTitle) && $authorListTitle) { ?>
        <div class="page-header"><h1><?= h($authorListTitle); ?></div>
        <?php } ?>

        <?php if (isset($total) && $total == 0) {
            ?>

            <div><?=t('No authors found.')?></div>

            <?php
        } elseif(isset($users) && is_array($users)) {
            ?>

            <table class="table table-striped" >


                <?php
                /** @var \Concrete\Core\Utility\Service\Validation\Strings $stringValidator */
                $stringValidator = Core::make('helper/validation/strings');

                /** @var \Concrete\Core\User\UserInfo $user */
                foreach ($users as $user) {
                    $url = '';
                    if (isset($detailPage) && is_object($detailPage)) {
                        $url = URL::to($detailPage, 'view_user_detail', $user->getUserID());
                    }
                    $name = $user->getAttribute('nick_name', 'display');
                    $name = ($stringValidator->notempty($name)) ? $name : ucfirst($user->getUserName());
                    ?>

                    <tr>
                        <td class="ccm-members-directory-avatar">
                            <?php if ($url) { ?>
                                <a href="<?= h($url); ?>"><?=$user->getUserAvatar()->output()?></a>
                            <?php } else { ?>
                                <?=$user->getUserAvatar()->output()?>
                            <?php } ?>
                        </td>
                        <td class="ccm-members-directory-name">
                            <?php if ($url) { ?>
                                <a href="<?= h($url); ?>"><?= h($name); ?></a>
                            <?php } else { ?>
                                <?= h($name); ?>
                            <?php } ?>
                        </td>
                        <?php
                        if (isset($attribs) && is_array($attribs)) {
                            foreach ($attribs as $ak) {
                                ?>
                                <td>
                                    <?= $user->getAttribute($ak, 'displaySanitized', 'display');
                                    ?>
                                </td>
                                <?php
                            }
                        }
                        ?>
                    </tr>

                    <?php
                }
                ?>

            </table>

            <?php if (isset($pagination) && is_object($pagination) && $pagination->haveToPaginate()) {
                ?>

                <?=$pagination->renderView();
                ?>

                <?php
            }
            ?>

            <?php

        } ?>