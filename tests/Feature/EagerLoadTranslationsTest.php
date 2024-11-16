<?php

namespace Omaralalwi\LexiTranslate\Tests\Feature;

use Omaralalwi\LexiTranslate\Tests\Models\LexiTranslatePost as Post;
use Omaralalwi\LexiTranslate\Tests\TestCase;

class EagerLoadTranslationsTest extends TestCase
{
    /** @test */
    public function it_can_eager_load_translations()
    {
        $post = Post::create(['title' => 'Original Title']);

        $post->translations()->create([
            'locale' => 'ar',
            'column' => 'title',
            'text' => 'العنوان بالعربية',
        ]);

        $post->translations()->create([
            'locale' => 'en',
            'column' => 'title',
            'text' => 'English Title',
        ]);

        $loadedPost = Post::with('translations')->find($post->id);
        $this->assertThat($loadedPost->relationLoaded('translations'), $this->isTrue(), 'Expected translations relation to be loaded');
        $this->assertEquals('العنوان بالعربية', $loadedPost->translations->firstWhere('locale', 'ar')->text);
        $this->assertEquals('English Title', $loadedPost->translations->firstWhere('locale', 'en')->text);
    }
}
