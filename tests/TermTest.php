<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Laravel\Taxonomies\Exceptions\UnsupportedModelDataException;
use Myerscode\Laravel\Taxonomies\Taxonomy;
use Myerscode\Laravel\Taxonomies\Term;
use stdClass;

final class TermTest extends TestCase
{
    public function testAddTerm(): void
    {
        Term::add('Foo');
        Term::add(['name' => 'Bar']);

        $this->assertCount(2, Term::all());
    }

    public function testAddTermToTaxonomy(): void
    {
        Term::addToTaxonomy('Hello', 'World');
        Term::addToTaxonomy('Foo', 'Bar');

        $this->assertCount(2, Term::all());
    }

    public function testAddTermWithAlternateSlug(): void
    {
        Term::add(['slug' => 'bar', 'name' => 'Foo']);
        $term = Term::findBySlug('bar');
        $invalidTerm = Term::findBySlug('foo');

        $this->assertCount(1, Term::all());
        $this->assertInstanceOf(Term::class, $term);
        $this->assertEquals('Foo', $term->name);
        $this->assertEquals(null, $invalidTerm);
    }

    public function testFindTermByName(): void
    {
        Term::add('Hello World');
        Term::add('Foo Bar');

        $term = Term::findByName('Hello World');
        $this->assertInstanceOf(Term::class, $term);
        $this->assertEquals('hello-world', $term->slug);
    }

    public function testFindTermBySlug(): void
    {
        Term::add('Hello');
        Term::add('World');

        $term = Term::findBySlug('hello');
        $this->assertInstanceOf(Term::class, $term);
        $this->assertEquals('Hello', $term->name);
    }

    public function testTermBelongsToTaxonomy(): void
    {
        Term::addToTaxonomy('Hello', 'World');
        $term = Term::findByName('Hello');

        $this->assertInstanceOf(Taxonomy::class, $term->taxonomy);
        $this->assertEquals('World', $term->taxonomy->name);

        $term = Term::add('Foo');

        $this->assertNull($term->taxonomy);
    }

    public function testUnsupportedTermDataThrowsException(): void
    {
        $this->expectException(UnsupportedModelDataException::class);
        Term::add(new stdClass());
    }

}
