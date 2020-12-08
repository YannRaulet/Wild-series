<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\season;
use App\Repository\SeasonRepository;



/**
 * @Route("/programs", name="program_")
 */
Class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     * ns de récupérer en contexte à ta vue program/show.html.twig.
     * @Route("/", name="index")        $seasonRepository->

     * @return Response AThis controller must have an optionnal name parameter (in the URL path).
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * Getting a program by id
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id' => $id]);
        
        if(!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' .$id . 'found in program\'s table.'
            );
        }
        
        return $this->render('program/show.html.twig', [
                'program' => $program
            ]);
    }

    /**
     * Show season of a program
     * @Route("/{programId}/season/{seasonId}", name="season_show")
     */
    public function showSeason(int $programId, int $seasonId, SeasonRepository $seasonRepository): Response
    {
        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->find(['id' => $seasonId]);
        

        return $this->render('program/season_show.html.twig', [
            'programId' => $programId,
            'seasonId' => $seasonId,
            'season' => $season
        ]);
    }
}