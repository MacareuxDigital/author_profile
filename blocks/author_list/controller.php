<?php
namespace Concrete\Package\AuthorProfile\Block\AuthorList;

use Concrete\Core\Attribute\Category\UserCategory;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Page\Page;
use Concrete\Core\User\UserList;

class Controller extends BlockController
{
	public $detailPage;
	
    protected $btTable = 'btAuthorList';
    protected $btDefaultSet = 'social';
    protected $btInterfaceWidth = '400';
    protected $btInterfaceHeight = '300';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;

    /** @var UserList|null */
    protected $userList;

    public function getBlockTypeDescription()
    {
        return t("A block to display users");
    }

    public function getBlockTypeName()
    {
        return t('Author List');
    }

    public function add()
    {
        $this->edit();
    }

    public function edit()
    {
    }

    public function view()
    {
        $this->userList = new UserList();
        $this->userList->filterByIsActive(true);
        $this->userList->filterByAttribute('is_viewable_on_author_list', true);

        switch ($this->orderBy) {
            case 'date_added':
                $this->userList->sortByDateAdded();
                break;
            case 'user_id':
                $this->userList->sortByUserID();
                break;
            case 'user_name':
                $this->userList->sortByUserName();
                break;
        }

        $pagination = $this->userList->getPagination();
        $users = $pagination->getCurrentPageResults();
        $this->set('userList', $this->userList);
        $this->set('users', $users);
        $this->set('total', $pagination->getTotalResults());
        /** @var UserCategory $userCategory */
        $userCategory = $this->app->make(UserCategory::class);
        $this->set('attribs', $userCategory->getMemberListList());
        $this->set('pagination', $pagination);

        if ($this->detailPage > 0) {
            $detailPage = Page::getByID($this->detailPage);
            if (is_object($detailPage) && !$detailPage->isError()) {
                $this->set('detailPage', $detailPage);
            }
        }
    }
	
	public function save($data)
	{
		$data['detailPage'] = (int) $data['detailPage'];
		parent::save($data);
	}
}