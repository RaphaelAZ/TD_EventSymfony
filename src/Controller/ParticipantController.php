<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class ParticipantController extends AbstractController
{
    #[Route('/events/{id}/participant/new', name: 'add_participant')]
    public function index($id, EventRepository $eventRepository, Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->findOneBy(['id' => $id]);
        $participant = new Participant();
        $formParticipant = $this->createForm(ParticipantType::class, $participant);
        $formParticipant->handleRequest($request);

        if($formParticipant->isSubmitted() && $formParticipant->isValid()) {
            $participant->setEvent($event);
            $entityManager->persist($participant);
            $entityManager->flush();

            $session->set('participant', $participant);
            $session->set('event', $event);

            return $this->redirectToRoute('add_participant_success', [
                'id' => $event->getId()
            ]);
        }

        return $this->render('events/new-participant.html.twig', [
            'form' => $formParticipant
        ]);
    }

    #[Route('/events/{id}/participant/success', name: 'add_participant_success')]
    public function success(SessionInterface $session): Response
    {
        $participant = $session->get('participant');
        $event = $session->get('event');
        if ($participant && $event) {
            return $this->render('events/success/new-participant-success.html.twig', [
                'participant' => $participant,
                'event' => $event
            ]);
        }
        return $this->redirectToRoute(route: 'add_participant');
    }
}
