<?php
namespace Concrete\Package\AuthorProfile;

use Concrete\Core\Package\Package;
use Concrete\Core\Backup\ContentImporter;

class Controller extends Package
{
    /**
     * Package handle.
     */
    protected $pkgHandle = 'author_profile';

    /**
     * Required concrete5 version.
     */
    protected $appVersionRequired = '5.7.5';

    /**
     * Package version.
     */
    protected $pkgVersion = '0.1';

    /**
     * Remove \Src from package namespace.
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

    public function install()
    {
        $pkg = parent::install();
        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/config/install.xml');
    }
}
