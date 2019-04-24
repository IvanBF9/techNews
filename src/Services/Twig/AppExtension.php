<?php

namespace App\Services\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{#Création d'un filtre twig:
    public function getFilters()
    {
        return [
            new TwigFilter('summarize', function ($contenu) {


                $string = strip_tags($contenu);

                if (strlen($string) > 150) {


                    $stringCut = substr($string, 0, 150);


                    $string = substr($stringCut, 0, strrpos($stringCut, ' '));
                }

                return $string . '...';

            }, array('is_safe' => array('html')))
        ];
    }

}