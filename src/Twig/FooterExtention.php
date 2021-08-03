<?php

namespace App\Twig;

use App\Repository\GameRepository;
use App\Repository\PostRepository;
use App\Repository\SocietyRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FooterExtention extends AbstractExtension
{
    private GameRepository $gameRepository;
    private PostRepository $postRepository;
    private SocietyRepository $societyRepository;
    private Environment $env;

    /**
     * @param GameRepository $gameRepository
     * @param PostRepository $postRepository
     * @param SocietyRepository $societyRepository
     * @param Environment $env
     */
    public function __construct(GameRepository $gameRepository, PostRepository $postRepository, SocietyRepository $societyRepository, Environment $env)
    {
        $this->gameRepository = $gameRepository;
        $this->postRepository = $postRepository;
        $this->societyRepository = $societyRepository;
        $this->env = $env;
    }


    public function getFunctions()
    {
        return [
          new TwigFunction('show_stats', [$this, 'showStats']),
            new TwigFunction('show_society', [$this, 'showSociety']),
        ];
    }

    public function showSociety() {
        $society = $this->societyRepository->findAll()[0];

        return $this->env->render('partial/society.html.twig', [
            'society' => $society
        ]);
    }
    public function showStats() {
        $nbr_actu = count($this->postRepository->findAll());
        $nbr_game = count($this->gameRepository->findAll());

        return $this->env->render('partial/stats_footer.html.twig', [
           'nbr_game' => $nbr_game,
            'nbr_actu' => $nbr_actu
        ]);
    }
}