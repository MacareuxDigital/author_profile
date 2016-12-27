<?php
namespace Concrete\Package\AuthorProfile;

use Concrete\Core\Package\Package;
use Concrete\Core\Backup\ContentImporter;
use Core;

class Controller extends Package
{
    /**
     * @var string Package handle.
     */
    protected $pkgHandle = 'author_profile';

    /**
     * @var string Required concrete5 version.
     */
    protected $appVersionRequired = '5.7.5';

    /**
     * @var string Package version.
     */
    protected $pkgVersion = '1.0';

    /**
     * @var boolean Remove \Src from package namespace.
     */
    protected $pkgAutoloaderMapCoreExtensions = true;

    /**
     * Returns the translated package description.
     *
     * @return string
     */
    public function getPackageDescription()
    {
        return t("Author Profile Block and Author's Page List Block");
    }

    /**
     * Returns the installed package name.
     *
     * @return string
     */
    public function getPackageName()
    {
        return t('Author Profile');
    }

    /**
     * Install process of the package.
     */
    public function install()
    {
        $pkg = parent::install();
        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/config/install.xml');
    }

    /**
     * Startup process of the package.
     */
    public function on_start()
    {
        /** @var \Concrete\Core\Application\Service\UserInterface\Help\BlockTypeManager $blockTypeManager */
        $blockTypeManager = Core::make('help/block_type');
        $blockTypeManager->registerMessages(array(
            'author_profile' => array(t('You can show informations about the author of current page.')),
            'author_page_list' => array(t("Author's Page List blocks creates a navigation menu that shows pages created by the current page's author. You can also use this block on Public Profile single page.")),
        ));
    }
}
