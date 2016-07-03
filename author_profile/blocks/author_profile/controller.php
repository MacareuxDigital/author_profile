<?php
namespace Concrete\Package\AuthorProfile\Block\AuthorProfile;

use Page;
use Core;
use Config;
use Concrete\Core\Block\BlockController;
use UserAttributeKey;

class Controller extends BlockController
{
    protected $btTable = 'btAuthorProfile';
    protected $btDefaultSet = 'multimedia';
    protected $btInterfaceWidth = "400";
    protected $btInterfaceHeight = "300";
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 0; //until manually updated or cleared

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
        $data['displayBasicAttributes'] = $data['displayBasicAttributes'] ? 1 : 0;
        $data['displayProfileAttributes'] = $data['displayProfileAttributes'] ? 1 : 0;
        $data['displayMemberListAttributes'] = $data['displayMemberListAttributes'] ? 1 : 0;
        $data['displayProfilePicture'] = $data['displayProfilePicture'] ? 1 : 0;
        $data['linkToProfilePage'] = $data['linkToProfilePage'] ? 1 : 0;
        parent::save($data);
    }

    public function view()
    {
        $c = Page::getCurrentPage();
        $uID = $c->getCollectionUserID();
        $ui = Core::make('Concrete\Core\User\UserInfoFactory')->getByID($uID);
        $this->set('ui', $ui);

        $this->set('publicProfileAttributes', UserAttributeKey::getPublicProfileList());
        $this->set('memberListAttributes', UserAttributeKey::getMemberListList());
        $this->set('publicProfileEnabled', Config::get('concrete.user.profiles_enabled'));
    }
}
