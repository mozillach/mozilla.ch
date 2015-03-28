<?php

namespace AppBundle\Command;

use Endroid\Twitter\Twitter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use ICal;

class TwitterFeedCacheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mozilla-ch:cache-twitter-feed')
            ->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Twitter $twitterClient */
        $twitterClient = $this->getContainer()->get('endroid.twitter');
        $timeline = $twitterClient->getTimeline(array('screen_name' => 'mozillach'));

        // we only want the last 5
        $lastFiveTweets = array_splice($timeline, 5);
        $this->getContainer()->get('cache')->save('twitter_tweets', $lastFiveTweets);
    }
}


