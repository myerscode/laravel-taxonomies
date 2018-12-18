<?php

namespace Tests;

use Myerscode\Laravel\Taxonomies\Taxonomy;
use Myerscode\Laravel\Taxonomies\Term;

class LocalisationTest extends TestCase
{

    public function testTermIsTranslated()
    {
        $term = Term::add('Foo Bar');

        $translated = $term->translate('fr');

        $this->assertEquals('Foo La La', $translated->name);

        $translated->save();

        $this->assertEquals('Foo Bar', $term->name);
    }

    public function testTaxonomyIsTranslated()
    {
        $term = Taxonomy::add('Hello World');

        $translated = $term->translate('fr');

        $this->assertEquals('Bonjour le monde', $translated->name);

        $translated->save();

        $this->assertEquals('Hello World', $term->name);
    }

    public function testTermReturnsDefaultForMissingTranslation()
    {
        $term = Term::add('A Random Term');

        $translated = $term->translate('fr');

        $this->assertEquals('A Random Term', $translated->name);
    }

    public function testTaxonomyReturnsDefaultForMissingTranslation()
    {
        $term = Taxonomy::add('A Random Taxonomy');

        $translated = $term->translate('fr');

        $this->assertEquals('A Random Taxonomy', $translated->name);
    }


    public function testIsTranslatedToDefaultLocal()
    {
        $term = Term::add('Foo Bar');
        $taxonomy = Taxonomy::add('Hello World');

        app()->setLocale('cy');

        $this->assertEquals('Tymor un', $term->translate()->name);
        $this->assertEquals('Helo Byd', $taxonomy->translate()->name);
    }
}