<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\PaniersRepository;
use App\Repository\PaniersProduitsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use DateInterval;

class CleanVisiteursCommand extends Command
{
    protected static $defaultName = 'CleanVisiteurs';
    private const DELAI_MAX = "P2D"; //Period of 2 Days
    private $paniersRepository;
    private $paniersProduitsRepository;
    private $usersRepository;
    private $session;

    public function __construct(PaniersRepository $paniersRepository,
                                PaniersProduitsRepository $paniersProduitsRepository,
                                UsersRepository $usersRepository,
                                EntityManagerInterface $entityManager) 
    {
        $this->paniersRepository = $paniersRepository;
        $this->paniersProduitsRepository = $paniersProduitsRepository;
        $this->usersRepository = $usersRepository;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Efface tout les objets users des visiteurs non identifies dont la derniere activite est anterieure à une limite fixée')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /*$arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }*/

        $users = $this->usersRepository->findBy(["pseudo" => "user"]);
        $limite = new DateTime();
        $limite->sub(new DateInterval(self::DELAI_MAX));
        $visiteurs_perimes = [];

        foreach($users as $user) {

            $panier = $this->paniersRepository->findOneBy(["users_id" => $user]);

            if($panier == null) {

                if($user->getDateModification() < $limite) {

                    array_push($visiteurs_perimes, $user);
                }
            }
            
            else if($panier->getDateModification() < $limite) {

                $paniers_produits = $this->paniersProduitsRepository->findBy(["paniers_id" => $panier]);

                foreach($paniers_produits as $panier_produit) {

                    $panier_produit->setPaniersId(null);
                    $panier_produit->setProduitsId(null);
                    $this->entityManager->remove($panier_produit);
                    $this->entityManager->flush();

                }
                
                $this->entityManager->remove($panier);
                $this->entityManager->flush();
                array_push($visiteurs_perimes, $user);
            }
        }
        
        foreach($visiteurs_perimes as $visiteur_perime) {

            $this->entityManager->remove($visiteur_perime);
            $this->entityManager->flush();
        }

        //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
