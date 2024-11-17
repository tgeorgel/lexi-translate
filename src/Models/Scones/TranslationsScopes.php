<?php

namespace Omaralalwi\LexiTranslate\Models\Scones;

trait TranslationsScopes
{
    /**
     * Search the model by translated fields.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $field The translatable field to search.
     * @param string $keyword The keyword to search for.
     * @param string|null $locale The locale to search in. Defaults to app locale.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByTranslation($query, string $field, string $keyword, ?string $locale = null)
    {
        $locale = $locale && $this->isSupportedLocal($locale) ? $locale : app()->getLocale();

        return $query->whereHas('translations', function ($q) use ($field, $keyword, $locale) {
            $q->where('column', $field)
                ->where('locale', $locale)
                ->where('text', 'LIKE', "%{$keyword}%");
        });
    }

    /**
     * Filter the model by translated field values.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $field The translatable field to filter.
     * @param mixed $value The exact value to filter for.
     * @param string|null $locale The locale to filter in. Defaults to app locale.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByTranslation($query, string $field, $value, ?string $locale = null)
    {
        $locale = $locale && $this->isSupportedLocal($locale) ? $locale : app()->getLocale();

        return $query->whereHas('translations', function ($q) use ($field, $value, $locale) {
            $q->where('column', $field)
                ->where('locale', $locale)
                ->where('text', $value);
        });
    }

}
