<?php

namespace Tests;

use Myerscode\Laravel\Taxonomies\Exceptions\UnsupportedModelDataException;
use Myerscode\Laravel\Taxonomies\Taxonomy;
use Myerscode\Laravel\Taxonomies\Term;

class TermTest extends TestCase
{

    public function testAddTerm()
    {
        Term::add('Foo');
        Term::add(['name' => 'Bar']);

        $this->assertCount(2, Term::all());
    }

    public function testAddTermWithAlternateSlug()
    {
        Term::add(['slug' => 'bar', 'name' => 'Foo']);
        $term = Term::findBySlug('bar');
        $invalidTerm = Term::findBySlug('foo');

        $this->assertCount(1, Term::all());
        $this->assertInstanceOf(Term::class, $term);
        $this->assertEquals('Foo', $term->name);
        $this->assertEquals(null, $invalidTerm);
    }

    public function testFindTermBySlug()
    {
        Term::add('Hello');
        Term::add('World');

        $term = Term::findBySlug('hello');
        $this->assertInstanceOf(Term::class, $term);
        $this->assertEquals('Hello', $term->name);
    }

    public function testFindTermByName()
    {
        Term::add('Hello World');
        Term::add('Foo Bar');

        $term = Term::findByName('Hello World');
        $this->assertInstanceOf(Term::class, $term);
        $this->assertEquals('hello-world', $term->slug);
    }

    public function testUnsupportedTermDataThrowsException()
    {
        $this->expectException(UnsupportedModelDataException::class);
        Term::add(new \stdClass());
    }

    public function testAddTermToTaxonomy()
    {
        Term::addToTaxonomy('Hello', 'World');
        Term::addToTaxonomy('Foo', 'Bar');

        $this->assertCount(2, Term::all());
    }

    public function testTermBelongsToTaxonomy()
    {
        Term::addToTaxonomy('Hello', 'World');
        $term = Term::findByName('Hello');

        $this->assertInstanceOf(Taxonomy::class, $term->taxonomy);
        $this->assertEquals('World', $term->taxonomy->name);
    }

}
