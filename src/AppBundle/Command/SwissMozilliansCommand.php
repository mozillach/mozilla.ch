<?php

namespace AppBundle\Command;

use Guzzle\Service\Client;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class SwissMozilliansCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mozilla-ch:download-swiss-mozillians')
            ->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Client $guzzle */
        $guzzle = $this->getContainer()->get('mozillians.client');
        $groupNames = $this->getContainer()->getParameter('mozillians.group_names');

        $usersResponse = $guzzle->get('users?groups=' . $groupNames)->send();
        $data = json_decode($usersResponse->getBody(true), true);

        $mozillianGravatarUrls = array();
        foreach ($data['objects'] as $mozillian) {
            $mozillianGravatarUrls[] = md5(strtolower(trim($mozillian['email'])));
        }

        $cache = $this->getContainer()->get('cache');
        $cache->save('mozillian_gravatar_urls', $mozillianGravatarUrls);
    }
}
