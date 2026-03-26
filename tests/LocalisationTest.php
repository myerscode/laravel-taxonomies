<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Laravel\Taxonomies\Taxonomy;
use Myerscode\Laravel\Taxonomies\Term;

final class LocalisationTest extends TestCase
{
    public function testIsTranslatedToDefaultLocal(): void
    {
        $model = Term::add('Foo Bar');
        $taxonomy = Taxonomy::add('Hello World');

        app()->setLocale('cy');

        $this->assertEquals('Tymor un', $model->translate()->name);
        $this->assertEquals('Helo Byd', $taxonomy->translate()->name);
    }

    public function testTaxonomyIsTranslated(): void
    {
        $model = Taxonomy::add('Hello World');

        $translated = $model->translate('fr');

        $this->assertEquals('Bonjour le monde', $translated->name);

        $translated->save();

        $this->assertEquals('Hello World', $model->name);
    }

    public function testTaxonomyReturnsDefaultForMissingTranslation(): void
    {
        $model = Taxonomy::add('A Random Taxonomy');

        $translated = $model->translate('fr');

        $this->assertEquals('A Random Taxonomy', $translated->name);
    }

    public function testTermIsTranslated(): void
    {
        $model = Term::add('Foo Bar');

        $translated = $model->translate('fr');

        $this->assertEquals('Foo La La', $translated->name);

        $translated->save();

        $this->assertEquals('Foo Bar', $model->name);
    }

    public function testTermReturnsDefaultForMissingTranslation(): void
    {
        $model = Term::add('A Random Term');

        $translated = $model->translate('fr');

        $this->assertEquals('A Random Term', $translated->name);
    }
}
