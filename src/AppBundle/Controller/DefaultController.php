<?php

namespace AppBundle\Controller;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ICal;

class DefaultController extends Controller
{
    private $mozillianGravatarUrls;

    /**
     * @Route("/{_locale}", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }

    public function contributorTileAction($index)
    {
        $url = '';

        $mozillianGravatarUrls = $this->getMozillianGravatarUrls();
        if (count($mozillianGravatarUrls) > 0) {
            $index = $index % count($mozillianGravatarUrls);

            $url = $mozillianGravatarUrls[$index];
        }

        return $this->render('tiles/contributor.html.twig', array('url' => $url));
    }

    public function nextEventTileAction()
    {
        $events = $this->getEventFeed();

        return $this->render('tiles/upcoming_event.html.twig', array('event' => reset($events)));
    }

    public function marketplaceAppAction($id)
    {
        $app = $this->getApp($id);

        return $this->render('marketplace_app.html.twig', array('application' => $app));
    }

    private function checkGravatarExistence($hash)
    {
        try {
            $this->container->get('guzzle.client')->get('https://secure.gravatar.com/avatar/'.$hash.'?r=g&s=1&d=404')->send();
        } catch(ClientErrorResponseException $e) {
            return false;
        }
        return true;
    }

    private function getMozillianGravatarUrls()
    {
        if (isset($this->mozillianGravatarUrls)) {
            $mozillianGravatarUrls = $this->mozillianGravatarUrls;
        } else if ($cachedGravatarUrls = $this->getCache()->fetch('mozillian_gravatar_urls')) {
            $mozillianGravatarUrls = unserialize($cachedGravatarUrls);
        } else {
            /** @var Client $guzzle */
            $guzzle = $this->container->get('mozillians.client');

            $groupNames = $this->container->getParameter('mozillians.group_names');
            try {
                $usersResponse = $guzzle->get('users?groups=' . $groupNames)->send();
            } catch(ClientErrorResponseException $e) {
                return array();
            }

            $data = json_decode($usersResponse->getBody(true), true);

            $mozillianGravatarUrls = array();
            foreach ($data['objects'] as $mozillian) {
                $hash = md5(strtolower(trim($mozillian['email'])));

                if($this->checkGravatarExistence($hash))
                    $mozillianGravatarUrls[] = $hash;
            }

            $this->getCache()->save('mozillian_gravatar_urls', serialize($mozillianGravatarUrls), 3600);
        }

        $this->mozillianGravatarUrls = $mozillianGravatarUrls;

        return $mozillianGravatarUrls;
    }


    private function getEventFeed()
    {
        if ($cachedEvents = $this->getCache()->fetch('mozilla_reps_events')) {
            $events = unserialize($cachedEvents);
        } else {
            $response = $this->container->get('mozilla_reps.client')->get('events/period/future/search/switzerland/ical/')->send();

            $lines = explode("\n", $response->getBody(true));

            if(count($lines) > 5) {
                $ical = new ICal($lines);
                $events = $ical->events();
                foreach($events as $i => $event) {
                    $events[$i]['TIMESTAMP'] = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
                    $events[$i]['LOCATION'] = stripslashes($event['LOCATION']);
                    $events[$i]['SUMMARY'] = stripslashes($event['SUMMARY']);
                }
            }
            else {
                $events = array();
            }
            $this->getCache()->save('mozilla_reps_events', serialize($events), 3600);
        }

        return $events;
    }

    private function getApp($id)
    {
        $cachedApps = unserialize($this->getCache()->fetch('firefox_marketplace_apps'));
        if(isset($cachedApps[$id])) {
            $app = $cachedApps[$id];
        } else {
            $response = $this->container->get('firefox_marketplace.client')->get('apps/app/' . $id)->send();

            $app = json_decode($response->getBody(true), true);

            $cachedApps[$id] = $app;
            $this->getCache()->save('firefox_marketplace_apps', serialize($cachedApps), 3600);
        }

        return $app;
    }

    private function getCache()
    {
        return $this->container->get('cache');
    }
}
