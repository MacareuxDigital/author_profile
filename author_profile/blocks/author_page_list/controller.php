<?php
namespace Concrete\Package\AuthorProfile\Block\AuthorPageList;

use Concrete\Core\Block\BlockController;

class Controller extends BlockController
{
    protected $btTable = 'btAuthorPageList';
    protected $btDefaultSet = 'multimedia';
    protected $btInterfaceWidth = "400";
    protected $btInterfaceHeight = "500";
    protected $btWrapperClass = 'ccm-ui';
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = null;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputLifetime = 300;

    public function getBlockTypeDescription()
    {
        return t("A block to display pages written by current user.");
    }

    public function getBlockTypeName()
    {
        return t("Author's Page List");
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
        if (isset($data['andSearchAttributeIDs']) && is_array($data['andSearchAttributeIDs'])) {
            $data['andSearchAttributeIDs'] = implode(',', $data['andSearchAttributeIDs']);
        }
        if (isset($data['orSearchAttributeIDs']) && is_array($data['orSearchAttributeIDs'])) {
            $data['orSearchAttributeIDs'] = implode(',', $data['orSearchAttributeIDs']);
        }
        parent::save($data);
    }
    
    public function view()
    {
    }
}
