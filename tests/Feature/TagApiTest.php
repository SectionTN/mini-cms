<?php

use App\Models\Post;
use App\Models\Tag;

it('lists the tags with their published post count', function () {
    $tag = Tag::factory()->create();
    $tag->posts()->attach(Post::factory()->published()->count(2)->create());
    $tag->posts()->attach(Post::factory()->create());

    $this->getJson('/api/tags')
        ->assertOk()
        ->assertJsonStructure(['data' => ['*' => ['id', 'name', 'slug', 'posts_count']]])
        ->assertJsonPath('data.0.slug', $tag->slug)
        ->assertJsonPath('data.0.posts_count', 2);
});

it('filters the posts by tag slug', function () {
    $tag = Tag::factory()->create();
    $tagged = Post::factory()->published()->create();
    Post::factory()->published()->create();
    $tagged->tags()->attach($tag);

    $this->getJson('/api/posts?tag='.$tag->slug)
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $tagged->id);
});

it('returns a JSON 404 for an unknown tag', function () {
    $this->getJson('/api/tags/999999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});

it('keeps the tag filter in the next page link', function () {
    $tag = Tag::factory()->create();
    $tag->posts()->attach(Post::factory()->published()->count(3)->create());

    $next = $this->getJson('/api/posts?per_page=2&tag='.$tag->slug)
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->json('links.next');

    expect($next)->toContain('tag='.$tag->slug);
});
