<?php

namespace Tests;

use Myerscode\Laravel\Taxonomies\Term;
use Myerscode\Laravel\Taxonomies\Taxonomy;

class TaxonomyTest extends TestCase
{

    /**
     * @var Taxonomy
     */
    protected $taxonomy;

    public function setUp()
    {
        parent::setUp();

        $this->taxonomy = Taxonomy::create(['name' => 'Foo']);
    }

    public function testCreateTaxonomy()
    {
        Taxonomy::create(['name' => 'Bar']);

        $this->assertCount(2, Taxonomy::all());
    }

    public function testAttachTermToTaxonomy()
    {

        $term = Term::firstOrCreate(['name' => 'Test']);

        $this->taxonomy->attachTerm($term);
        $this->taxonomy->refresh();

        $this->assertCount(1, $this->taxonomy->terms);
        $this->assertEquals($term->toArray(), $this->taxonomy->terms->first()->toArray());
    }

    public function testEnsureTaxMovesWhenAttachedToTaxonomy()
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

    public function testAddingTermToTaxonomy()
    {

        $this->taxonomy->addTerm('Test');
        $this->taxonomy->refresh();
        $term = Term::where(['name' => 'Test'])->firstOrFail();

        $this->assertCount(1, $this->taxonomy->terms);
        $this->assertEquals($term->toArray(), $this->taxonomy->terms->first()->toArray());
    }

    public function testAddingMultipleTermsToTaxonomy()
    {
        $this->taxonomy->addTerms(['Foo', 'Bar']);
        $this->taxonomy->refresh();

        $terms = Term::whereIn('name', ['Foo', 'Bar'])->get();

        $this->assertCount(2, $this->taxonomy->terms);
        $this->assertEquals($terms->toArray(), $this->taxonomy->terms->toArray());
    }
}