<?php

namespace App\Traits;

use InvalidArgumentException;

/**
 * Reads a text attribute in the active locale, backed by an `<attr>_en` sibling column.
 *
 * Indonesian lives in the base column and English in `<attr>_en`, matching the
 * convention already used by `lab_team_sections.label_id`/`label_en`,
 * `lab_team_people.role_id`/`role_en`, and the `page_sections` `<key>_en` rows.
 *
 * An absent or empty `_en` falls back to Indonesian rather than rendering blank, so a
 * half-translated row degrades to the original copy instead of leaving a hole in the page.
 *
 * Deliberately an EXPLICIT accessor rather than a `getAttribute()` override. The admin
 * CRUD components load their forms with `$this->name = $product->name` and write that
 * value straight back on save — so if `$model->name` transparently returned `name_en`
 * for an English-locale admin, saving would overwrite the Indonesian copy with the
 * English one. Keeping `$model->name` as the true stored column makes that impossible;
 * read paths opt in by calling `localized('name')`.
 */
trait HasEnglishOverlay
{
    /**
     * Attributes that have an `<attr>_en` sibling column.
     *
     * Declared per model as `protected array $localizable = ['name', 'description'];`
     */
    public function localized(string $key): mixed
    {
        $localizable = $this->localizable ?? [];

        if (! in_array($key, $localizable, true)) {
            throw new InvalidArgumentException(sprintf(
                '[%s] has no localizable attribute "%s". Declared: %s',
                static::class,
                $key,
                $localizable ? implode(', ', $localizable) : '(none)',
            ));
        }

        if (app()->getLocale() === 'en') {
            return $this->getAttribute($key.'_en') ?: $this->getAttribute($key);
        }

        return $this->getAttribute($key);
    }

    /** @return array<string, mixed> every localizable attribute, resolved for the active locale */
    public function localizedAttributes(): array
    {
        return collect($this->localizable ?? [])
            ->mapWithKeys(fn (string $key) => [$key => $this->localized($key)])
            ->all();
    }
}
