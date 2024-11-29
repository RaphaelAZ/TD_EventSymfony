<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/events', name: 'events')]
    public function listEvents(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('events/event-list.html.twig', ['events' => $events]);
    }

    #[Route('/events/{id}', name: 'events_detail', requirements: ['id' => '\d+'])]
    public function viewEvent(int $id, EventRepository $eventRepository): Response 
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $participants = $event->getParticipants();
        return $this->render('events/event-detail.html.twig', [
            'event' => $event,
            'participants' => $participants
        ]);
    }
}
