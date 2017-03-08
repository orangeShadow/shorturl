<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\ShortUrl;


class CreateClearUrlsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:clear-urls');
        $this->setDescription('Remove old links (after 15 days)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $query = $em->createQuery(
            'DELETE
                FROM AppBundle:ShortUrl su
                WHERE DATE_DIFF(CURRENT_DATE(),su.createdAt) > 15
                '
        );

        $query->execute();

    }
}