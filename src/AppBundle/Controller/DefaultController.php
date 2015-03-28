<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ICal;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $this->nextEventTileAction();

        return $this->render('default/index.html.twig');
    }

    public function contributorTileAction()
    {
        $mozillianGravatarUrls = $this->getMozillianGravatarUrls();

        return $this->render('tiles/contributor.html.twig', array('url' => reset($mozillianGravatarUrls)));
    }

    public function nextEventTileAction()
    {
        $events = $this->getEventFeed();

        return $this->render('tiles/upcoming_event.html.twig', array('event' => reset($events)));
    }

    private function getMozillianGravatarUrls()
    {
        if ($cachedGravatarUrls = $this->getCache()->fetch('mozillian_gravatar_urls')) {
            $mozillianGravatarUrls = unserialize($cachedGravatarUrls);
        } else {
            $config = $this->container->getParameter('mozillians');
            $mozillianApiUrl = 'https://mozillians.org/api/v1/users/?app_name=' . $config['apiAppName'] . '&app_key=' . $config['apiKey'];

            $response = $this->getBuzz()->get($mozillianApiUrl . '&groups=' . $config['groupNames']);
            $data = json_decode($response->getContent(), true);

            $mozillianGravatarUrls = array();
            foreach ($data['objects'] as $mozillian) {
                $mozillianGravatarUrls[] = md5(strtolower(trim($mozillian['email'])));
            }

            $this->getCache()->save('mozillian_gravatar_urls', serialize($mozillianGravatarUrls), 3600);
        }

        return $mozillianGravatarUrls;
    }


    private function getEventFeed()
    {
        if ($cachedEvents = $this->getCache()->fetch('mozilla_reps_events')) {
            $events = unserialize($cachedEvents);
        } else {
            $response = $this->getBuzz()->get('https://reps.mozilla.org/events/period/future/search/switzerland/ical/');

            $lines = explode("\n", $response->getContent());

            $ical = new ICal($lines);
            $events = $ical->events();
            $this->getCache()->save('mozilla_reps_events', serialize($events), 3600);
        }

        return $events;
    }

    private function getCache()
    {
        return $this->container->get('cache');
    }

    private function getBuzz()
    {
        return $this->container->get('buzz');
    }
}
