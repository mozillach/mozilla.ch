<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use ICal;

class MozillaRepsEventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mozilla-ch:mozilla-reps-events')
            ->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buzz = $this->getContainer()->get('buzz');

        $response = $buzz->get('https://reps.mozilla.org/events/period/future/search/switzerland/ical/');

        $lines = explode("\n", $response->getContent());

        $ical = new ICal($lines);
        $this->getContainer()->get('cache')->save('mozilla_reps_events', $ical->events());
    }
}


