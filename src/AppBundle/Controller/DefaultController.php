<?php

namespace AppBundle\Controller;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ICal;

class DefaultController extends Controller
{
    private $mozillianData;
    /** @var \ICal */
    private $ical;

    /**
     * @Route("/{_locale}", name="homepage", requirements={
           "_locale": "(de|en|fr|it|rm)"
       })
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }

    public function contributorTileAction($index)
    {
        $data = array('photo_url' => '', 'href' => '');

        $mozillianData = $this->getMozillianData();
        if (count($mozillianData) > 0) {
            $index = $index % count($mozillianData);

            $data = $mozillianData[$index];
        }

        return $this->render('tiles/contributor.html.twig', array('photo_url' => $data['photo_url'], 'href' => $data['href']));
    }

    public function nextEventTileAction()
    {
        $events = $this->getEventFeed();

        return $this->render('tiles/upcoming_event.html.twig', array('event' => reset($events)));
    }

    private function getMozillianData()
    {
        if (isset($this->mozillianData)) {
            $mozillianData = $this->mozillianData;
        } else if ($cachedMozillianData = $this->getCache()->fetch('mozillian_data')) {
            $mozillianData = unserialize($cachedMozillianData);
        } else {


            /** @var Client $guzzle */
            $guzzle = $this->container->get('mozillians.client');
            $groupNames = $this->container->getParameter('mozillians.group_names');

            $usersResponse = $guzzle->get('api/v2/users?group=' . $groupNames)->send();
            $data = json_decode($usersResponse->getBody(true), true);

            $mozillianData = array();
            foreach ($data['results'] as $user) {
                // get data for each user
                $userData = json_decode($guzzle->get($user['_url'])->send()->getBody(true), true);

                if (!$userData['is_public']) {
                    continue;
                }

                $mozillian = array(
                    'photo_url' => '',
                    'href' => ''
                );

                if (!$this->isFieldVisible($userData, 'photo') || !isset($userData['photo']['150x150'])) {
                    continue;
                }
                $mozillian['photo_url'] = $userData['photo']['150x150'];

                if ($this->isFieldVisible($userData, 'story_link') && $userData['story_link']['value'] != '') {
                    $mozillian['href'] = $userData['story_link']['value'];
                } else {
                    $mozillian['href'] = $userData['url'];
                }

                $mozillianData[] = $mozillian;
            }

            $this->getCache()->save('mozillian_data', serialize($mozillianData), 3600);
        }

        $this->mozillianData = $mozillianData;

        return $mozillianData;
    }


    private function getEventFeed()
    {
        if ($cachedEvents = $this->getCache()->fetch('mozilla_reps_events')) {
            $events = unserialize($cachedEvents);
        } else {
            $response = $this->container->get('mozilla_reps.client')->get('events/period/future/search/switzerland/ical/')->send();

            $lines = explode("\n", $response->getBody(true));

            if(count($lines) > 5) {
                $this->ical = new ICal($lines);
                $events = $this->ical->events();
                foreach($events as $i => $event) {
                    $events[$i]['TIMESTAMP'] = $this->getStartDate($event['DTSTART']);
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

    /**
     * Handles the weird date time string we get from the iCal
     * Like: 20160207T154151DTSTART;TZID=Europe/Zurich:20160209T180000DTEND;TZID=Europe/Zurich:20160209T200000
     *
     * @param $datetimeString
     */
    private function getStartDate($datetimeString)
    {
        if (strpos($datetimeString, 'DTSTART') !== false) {
            // $date DTSTART;TZID= $tz : $date DTEND;TZID= $tz : $date
            $datetimeString = substr(
                $datetimeString,
                strpos($datetimeString, ':'),
                strpos($datetimeString, 'DTEND') - strpos($datetimeString, ':')
            );
        }

        return $this->ical->iCalDateToUnixTimestamp($datetimeString);

    }

    private function getCache()
    {
        return $this->container->get('cache');
    }

    private function isFieldVisible(array $userData, $fieldName, $visibility = 'Public')
    {
        if (!in_array($visibility, array('Public', 'Privileged', 'Mozillians'))) {
            throw new \LogicException('Invalid visibility passed to isFieldVisible method: ' . $visibility);
        }

        if ($userData[$fieldName]['privacy'] === $visibility) {
            return true;
        }

        return false;
    }
}
