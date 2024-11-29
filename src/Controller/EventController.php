<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Service\DistanceCalculator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    private $distanceCalculator;

    public function __construct(DistanceCalculator $distanceCalculator)
    {
        $this->distanceCalculator = $distanceCalculator;
    }

    #[Route('/events', name: 'events')] // Affiche la liste des évènements
    public function listEvents(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('events/event-list.html.twig', ['events' => $events]);
    }

    #[Route('/events/{id}', name: 'events_detail', requirements: ['id' => '\d+'])] // Affiche le détail d'un évènement
    public function viewEvent(int $id, EventRepository $eventRepository): Response 
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $participants = $event->getParticipants();
        return $this->render('events/event-detail.html.twig', [
            'event' => $event,
            'participants' => $participants
        ]);
    }

    #[Route('/events/{id}/distance?lat={lat}&lon={lon}', name: 'distance_calculator', requirements: ['id' => '\d+'])] // Action qui donne la distance entre 2 positions
    public function calculateDistance($id, $lat, $lon, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $eventLat = $event->getLatitude();
        $eventLon = $event->getLongitude();
        $distance = $this->distanceCalculator->calculateDistance($lat, $lon, $eventLat, $eventLon);

        return new Response("La distance entre vous et l'évènement est de " . round($distance, 2) . " km.");
    }

    #[Route('/events/new', name: 'add_event')]
    public function index(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $formEvent = $this->createForm(EventType::class, $event);
        $formEvent->handleRequest($request);

        if($formEvent->isSubmitted() && $formEvent->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            $session->set('event', $event);

            return $this->redirectToRoute('add_event_success', [
                'id' => $event->getId()
            ]);
        }

        return $this->render('events/new-event.html.twig', [
            'form' => $formEvent
        ]);
    }

    #[Route('/events/success', name: 'add_event_success')]
    public function success(SessionInterface $session): Response
    {
        $event = $session->get('event');
        if ($event) {
            return $this->render('events/success/new-event-success.html.twig', [
                'event' => $event
            ]);
        }
        return $this->redirectToRoute(route: 'add_event');
    }
}
