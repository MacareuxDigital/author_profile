<?php

namespace Concrete\Package\AuthorProfile\Block\AuthorPageList;

use Concrete\Core\Attribute\Category\PageCategory;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Block\View\BlockView;
use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\PageList;

class Controller extends BlockController
{
    /** @var PageList|null */
    public $list;
    /** @var int|null */
    public $uID;
	public $num;
	public $ptID;
	public $displayFeaturedOnly;
	public $displayAliases;
	public $paginate;
	public $orderBy;
	public $truncateSummaries;
	public $truncateChars;
	public $includeDate;
	public $displayThumbnail;
	public $useButtonForLink;
	public $buttonLinkText;
	public $pageListTitle;
	public $noResultsMessage;
	public $includeName;
	public $includeDescription;
	public $displayMode;
	public $hideNotViewableUser;
    protected $btTable = 'btAuthorPageList';
    protected $btDefaultSet = 'social';
    protected $btInterfaceWidth = '800';
    protected $btInterfaceHeight = '500';
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = null;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeDescription()
    {
        return t('A block to display pages written by current user.');
    }

    public function getBlockTypeName()
    {
        return t('Authors Pages');
    }

    /**
     * @param bool $uID
     *
     * @return PageList|null
     */
    public function getPageList($uID = false)
    {
        if ($this->displayMode != 'E' && $uID == false) {
            $c = Page::getCurrentPage();
            $cnt = $c->getPageController();
            if ($profile = $cnt->get('profile')) {
                $uID = $profile->getUserID();
            } else {
                $uID = $c->getCollectionUserID();
            }
        }

        $ui = $this->app->make('Concrete\Core\User\UserInfoRepository')->getByID($uID);
        if (is_object($ui)) {
            $viewable = true;
            if ($this->hideNotViewableUser && !$ui->getAttribute('is_viewable_on_author_list')) {
                $viewable = false;
            }
            if ($viewable) {
                $this->list = new PageList();
                $this->list->disableAutomaticSorting();
                $this->list->filterByUserID($uID);
                $this->uID = $uID;

                switch ($this->orderBy) {
                    case 'display_asc':
                        $this->list->sortByDisplayOrder();
                        break;
                    case 'display_desc':
                        $this->list->sortByDisplayOrderDescending();
                        break;
                    case 'chrono_asc':
                        $this->list->sortByPublicDate();
                        break;
                    case 'random':
                        $this->list->sortBy('RAND()');
                        break;
                    case 'alpha_asc':
                        $this->list->sortByName();
                        break;
                    case 'alpha_desc':
                        $this->list->sortByNameDescending();
                        break;
                    default:
                        $this->list->sortByPublicDateDescending();
                        break;
                }

                /** @var PageCategory $pageCategory */
                $pageCategory = $this->app->make(PageCategory::class);

                if ($this->displayFeaturedOnly == 1) {
                    $cak = $pageCategory->getAttributeKeyByHandle('is_featured');
                    if (is_object($cak)) {
                        $this->list->filterByIsFeatured(1);
                    }
                }
                if ($this->displayAliases) {
                    $this->list->includeAliases();
                }
                $this->list->filter('cvName', '', '!=');

                if ($this->ptID) {
                    $this->list->filterByPageTypeID($this->ptID);
                }

                $cak = $pageCategory->getAttributeKeyByHandle('exclude_page_list');
                if (is_object($cak)) {
                    $this->list->filterByExcludePageList(false);
                }
            }
        }

        return $this->list;
    }

    public function view()
    {

        if (is_object($this->list)) {
            $list = $this->list;
        } else {
            $list = $this->getPageList();
        }
        $nh = $this->app->make('helper/navigation');
        $this->set('nh', $nh);

        //Pagination...
        $showPagination = false;
        if (is_object($list)) {
            if ($this->num > 0) {
                $list->setItemsPerPage($this->num);
                $pagination = $list->getPagination();
                $pages = $pagination->getCurrentPageResults();
                if ($pagination->getTotalPages() > 1 && $this->paginate) {
                    $showPagination = true;
                    $pagination = $pagination->renderDefaultView();
                    $this->set('pagination', $pagination);
                }
            } else {
                $pages = $list->getResults();
            }
        } else {
            $pages = [];
        }

        if ($showPagination) {
            $this->requireAsset('css', 'core/frontend/pagination');
        }

        $this->set('pages', $pages);
        $this->set('list', $list);
        $this->set('uID', $this->uID);
        $this->set('showPagination', $showPagination);
    }

    public function action_view_user_detail($uID = null)
    {
        if ($this->displayMode == 'E') {
            $this->getPageList($uID);
        }
        $this->view();
    }

    public function add()
    {
        $uh = $this->app->make('helper/concrete/urls');
        $this->set('uh', $uh);
        $this->set('includeDescription', true);
        $this->set('includeName', true);
        $this->set('bt', BlockType::getByHandle('author_page_list'));
        /** @var PageCategory $cak */
        $cak = $this->app->make(PageCategory::class);
        $this->set('featuredAttribute', $cak->getAttributeKeyByHandle('is_featured'));
        $this->set('thumbnailAttribute', $cak->getAttributeKeyByHandle('thumbnail'));
    }

