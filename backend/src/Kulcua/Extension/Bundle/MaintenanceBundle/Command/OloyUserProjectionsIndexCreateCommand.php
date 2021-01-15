<?php

namespace Kulcua\Extension\Bundle\MaintenanceBundle\Command;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use OpenLoyalty\Component\Campaign\Domain\ReadModel\CampaignBoughtRepository;
use OpenLoyalty\Component\Campaign\Domain\ReadModel\CampaignUsageRepository;
use OpenLoyalty\Component\Transaction\Domain\ReadModel\TransactionDetailsRepository;
use Kulcua\Extension\Component\Maintenance\Domain\ReadModel\MaintenanceDetailsRepository;
use Kulcua\Extension\Component\Conversation\Domain\ReadModel\ConversationDetailsRepository;
use Kulcua\Extension\Component\Message\Domain\ReadModel\MessageDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OloyUserProjectionsIndexCreateCommand.
 */
class OloyUserProjectionsIndexCreateCommand extends ContainerAwareCommand
{
    protected $repos = [
        'oloy.user.read_model.repository.customer_details',
        'oloy.user.read_model.repository.invitation_details',
        'oloy.points.account.repository.account_details',
        'oloy.points.account.repository.points_transfer_details',
        'oloy.user.read_model.repository.customers_belonging_to_one_level',
        TransactionDetailsRepository::class,
        'oloy.user.read_model.repository.seller_details',
        'oloy.segment.read_model.repository.segmented_customers',
        'oloy.campaign.read_model.repository.coupon_usage',
        CampaignBoughtRepository::class,
        CampaignUsageRepository::class,
        MaintenanceDetailsRepository::class,
        ConversationDetailsRepository::class,
        MessageDetailsRepository::class,
    ];

    protected function configure()
    {
        $this->setName('oloy:user:projections:index:create');
        $this->setDescription('Command console override by KC');
        $this->addOption('drop-old', 'drop-old', InputOption::VALUE_NONE);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->repos as $repo) {
            $repo = $this->getContainer()->get($repo);
            if ($input->getOption('drop-old')) {
                if (method_exists($repo, 'deleteIndex')) {
                    try {
                        $repo->deleteIndex();
                    } catch (Missing404Exception $e) {
                    }
                }
            }
            if (method_exists($repo, 'createIndex')) {
                $repo->createIndex();
            }
        }
    }
}
