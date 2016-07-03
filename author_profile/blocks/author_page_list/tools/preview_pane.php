<?php
defined('C5_EXECUTE') or die('Access Denied.');

$request = Request::getInstance();
$request->setCurrentPage(Page::getByID($_REQUEST['current_page']));

$bt = Concrete\Core\Block\BlockType\BlockType::getByHandle('author_page_list');
$controller = $bt->getController();
$controller->num = intval($_REQUEST['num']);
$controller->paginate = !!$_REQUEST['paginate'];
$controller->orderBy = $_REQUEST['orderBy'];
$controller->displayFeaturedOnly = !!$_REQUEST['displayFeaturedOnly'];
$controller->displayAliases = !!$_REQUEST['displayAliases'];
$controller->ptID = intval($_REQUEST['ptID']);
$controller->set('includeName', !!$_REQUEST['includeName']);
$controller->set('includeDate', !!$_REQUEST['includeDate']);
$controller->set('displayThumbnail', !!$_REQUEST['displayThumbnail']);
$controller->set('includeDescription', !!$_REQUEST['includeDescription']);
$controller->set('useButtonForLink', !!$_REQUEST['useButtonForLink']);

$bv = new \Concrete\Core\Block\View\BlockView($bt);
$bv->render('view');
exit;
