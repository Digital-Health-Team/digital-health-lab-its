<?php

namespace App\Console\Commands;

use App\Actions\Chatbot\BuildKnowledgeIndexAction;
use Illuminate\Console\Command;
use Throwable;

class ChatbotIndexCommand extends Command
{
    protected $signature = 'chatbot:index
                            {--fresh : Drop the selected sources first and rebuild them from scratch}
                            {--only=* : Limit to given sources - md, service, product, event, training, publication, project, page, team}';

    protected $description = 'Build the chatbot knowledge index from knowledge/*.md and the public models';

    public function handle(BuildKnowledgeIndexAction $action): int
    {
        /** @var array<int, string> $only */
        $only = (array) $this->option('only');
        $fresh = (bool) $this->option('fresh');

        if ($fresh) {
            $this->warn('Rebuilding from scratch — every selected document will be re-embedded.');
        }

        try {
            $stats = $action->execute($only, $fresh);
        } catch (Throwable $e) {
            // The message already carries Gemini's full response body when the failure
            // came from the API; printing it whole is the point.
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->table(
            ['Indexed', 'Skipped (unchanged)', 'Pruned', 'Chunks written'],
            [[$stats['indexed'], $stats['skipped'], $stats['pruned'], $stats['chunks']]],
        );

        return self::SUCCESS;
    }
}
