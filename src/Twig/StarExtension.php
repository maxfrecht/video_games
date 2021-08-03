<?php

namespace App\Twig;

use App\Entity\Game;
use App\Repository\GameRepository;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StarExtension extends AbstractExtension
{
    private Environment $env;
    private GameRepository $gameRepository;

    /**
     * @param Environment $env
     * @param GameRepository $gameRepository
     */
    public function __construct(Environment $env, GameRepository $gameRepository)
    {
        $this->env = $env;
        $this->gameRepository = $gameRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('show_stars', [$this, 'showStars'])
        ];
    }

    public function showStars(Game $game)
    {
        $stars = '';
        $note = $game->getNoteGlobal();
        for($i = 0; $i < $note; $i++) {
            $stars.= '★';
        }
        while(mb_strlen($stars) < 10) {
            $stars.= '☆';
        }

        return $stars;
    }

}