<?php

namespace Omaralalwi\LexiTranslate\Traits;

use Illuminate\Support\Facades\Config;
use Omaralalwi\LexiTranslate\Enums\Language;
use Omaralalwi\LexiTranslate\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

trait LexiTranslatable
{
    use HasCache;

    public function getTranslatableFields(): array
    {
        return $this->translatableFields ?? [];
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function translate(string $column, ?string $locale = null): ?string
    {
        $supportedLocales = Config::get('lexi-translate.supported_locales', []);
        $locale = $locale && in_array($locale, $supportedLocales) ? $locale : app()->getLocale();

        $modelType = class_basename($this);
        $cachePrefix = self::getCachePrefix();
        $cacheKey = "{$cachePrefix}_{$modelType}_{$this->id}_{$column}_{$locale}";

        if (self::isCacheEnabled()) {
            return Cache::remember($cacheKey, now()->addHours(self::cacheTtl()), function () use ($column, $locale) {
                $translation = $this->getTranslation($column, $locale);
                return $translation?->text ?? $this->getAttribute($column);
            });
        }

        $translation = $this->getTranslation($column, $locale);

        return $translation?->text ?? $this->getAttribute($column);
    }

    public function getTranslation($column, $locale): ?string
    {
        return $this->translations()
            ->where('column', $column)
            ->where('locale', $locale)
            ->first();
    }

    public function transAttr(string $attribute, ?string $locale = null): ?string
    {
        return $this->translate($attribute, $locale);
    }

    public function clearTranslationsCache(): void
    {
        if (self::isCacheEnabled()) {
            self::clearBaseTranslationsCache($this);

            foreach ($this->getTranslatableFields() as $column) {
                self::clearTranslationCache($this, $column);
            }
        }
    }
}
