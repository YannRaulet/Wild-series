<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\season;
use App\Entity\Episode;
use App\Entity\Actor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;
use App\Service\Slugify;



/**
 * @Route("/programs", name="program_")
 */
Class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     * ns de récupérer en contexte à ta vue program/show.html.twig.
     * @Route("/", name="index")       
     * @return Response 
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
     * The controller for the program add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify) : Response
    {

        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Use App\service\Slugify
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            // Persist Category Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('category_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
                'program' => $program
        ]);
    }

    /**
     * Show season of a program
     * @Route("/{programSlug}/season/{seasonId}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})

     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * Show episodes of a program
     * @Route("/{programSlug}/episode/{episodeSlug}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeSlug": "slug"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    /**
     * Show actors of a program
     * @Route("/{programSlug}/actor/{actorId}", name="actor_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("actor", class="App\Entity\Actor", options={"mapping": {"actorId": "id"}})
     * @return Response
     */
    public function showActor(Program $program, Actor $actor): Response
    {
        return $this->render('program/actor_show.html.twig', [
            'program' => $program,
            'actor' => $actor,
        ]);
    }

}