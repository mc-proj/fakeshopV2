<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\AdressesLivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use DateInterval;

class CleanAdressesLivraisonCommand extends Command
{
    protected static $defaultName = 'CleanAdressesLivraisonCommand';
    private const DELAI_MAX = "P2D"; //Period of 2 Days
    private $adressesLivraisonRepository;

    public function __construct(
        AdressesLivraisonRepository $adressesLivraisonRepository,
        EntityManagerInterface $entityManager) 
    {
        $this->adressesLivraisonRepository = $adressesLivraisonRepository;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Efface toutes les entites AdressesLivraison non attribuees (attribut actif == 0) et dont la derniere modification est anterieure à une limite fixée')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        /*if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }*/

        $adresses = $this->adressesLivraisonRepository->findBy(["actif" => false]);
        $limite = new DateTime();
        $limite->sub(new DateInterval(self::DELAI_MAX));
        $perimee_detectee = false;

        foreach($adresses as $adresse) {

            if($adresse->getDerniereModification() < $limite) {

                $perimee_detectee = true;
                $this->entityManager->remove($adresse);
            }
        }

        if($perimee_detectee) {

            $this->entityManager->flush();
        }

        //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
