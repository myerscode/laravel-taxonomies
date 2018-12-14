<?php

namespace Tests;

use Tests\Support\Post;

class HasTaxonomyTest extends TestCase
{

    /**
     * @var Post
     */
    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = Post::create(['slug' => 'hello-world', 'title' => 'Hello World', 'content' => 'Foo bar']);
    }

    public function testModelCanAddTerm()
    {
        $this->post->addTerm('Foo');

        $this->assertCount(1, $this->post->terms);
        $this->assertEquals(['Foo'], $this->post->terms->pluck(['name'])->toArray());
        $this->assertEquals(['foo'], $this->post->terms->pluck(['slug'])->toArray());
    }

    public function testModelCanAddMultipleTerms()
    {
        $this->post->addTerms(['Foo', 'Bar']);

        $this->assertCount(2, $this->post->terms);
        $this->assertEquals(['Foo', 'Bar'], $this->post->terms->pluck(['name'])->toArray());
        $this->assertEquals(['foo', 'bar'], $this->post->terms->pluck(['slug'])->toArray());
    }

    public function testModelCanSyncTerms()
    {
        $this->post->addTerms(['Foo', 'Bar', 'Hello', 'World']);
        $this->assertCount(4, $this->post->terms);
        $this->assertEquals(['Foo', 'Bar', 'Hello', 'World'], $this->post->terms->pluck('name')->toArray());

        $this->post->syncTerms(['Foo', 'Hello']);
        $this->post->refresh();
        $this->assertCount(2, $this->post->terms);
        $this->assertEquals(['Foo', 'Hello'], $this->post->terms->pluck('name')->toArray());

        $this->post->syncTerms('Bar');
        $this->post->refresh();
        $this->assertCount(1, $this->post->terms);
        $this->assertEquals(['Bar'], $this->post->terms->pluck('name')->toArray());
    }

    public function testModelCanRemoveTerms()
    {
        $this->post->addTerms(['Foo', 'Bar', 'Hello', 'World']);
        $this->assertCount(4, $this->post->terms);

        $this->post->detachTerms(['Hello', 'World']);
        $this->post->refresh();
        $this->assertCount(2, $this->post->terms);

        $this->post->detachTerms('Foo');
        $this->post->refresh();
        $this->assertCount(1, $this->post->terms);
    }
}