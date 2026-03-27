<?php

declare(strict_types=1);

namespace Tests;

use Myerscode\Laravel\Taxonomies\Taxonomy;
use Tests\Support\Post;

final class HasTaxonomyTest extends TestCase
{
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create(['slug' => 'hello-world', 'title' => 'Hello World']);
    }

    public function test_model_can_add_multiple_terms(): void
    {
        $this->post->addTerms(['Foo', 'Bar']);

        $this->assertCount(2, $this->post->terms);
        $this->assertEquals(['Foo', 'Bar'], $this->post->terms->pluck(['name'])->toArray());
        $this->assertEquals(['foo', 'bar'], $this->post->terms->pluck(['slug'])->toArray());
    }

    public function test_model_can_add_term(): void
    {
        $this->post->addTerm('Foo');

        $this->assertCount(1, $this->post->terms);
        $this->assertEquals(['Foo'], $this->post->terms->pluck(['name'])->toArray());
        $this->assertEquals(['foo'], $this->post->terms->pluck(['slug'])->toArray());
    }

    public function test_model_can_add_term_to_taxonomy(): void
    {
        $this->post->addTerm('Hello', 'Foo Bar');
        $this->post->addTerm('World', 'Foo Bar');

        $taxonomy = Taxonomy::where('name', 'Foo Bar')->get()->first();

        $this->assertCount(2, $this->post->terms);
        $this->assertCount(2, $taxonomy->terms);
    }

    public function test_model_can_add_term_to_taxonomy_by_instance(): void
    {
        $taxonomy = Taxonomy::findOrAdd('Foo Bar');
        $this->post->addTerm('Hello', $taxonomy);
        $this->post->addTerm('World', $taxonomy);

        $this->assertCount(2, $this->post->terms);
        $this->assertCount(2, $taxonomy->terms);
    }

    public function test_model_can_add_term_to_taxonomy_by_its_id(): void
    {
        $taxonomy = Taxonomy::findOrAdd('Foo Bar');
        $this->post->addTerm('Hello', 1);
        $this->post->addTerm('World', 1);

        $this->assertCount(2, $this->post->terms);
        $this->assertCount(2, $taxonomy->terms);
    }

    public function test_model_can_add_term_to_taxonomy_by_slug(): void
    {
        $this->post->addTerm('Hello', 'foo-bar');
        $this->post->addTerm('World', 'foo-bar');

        $taxonomy = Taxonomy::where('name', 'foo-bar')->get()->first();

        $this->assertCount(2, $this->post->terms);
        $this->assertCount(2, $taxonomy->terms);
    }


    public function test_model_can_find_by_all_terms(): void
    {
        $model1 = Post::create(['slug' => 'test-one', 'title' => 'Test One']);
        $model2 = Post::create(['slug' => 'test-two', 'title' => 'Test Two']);
        $model3 = Post::create(['slug' => 'test-three', 'title' => 'Test Three']);

        $model1->addTerm('Hello');
        $model1->addTerm('World');

        $model2->addTerm('World');
        $model3->addTerm('Foo');
        $model3->addTerm('Bar');

        $this->assertCount(1, Post::withAllTerms(['Hello', 'World']));
        $this->assertCount(0, Post::withAllTerms(['Hello', 'World', 'Foo', 'Bar']));
    }

    public function test_model_can_find_by_all_terms_in_taxonomy(): void
    {
        $model1 = Post::create(['slug' => 'test-one', 'title' => 'Test One']);
        $model2 = Post::create(['slug' => 'test-two', 'title' => 'Test Two']);
        $model3 = Post::create(['slug' => 'test-three', 'title' => 'Test Three']);

        $model1->addTerm('Foo');
        $model1->addTerm('Bar');
        $model1->addTerm('Foo', 'aaa');

        $model2->addTerm('Foo', 'aaa');
        $model2->addTerm('Bar', 'aaa');

        $model3->addTerm('Foo', 'bbb');
        $model3->addTerm('Bar', 'bbb');

        $model1->addTerm('Bar', 'bbb');

        $this->assertCount(1, Post::withAllTerms(['Foo', 'Bar']));
        $this->assertCount(1, Post::withAllTerms(['Foo', 'Bar'], 'aaa'));
    }

    public function test_model_can_find_by_any_terms(): void
    {
        $model1 = Post::create(['slug' => 'test-one', 'title' => 'Test One']);
        $model2 = Post::create(['slug' => 'test-two', 'title' => 'Test Two']);
        $model3 = Post::create(['slug' => 'test-three', 'title' => 'Test Three']);

        $model1->addTerm('Hello');
        $model1->addTerm('World');

        $model2->addTerm('World');
        $model3->addTerm('Foo');
        $model3->addTerm('Bar');

        $this->assertCount(2, Post::withAnyTerms(['Hello', 'World']));
    }

    public function test_model_can_find_by_any_terms_from_taxonomy(): void
    {
        $model1 = Post::create(['slug' => 'test-one', 'title' => 'Test One']);
        $model2 = Post::create(['slug' => 'test-two', 'title' => 'Test Two']);
        $model3 = Post::create(['slug' => 'test-three', 'title' => 'Test Three']);

        $model1->addTerm('Foo');
        $model1->addTerm('Bar');

        $model2->addTerm('Hello');
        $model2->addTerm('Bar');

        $model3->addTerm('Foo');
        $model1->addTerm('Foo', 'aaa');
        $model1->addTerm('Bar', 'bbb');

        $this->assertCount(3, Post::withAnyTerms(['Foo', 'Bar']));
    }

    public function test_model_can_remove_terms(): void
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

    public function test_model_can_sync_terms(): void
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

}
