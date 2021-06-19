<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenLoyalty\Bundle\SettingsBundle\Entity\BooleanSettingEntry;
use OpenLoyalty\Bundle\SettingsBundle\Entity\FileSettingEntry;
use OpenLoyalty\Bundle\SettingsBundle\Entity\IntegerSettingEntry;
use OpenLoyalty\Bundle\SettingsBundle\Entity\JsonSettingEntry;
use OpenLoyalty\Bundle\SettingsBundle\Entity\StringSettingEntry;
use OpenLoyalty\Bundle\SettingsBundle\Form\Type\SettingsFormType;
use OpenLoyalty\Bundle\SettingsBundle\Model\Logo;
use OpenLoyalty\Bundle\SettingsBundle\Model\Settings;
use OpenLoyalty\Bundle\SettingsBundle\Provider\AvailableMarketingVendors;
use OpenLoyalty\Bundle\SettingsBundle\Service\LogoUploader;
use OpenLoyalty\Component\Account\Domain\Model\AddPointsTransfer;
use OpenLoyalty\Component\Customer\Domain\Model\AccountActivationMethod;
use OpenLoyalty\Component\Customer\Domain\Model\Status;
use OpenLoyalty\Component\Customer\Infrastructure\LevelDowngradeModeProvider;
use OpenLoyalty\Component\Customer\Infrastructure\TierAssignTypeProvider;
use OpenLoyalty\Component\Translation\Domain\Command\CreateLanguage;
use OpenLoyalty\Component\Translation\Domain\LanguageId;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class LoadLanguageVNData.
 */
class LoadLanguageVNData extends ContainerAwareFixture implements OrderedFixtureInterface
{
    /**
     * @var array
     */
    private $languageMap = [
        'vietnam.json' => [
            'code' => 'vi',
            'name' => 'Vietnam',
            'default' => false,
            'order' => 3,
        ],
    ];

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadDefaultTranslations();

        // $settings = new Settings();

        // $currency = new StringSettingEntry('currency', 'eur');
        // $settings->addEntry($currency);

        // copy logo
        // $rootDirectory = $this->getContainer()->getParameter('kernel.root_dir');
        // $destinationDirectory = $rootDirectory.'/uploads/logo';
        // $filesystem = $this->getContainer()->get('filesystem');
        // if (!$filesystem->exists($destinationDirectory)) {
        //     $filesystem->mkdir($destinationDirectory);
        // }
        // $kernel = $this->getContainer()->get('kernel');

        // $photoNames = [
        //     LogoUploader::LOGO,
        //     LogoUploader::SMALL_LOGO,
        //     LogoUploader::HERO_IMAGE,
        //     LogoUploader::ADMIN_COCKPIT_LOGO,
        //     LogoUploader::CLIENT_COCKPIT_LOGO_BIG,
        //     LogoUploader::CLIENT_COCKPIT_LOGO_SMALL,
        //     LogoUploader::CLIENT_COCKPIT_HERO_IMAGE,
        // ];

        // foreach ($photoNames as $name) {
        //     $filesystem->copy(
        //         $kernel->locateResource('@OpenLoyaltySettingsBundle/Resources/images/logo/logo.png'),
        //         $destinationDirectory.'/'.$name.'.png'
        //     );

        //     $photo = new Logo();
        //     $photo->setMime('image/png');
        //     $photo->setPath('logo/'.$name.'.png');
        //     $entry = new FileSettingEntry($name, $photo);
        //     $settings->addEntry($entry);
        // }

        // $this->getContainer()->get('ol.settings.manager')->save($settings);
    }

    /**
     * Copy default translations to translations directory.
     */
    protected function loadDefaultTranslations(): void
    {
        $commandBus = $this->container->get('broadway.command_handling.command_bus');
        $uuidGenerator = $this->container->get('broadway.uuid.generator');

        /** @var Kernel $kernel */
        $kernel = $this->getContainer()->get('kernel');

        $transDir = $kernel->locateResource('@MaintenanceBundle/DataFixtures/ORM/translations/');
        $finder = Finder::create();
        $finder->files()->in($transDir);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $fileName = $file->getFilename();

            if (array_key_exists($fileName, $this->languageMap)) {
                $languageData = $this->languageMap[$fileName];

                $commandBus->dispatch(new CreateLanguage(
                    new LanguageId($uuidGenerator->generate()),
                    [
                        'code' => $languageData['code'],
                        'name' => $languageData['name'],
                        'order' => $languageData['order'],
                        'default' => $languageData['default'],
                        'translations' => $file->getContents(),
                    ]
                ));
            }
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}
