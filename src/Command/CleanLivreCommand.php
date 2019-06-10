<?php

namespace App\Command;

use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanLivreCommand extends Command
{
    protected static $defaultName = 'app:clean:livre';

    private $livresRepository;
    private $entityManager;

    public function __construct(LivresRepository $livresRepository, EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
        $this -> livresRepository = $livresRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Supprime les livres sans utilisateurs')
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $livres = $this->livresRepository->findBy(['user' => null]);
        $total = count ($livres);

        $output->writeln("<info> Il y a $total livres !</info>");

        foreach ( $livres as $livre){
            $titre = $livre->getTitre();
            $output->writeln("<error>Suppression de $titre </error>");
            $this->entityManager->remove($livre);
        }

        $this->entityManager->flush();
         $output->writeln("<info> Les livres ont bien été supprimés</info>");



//        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }
//
//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
