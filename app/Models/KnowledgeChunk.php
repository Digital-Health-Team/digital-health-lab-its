<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One retrievable passage of public knowledge, with its L2-normalised embedding.
 */
class KnowledgeChunk extends Model
{
    /**
     * Cache key prefix for the unpacked chunk lists.
     *
     * Declared here rather than on either action because both sides need it and neither
     * owns the other: RetrieveKnowledgeAction fills the cache, BuildKnowledgeIndexAction
     * clears it after every run. A duplicated literal is how a stale index survives a
     * reindex and nobody notices for a week.
     */
    public const CACHE_KEY = 'chatbot.knowledge_chunks';

    /**
     * Indexed locales. Shared so the ingest loop and the cache flush cannot drift apart —
     * a locale added to one but not the other leaves cache entries nothing ever clears.
     */
    public const LOCALES = ['id', 'en'];

    protected $fillable = [
        'source_key',
        'source_hash',
        'locale',
        'audience',
        'category',
        'title',
        'heading',
        'content',
        'url',
        'embedding',
        'dimensions',
    ];

    /**
     * Unpack the stored BLOB back into floats.
     *
     * @return array<int, float>
     */
    public function vector(): array
    {
        return array_values(unpack('g*', $this->embedding) ?: []);
    }
}
