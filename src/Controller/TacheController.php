<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TacheController extends AbstractController
{
    #[Route('/taches', name: 'app_tache')]
    public function index(TacheRepository $tacheRepository): Response
    {
        $taches = $tacheRepository->findBy([], ['terminee' => 'ASC', 'dateCreation' => 'DESC']);
        
        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/taches/ajouter', name: 'app_tache_ajouter')]
    public function ajouter(EntityManagerInterface $em): Response
    {
        $tache = new Tache();
        $tache->setTitre('Exemple de tâche');
        $tache->setDescription('Ceci est une description optionnelle');
        $tache->setTerminee(false);
        $tache->setDateCreation(new \DateTime());

        $em->persist($tache);
        $em->flush();

        $this->addFlash('success', 'Tâche créée avec succès !');
        
        return $this->redirectToRoute('app_tache');
    }

    #[Route('/taches/{id}', name: 'app_tache_detail', requirements: ['id' => '\d+'])]
    public function detail(Tache $tache): Response
    {
        return $this->render('tache/detail.html.twig', [
            'tache' => $tache,
        ]);
    }
    #[Route('/taches/{id}/terminer', name: 'app_tache_terminer', requirements: ['id' => '\d+'])]
    public function terminer(Tache $tache, EntityManagerInterface $em): Response
    {
        $tache->setTerminee(true);
        $em->flush();
        
        $this->addFlash('success', 'Tâche marquée comme terminée !');
        
        return $this->redirectToRoute('app_tache');
    }
}
