<?php
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Package\AuthorProfile\Block\AuthorPageList\Controller;
$request = Request::getInstance();
$request->setCurrentPage(Page::getByID($_REQUEST['current_page']));
$previewMode = true;
$nh = Loader::helper('navigation');
$controller = new Controller();
$controller->num = intval($_REQUEST['num']);
$controller->orderBy = $_REQUEST['orderBy'];
$controller->ptID = intval($_REQUEST['ptID']);
$controller->displayFeaturedOnly = !!$_REQUEST['displayFeaturedOnly'];
$controller->displayAliases = !!$_REQUEST['displayAliases'];
$controller->paginate = !!$_REQUEST['paginate'];
$controller->includeName = !!$_REQUEST['includeName'];
$controller->includeDate = !!$_REQUEST['includeDate'];
$controller->displayThumbnail = !!$_REQUEST['displayThumbnail'];
$controller->includeDescription = !!$_REQUEST['includeDescription'];
$controller->useButtonForLink = !!$_REQUEST['useButtonForLink'];
$controller->on_start();
$controller->add();
$controller->view();
$pages = $controller->get('pages');
$sets = $controller->getSets();

extract($controller->getSets());

require(dirname(__FILE__) . '/../view.php');
exit;
