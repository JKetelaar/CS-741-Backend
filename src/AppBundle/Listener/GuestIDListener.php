<?php
/**
 * @author JKetelaar
 */

namespace AppBundle\Listener;

use AppBundle\Service\CartHelper;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class GuestIDListener
 * @package AppBundle\Listener
 */
class GuestIDListener
{
    /**
     * @var CartHelper
     */
    private $cartHelper;

    /**
     * GuestIDListener constructor.
     * @param CartHelper $cartHelper
     */
    public function __construct(CartHelper $cartHelper)
    {
        $this->cartHelper = $cartHelper;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($request->cookies->get('guestid') === null) {
            $guestID = $this->cartHelper->getUniqueGuestID();

            $cookie = new Cookie('guestid', $guestID, new \DateTime('+30 days'));
            $response->headers->setCookie($cookie);
        }
    }
}