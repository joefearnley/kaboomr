<?php

namespace Tests\Feature\Bookmarks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;
use App\Models\User;

class BookmarkMostUsedTagsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_bookmark_list_does_not_shows_most_used_tags_section()
    {
        $user = User::factory([
            'show_most_used_tags' => false
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('users.edit', $user));

        $response->assertDontSee('Most used tags:');
    }

    public function test_bookmark_list_shows_most_used_tags_section()
    {
        $user = User::factory()
            ->hasBookmarks(4)
            ->create();

        $user->bookmarks->each(function($bookmark) {
            $bookmark->tag(['dev', 'php']);
        });

        $response = $this->actingAs($user)->get(route('bookmarks.index'));

        $response->assertStatus(200);
        $response->assertSee('Most used tags:');
    }

    public function test_bookmark_list_shows_most_used_tags()
    {
        $user = User::factory()
            ->hasBookmarks(4)
            ->create();

        $user->bookmarks->each(function($bookmark) {
            $bookmark->tag(['dev', $this->faker->word]);
        });

        $response = $this->actingAs($user)->get(route('bookmarks.index'));

        $html = $response->getContent();

        $crawler = new Crawler($html);
        $this->assertEquals(1, $crawler->filter('div#most-used-tags')->count());
    }

    protected function see($text, $element = 'body')
    {
        $crawler = $this->client->getCrawler();
        $matches = $crawler->filter("{$element}:contains('{$text}')");
    
        $this->assertGreaterThan(0, count($matches), "Expected to see the text '$text' within an '$element' element.");
    }
}
