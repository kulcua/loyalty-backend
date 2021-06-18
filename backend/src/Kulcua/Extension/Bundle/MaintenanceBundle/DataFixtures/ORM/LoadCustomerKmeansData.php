<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenLoyalty\Bundle\LevelBundle\DataFixtures\ORM\LoadLevelData;
use OpenLoyalty\Bundle\PosBundle\DataFixtures\ORM\LoadPosData;
use OpenLoyalty\Bundle\UserBundle\Entity\Customer;
use OpenLoyalty\Bundle\UserBundle\Entity\Seller;
use OpenLoyalty\Bundle\UserBundle\Entity\Status;
use OpenLoyalty\Component\Customer\Domain\Command\ActivateCustomer;
use OpenLoyalty\Component\Customer\Domain\Command\AssignPosToCustomer;
use OpenLoyalty\Component\Customer\Domain\Command\MoveCustomerToLevel;
use OpenLoyalty\Component\Customer\Domain\Command\RegisterCustomer;
use OpenLoyalty\Component\Customer\Domain\Command\UpdateCustomerAddress;
use OpenLoyalty\Component\Customer\Domain\Command\UpdateCustomerDetails;
use OpenLoyalty\Component\Customer\Domain\Command\UpdateCustomerLoyaltyCardNumber;
use OpenLoyalty\Component\Customer\Domain\CustomerId;
use OpenLoyalty\Component\Customer\Domain\LevelId;
use OpenLoyalty\Component\Seller\Domain\Command\ActivateSeller;
use OpenLoyalty\Component\Seller\Domain\Command\RegisterSeller;
use OpenLoyalty\Component\Seller\Domain\PosId;
use OpenLoyalty\Component\Seller\Domain\SellerId;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Broadway\UuidGenerator\Rfc4122\Version4Generator as UuidGenerator;

/**
 * Class LoadCustomerKmeansData.
 */
class LoadCustomerKmeansData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $bus = $this->container->get('broadway.command_handling.command_bus');

        $string = file_get_contents('data.json', true);
        $json_data = json_decode($string, true);
        foreach ($json_data as $person) {
            $firstName = $person['first'];
            $lastName = $person['last'];
            $email = $person['email'];
            $phone = $person['phone'];
            $year = $person['year'];
            $gender = $person['gender'];
            $birthday = strtotime("$year-01-01");

            //LOAD USER
            $randomUuidGenerator = new UuidGenerator();
            $customerId = new CustomerId($randomUuidGenerator->generate());
            $command = new RegisterCustomer(
                $customerId,
                $this->getDefaultCustomerData($firstName, $lastName, $email, $phone, $birthday, $gender)
            );

            $bus->dispatch($command);
            $bus->dispatch(new ActivateCustomer($customerId));
            $bus->dispatch(new AssignPosToCustomer($customerId, new \OpenLoyalty\Component\Customer\Domain\PosId(LoadPosData::POS_ID)));

            $user = new Customer($customerId);
            $user->setPlainPassword("loyalty");
            $user->setPhone($command->getCustomerData()[$phone]);

            $password = $this->container->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());

            $user->addRole($this->getReference('role_participant'));
            $user->setPassword($password);
            $user->setIsActive(true);
            $user->setStatus(Status::typeActiveNoCard());

            $user->setEmail($email);
            $manager->persist($user);
        }
    }

    public static function getDefaultCustomerData($firstName, $lastName, $email, $phone = '00000', $birthday, $gender)
    {
        return [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'gender' => $gender,
            'phone' => $phone,
            'email' => $email,
            'birthDate' => $birthday,
            'createdAt' => 1470646394,
            'company' => [
                'name' => 'test',
                'nip' => 'nip',
            ],
            'loyaltyCardNumber' => '000000',
            'address' => [
                'street' => 'Dmowskiego',
                'address1' => '21',
                'city' => 'Wrocław',
                'country' => 'pl',
                'postal' => '50-300',
                'province' => 'Dolnośląskie',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
