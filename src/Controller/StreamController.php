<?php

namespace App\Controller;

use App\Client\TwitchClient;
use App\Entity\Broadcaster;
use App\Repository\BroadcasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StreamController extends AbstractController
{
    #[Route('/stream/{broadcasterId}', name: 'stream')]
    public function index(EntityManagerInterface $entityManager, BroadcasterRepository $broadcasterRepository, TwitchClient $twitchClient, int $broadcasterId): Response
    {
        $broadcaster = $broadcasterRepository->findOneBy(['twitchId' => $broadcasterId]);
        if (!$broadcaster instanceof Broadcaster) {
            return $this->createNotFoundException();
        }
        if ($broadcaster->getFetchedAt() === null || (new \DateTimeImmutable())->getTimestamp() - $broadcaster->getFetchedAt()->getTimestamp() > 10) {
            $streamData = json_decode(json_encode($twitchClient->getStreamData($broadcasterId)), true);
            $broadcaster
                ->setStreamData($streamData)
                ->setFetchedAt(new \DateTimeImmutable())
            ;
            $entityManager->persist($broadcaster);
            $entityManager->flush();
        } else {
            $streamData = $broadcaster->getStreamData();
        }

        return $this->json($streamData);
    }
}
