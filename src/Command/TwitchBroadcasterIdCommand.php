<?php

namespace App\Command;

use App\Client\TwitchClient;
use App\Entity\Broadcaster;
use App\Repository\BroadcasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:twitch:broadcaster-id',
    description: 'Get broadcaster id for a given channel name',
)]
class TwitchBroadcasterIdCommand extends Command
{
    public function __construct(private TwitchClient $twitchClient, private EntityManagerInterface $entityManager, private BroadcasterRepository $broadcastRepository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('channel', InputArgument::REQUIRED, 'Channel name eg: otplol_')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $channelName = $input->getArgument('channel');
        $broadcasterId = $this->twitchClient->getBroadcasterId($channelName);
        if ($broadcasterId === null) {
            $io->error(sprintf('Broadcaster corresponding to %s not found.', $channelName));
        } else {
            $io->success(sprintf('Broadcaster id corresponding to %s is : %s.', $channelName, $broadcasterId));
            $broadcaster = $this->broadcastRepository->findOneBy(['twitchId' => $broadcasterId]);
            if ($broadcaster instanceof Broadcaster) {
                $io->info('This broadcaster is already registered.');
            } else {
                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('Do you want to create a broadcaster corresponding to this id in database ?', false);
                if ($helper->ask($input, $output, $question)) {
                    $broadcaster = new Broadcaster();
                    $broadcaster
                        ->setTwitchId($broadcasterId)
                        ->setCreatedAt(new \DateTimeImmutable())
                    ;
                    $this->entityManager->persist($broadcaster);
                    $this->entityManager->flush();
                }
            }
        }

        return Command::SUCCESS;
    }
}
