<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<\App\Models\IssueReport>
 */
class IssueReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reporter_id' => User::factory(),
            'reportable_type' => null,
            'reportable_id' => null,
            'type' => fake()->randomElement(ReportType::cases())->value,
            'description' => fake()->paragraph(),
            'status' => ReportStatus::Open->value,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn () => [
            'status' => ReportStatus::Resolved->value,
            'resolved_by' => User::factory(),
            'resolved_at' => now(),
            'resolution_note' => fake()->sentence(),
        ]);
    }

    public function forReportable(Model $model): static
    {
        return $this->state(fn () => [
            'reportable_type' => $model::class,
            'reportable_id' => $model->getKey(),
        ]);
    }
}
