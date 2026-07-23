<?php

use Inertia\Testing\AssertableInertia as Assert;

test('news index renders with articles from config', function () {
    $this->get('/news')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/News/Pages/NewsIndex')
            ->has('articles', count(config('lab-news.articles')))
            ->has('articles.0.slug')
            ->has('articles.0.title')
            ->has('articles.0.href')
        );
});

test('news show renders the matching article', function () {
    $slug = config('lab-news.articles')[0]['slug'];

    $this->get("/news/{$slug}")
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Features/News/Pages/NewsShow')
            ->where('article.slug', $slug)
            ->has('article.body')
            ->has('related')
        );
});

test('news show 404s for an unknown slug', function () {
    $this->get('/news/does-not-exist')->assertNotFound();
});