    public function edit()
    {
        $b = $this->getBlockObject();
        $bID = $b->getBlockID();
        $this->set('bID', $bID);
        $uh = $this->app->make('helper/concrete/urls');
        $this->set('uh', $uh);
        $this->set('bt', BlockType::getByHandle('author_page_list'));
        /** @var PageCategory $cak */
        $cak = $this->app->make(PageCategory::class);
        $this->set('featuredAttribute', $cak->getAttributeKeyByHandle('is_featured'));
        $this->set('thumbnailAttribute', $cak->getAttributeKeyByHandle('thumbnail'));
    }

    public function save($args)
    {
        $args = $args + [
                    'num' => 0,
                    'includeDate' => 0,
                    'truncateSummaries' => 0,
                    'truncateChars' => 0,
                    'displayFeaturedOnly' => 0,
                    'displayThumbnail' => 0,
                    'displayAliases' => 0,
                    'paginate' => 0,
                    'ptID' => 0,
                    'useButtonForLink' => 0,
                    'includeName' => 0,
                    'includeDescription' => 0,
                ];

        $args['num'] = ($args['num'] > 0) ? (int) ($args['num']) : 0;
        $args['includeDate'] = ($args['includeDate']) ? '1' : '0';
        $args['truncateSummaries'] = ($args['truncateSummaries']) ? '1' : '0';
        $args['truncateChars'] = ($args['truncateChars'] > 0) ? (int) ($args['truncateChars']) : 0;
        $args['displayFeaturedOnly'] = ($args['displayFeaturedOnly']) ? '1' : '0';
        $args['displayThumbnail'] = ($args['displayThumbnail']) ? '1' : '0';
        $args['displayAliases'] = ($args['displayAliases']) ? '1' : '0';
        $args['paginate'] = ($args['paginate']) ? '1' : '0';
        $args['ptID'] = (int) ($args['ptID']);
        $args['useButtonForLink'] = ($args['useButtonForLink']) ? '1' : '0';
        $args['includeName'] = ($args['includeName']) ? '1' : '0';
        $args['includeDescription'] = ($args['includeDescription']) ? '1' : '0';
        $data['displayMode'] = (empty($data['displayMode'])) ? 'S' : $data['displayMode'];

        parent::save($args);
    }

    public function isBlockEmpty()
    {
        $pages = $this->get('pages');
        if (isset($this->pageListTitle) && $this->pageListTitle) {
            return false;
        }
        if (count($pages) == 0) {
            if ($this->noResultsMessage) {
                return false;
            }

                return true;

        }
            if ($this->includeName || $this->includeDate || $this->displayThumbnail
                || $this->includeDescription || $this->useButtonForLink
            ) {
                return false;
            }

                return true;


    }

    public function cacheBlockOutput()
    {
        if ($this->btCacheBlockOutput === null) {
            if ($this->displayMode != 'E' && !$this->paginate) {
                $this->btCacheBlockOutput = true;
            } else {
                $this->btCacheBlockOutput = false;
            }
        }

        return  $this->btCacheBlockOutput;
    }

	public function getPassThruActionAndParameters($parameters): array
	{
		if ($parameters[0] == 'preview_pane') {
			$method = 'action_' . $parameters[0];
			$parameters = array_slice($parameters, 1);
		}else {
			$parameters = $method = null;
		}

		return [$method, $parameters];
	}

	public function isValidControllerTask($method, $parameters = []): bool
	{
		if ($method == 'action_preview_pane') {
			return true;
		}

		return false;
	}

	public function action_preview_pane()
	{
		$bt = BlockType::getByHandle('author_page_list');
		//$bt = BlockType::getByHandle('page_list');
		$controller = $bt->getController();
		$controller->num = $_REQUEST['num'] ?? 0;
		$controller->orderBy = $_REQUEST['orderBy'] ?? null;
		$controller->ptID = $_REQUEST['ptID'] ?? null;
		$controller->displayFeaturedOnly = $_REQUEST['displayFeaturedOnly'] ?? false;
		$controller->displayAliases = $_REQUEST['displayAliases'] ?? false;
		$controller->paginate = $_REQUEST['paginate'] ?? false;
		$controller->truncateSummaries = $_REQUEST['truncateSummaries'] ?? false;
		$controller->truncateChars = $_REQUEST['truncateChars'] ?? 0;
		$controller->displayThumbnail = $_REQUEST['displayThumbnail'] ?? false;
		$controller->useButtonForLink = $_REQUEST['useButtonForLink'] ?? false;
		$controller->buttonLinkText = $_REQUEST['buttonLinkText'] ?? null;
		$controller->pageListTitle = $_REQUEST['pageListTitle'] ?? null;
		$controller->noResultsMessage = $_REQUEST['noResultsMessage'] ?? null;
		$controller->set('includeEntryText', true);
		$controller->set('includeName', true);
		$bv = new BlockView($bt);
		ob_start();
		$bv->render('view');
		$content = ob_get_contents();
		ob_end_clean();


		return $this->app->make(ResponseFactoryInterface::class)->create($content);
	}
}
