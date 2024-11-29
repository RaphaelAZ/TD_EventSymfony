<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Service\DistanceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    private $distanceCalculator;

    public function __construct(DistanceCalculator $distanceCalculator)
    {
        $this->distanceCalculator = $distanceCalculator;
    }

    #[Route('/events', name: 'events')]
    public function listEvents(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('events/event-list.html.twig', ['events' => $events]);
    }

    #[Route('/events/{id}', name: 'events_detail')]
    public function viewEvent(int $id, EventRepository $eventRepository): Response 
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $participants = $event->getParticipants();
        return $this->render('events/event-detail.html.twig', [
            'event' => $event,
            'participants' => $participants
        ]);
    }

    #[Route('/events/{id}/distance?lat={lat}&lon={lon}', name: 'distance_calculator')]
    public function calculateDistance($id, $lat, $lon, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $eventLat = $event->getLatitude();
        $eventLon = $event->getLongitude();
        $distance = $this->distanceCalculator->calculateDistance($lat, $lon, $eventLat, $eventLon);

        return new Response("La distance entre vous et l'évènement est de " . round($distance, 2) . " km.");
    }
}
