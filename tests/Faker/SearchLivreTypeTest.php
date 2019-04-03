<?php

namespace App\Tests\Faker;

use App\Form\SearchLivreType;
use PHPUnit\Framework\TestCase;

class SearchLivreTypeTest extends TestCase
{
    /**
     * @dataProvider etatProvider
     */
    public function testEtatContains($carburant)
    {
        $this->assertContains($carburant, SearchLivreType::ETAT);


    }

    public function testEtatNotContains()
    {
        $this->assertNotContains('ethanol', SearchLivreType::ETAT);
    }

    public function etatProvider()
    {
        return [
            ['Mauvais état'],
            ['Bon état'],
            ['Neuf'],
            ['Trés bon état'],
        ];
    }

    /**
     * @dataProvider categorieProvider
     */
    public function testCategorieContains($categorie)
    {
        $this->assertContains($categorie, SearchLivreType::CATEGORIE);


    }

    public function categorieProvider()
    {
        return [
            ['Roman'],
            ['Poésie'],
            ['Nouvelle'],
            ['Biographie'],
        ];
    }


}