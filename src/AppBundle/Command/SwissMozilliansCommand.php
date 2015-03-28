<?php

namespace AppBundle\Command;

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
        $buzz = $this->getContainer()->get('buzz');
        $config = $this->getContainer()->getParameter('mozillians');
        $mozillianApiUrl = 'https://mozillians.org/api/v1/users/?app_name=' . $config['apiAppName'] . '&app_key=' . $config['apiKey'];

        $response = $buzz->get($mozillianApiUrl . '&groups=' . $config['groupNames']);
        $data = json_decode($response->getContent(), true);

        $mozillianGravatarUrls = array();
        foreach ($data['objects'] as $mozillian) {
            $mozillianGravatarUrls[] = md5(strtolower(trim($mozillian['email'])));
        }

        $cache = $this->getContainer()->get('cache');
        $cache->save('mozillian_gravatar_urls', $mozillianGravatarUrls);
    }
}
