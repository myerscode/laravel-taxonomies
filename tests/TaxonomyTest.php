<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Laravel\Taxonomies\Term;
use Myerscode\Laravel\Taxonomies\Taxonomy;

final class TaxonomyTest extends TestCase
{
    private Taxonomy $taxonomy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taxonomy = Taxonomy::create(['name' => 'Foo']);
    }

    public function testAddingMultipleTermsToTaxonomy(): void
    {
        $this->taxonomy->addTerms(['Foo', 'Bar']);
        $this->taxonomy->refresh();

        $terms = Term::whereIn('name', ['Foo', 'Bar'])->get();

        $this->assertCount(2, $this->taxonomy->terms);
        $this->assertEquals($terms->toArray(), $this->taxonomy->terms->toArray());
    }

    public function testAddingTermToTaxonomy(): void
    {

        $this->taxonomy->addTerm('Test');
        $this->taxonomy->refresh();

        $term = Term::where(['name' => 'Test'])->firstOrFail();

        $this->assertCount(1, $this->taxonomy->terms);
        $this->assertEquals($term->toArray(), $this->taxonomy->terms->first()->toArray());
    }

    public function testAddTaxonomy(): void
    {
        Taxonomy::add('Foo');
        Taxonomy::add('Bar');
        Taxonomy::add('Hello World');
        Taxonomy::add(['Foo', 'Bar']);

        $this->assertCount(3, Taxonomy::all());
    }

    public function testAttachTermToTaxonomy(): void
    {

        $term = Term::firstOrCreate(['name' => 'Test']);

        $this->taxonomy->attachTerm($term);
        $this->taxonomy->refresh();

        $this->assertCount(1, $this->taxonomy->terms);
        $this->assertEquals($term->toArray(), $this->taxonomy->terms->first()->toArray());
    }

    public function testCreateTaxonomy(): void
    {
        Taxonomy::create(['name' => 'Bar']);

        $this->assertCount(2, Taxonomy::all());
    }

    public function testEnsureTaxMovesWhenAttachedToTaxonomy(): void
    {

        $term = Term::firstOrCreate(['name' => 'Test']);

        $this->taxonomy->attachTerm($term);
        $this->taxonomy->refresh();

        $this->assertCount(1, $this->taxonomy->terms);
        $this->assertEquals($term->toArray(), $this->taxonomy->terms->first()->toArray());

        $tax = Taxonomy::create(['name' => 'Foo']);
        $tax->attachTerm($term);

        $tax->refresh();

        $this->taxonomy->refresh();

        $this->assertCount(1, $tax->terms);
        $this->assertCount(0, $this->taxonomy->terms);
    }
}
