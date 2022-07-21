<?php
namespace Concrete\Package\AuthorProfile\Block\AuthorProfile;

use Concrete\Core\Attribute\Category\UserCategory;
use Concrete\Core\Config\Repository\Liaison;
use Concrete\Core\Html\Service\Seo;
use Concrete\Core\Page\Page;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Utility\Service\Validation\Strings;

class Controller extends BlockController
{
	public $hideNotViewableUser;
	public $detailPage;
	
    protected $btTable = 'btAuthorProfile';
    protected $btDefaultSet = 'social';
    protected $btInterfaceWidth = '400';
    protected $btInterfaceHeight = '300';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = null;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 0; //until manually updated or cleared
    protected $displayMode;

    public function getBlockTypeDescription()
    {
        return t("A block to display public attributes of the page's author");
    }

    public function getBlockTypeName()
    {
        return t('Author Profile');
    }

    public function add()
    {
        $this->edit();
    }

    public function edit()
    {
    }

    public function save($data)
    {
        $data['displayBasicAttributes'] = isset($data['displayBasicAttributes']) ? 1 : 0;
        $data['displayProfileAttributes'] = isset($data['displayProfileAttributes']) ? 1 : 0;
        $data['displayMemberListAttributes'] = isset($data['displayMemberListAttributes']) ? 1 : 0;
        $data['displayProfilePicture'] = isset($data['displayProfilePicture']) ? 1 : 0;
        $data['linkToProfilePage'] = isset($data['linkToProfilePage']) ? 1 : 0;
        $data['hideNotViewableUser'] = isset($data['hideNotViewableUser']) ? 1 : 0;
        $data['displayMode'] = (empty($data['displayMode'])) ? 'S' : $data['displayMode'];
	    $data['detailPage'] = (int) $data['detailPage'];
	    parent::save($data);
    }

    public function view()
    {
        if ($this->displayMode != 'E') {
            $c = Page::getCurrentPage();
            $uID = $c->getCollectionUserID();
            $ui = $this->app->make('Concrete\Core\User\UserInfoRepository')->getByID($uID);
            if (is_object($ui)) {
                $viewable = true;
                if ($this->hideNotViewableUser && !$ui->getAttribute('is_viewable_on_author_list')) {
                    $viewable = false;
                }
                if ($viewable) {
                    $this->set('ui', $ui);
                }
            }
        }

        /** @var UserCategory $userCategory */
        $userCategory = $this->app->make(UserCategory::class);
        $this->set('publicProfileAttributes', $userCategory->getPublicProfileList());
        $this->set('memberListAttributes', $userCategory->getMemberListList());

        /** @var Liaison $config */
        $config = $this->app->make('config');
        $this->set('publicProfileEnabled', $config->get('concrete.user.profiles_enabled'));

        if ($this->detailPage > 0) {
            $detailPage = Page::getByID($this->detailPage);
            if (is_object($detailPage) && !$detailPage->isError()) {
                $this->set('detailPage', $detailPage);
            }
        }
    }

    public function action_view_user_detail($uID = null)
    {
        if ($this->displayMode == 'E') {
            $ui = $this->app->make('Concrete\Core\User\UserInfoRepository')->getByID($uID);
            if (is_object($ui)) {
                /** @var Strings $stringValidator */
                $stringValidator = $this->app->make('helper/validation/strings');
                $name = $ui->getAttribute('nick_name', 'display');
                $name = ($stringValidator->notempty($name)) ? $name : $ui->getUserName();
                /** @var Seo $seo */
                $seo = $this->app->make('helper/seo');
                $seo->addTitleSegment($name);
                $this->set('ui', $ui);
            }
            $this->view();
        }
    }

    public function cacheBlockOutput()
    {
        if ($this->btCacheBlockOutput === null) {
            if ($this->displayMode == 'E') {
                $this->btCacheBlockOutput = false;
            } else {
                $this->btCacheBlockOutput = true;
            }
        }

        return $this->btCacheBlockOutput;
    }
}
