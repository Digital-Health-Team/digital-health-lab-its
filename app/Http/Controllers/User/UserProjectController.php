<?php

namespace App\Http\Controllers\User;

use App\Actions\Project\CreateOpenSourceProjectAction;
use App\Actions\Project\DeleteOpenSourceProjectAction;
use App\Actions\Project\UpdateOpenSourceProjectAction;
use App\DTOs\Project\OpenSourceProjectData;
use App\Http\Controllers\Controller;
use App\Models\OpenSourceProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserProjectController extends Controller
{
    private array $rules = [
        'title' => 'required|string|max:255',
        'category' => 'required|string|in:3d_model,iot_system,medical_device,software',
        'listing_type' => 'nullable|string|in:journals,products,powerpoint,downloadable,read_only',
        'caption' => 'nullable|string|max:500',
        'description' => 'nullable|array',
        'description.*' => 'nullable|string',
        'highlights' => 'nullable|array',
        'highlights.*' => 'nullable|string',
        'includes' => 'nullable|array',
        'includes.*' => 'nullable|string',
        'license' => 'nullable|string|in:MIT,Apache 2.0,CC BY 4.0,GPL-3.0',
        'version' => 'nullable|string|max:255',
        'format' => 'nullable|string|max:255',
        'files' => 'nullable|array',
        'files.*' => 'file|max:20480',
    ];

    public function store(Request $request, CreateOpenSourceProjectAction $action): RedirectResponse
    {
        $validated = $request->validate($this->rules);

        $action->execute(new OpenSourceProjectData(
            user_id: auth()->id(),
            title: $validated['title'],
            category: $validated['category'],
            new_files: $request->file('files', []),
            status: 'pending',
            caption: $validated['caption'] ?? null,
            listing_type: $validated['listing_type'] ?? null,
            description: array_filter($validated['description'] ?? []),
            highlights: array_filter($validated['highlights'] ?? []),
            includes: array_filter($validated['includes'] ?? []),
            license: $validated['license'] ?? 'MIT',
            version: $validated['version'] ?? null,
            format: $validated['format'] ?? null,
        ));

        return redirect()->route('profile.show')
            ->with('success', __('Project submitted for review.'));
    }

    public function update(Request $request, OpenSourceProject $project, UpdateOpenSourceProjectAction $action): RedirectResponse
    {
        abort_if($project->user_id !== auth()->id(), 403);
        abort_if($project->status === 'approved', 403, 'Cannot edit an approved project.');

        $validated = $request->validate($this->rules);

        $wasRejected = $project->status === 'rejected';

        $action->execute($project, new OpenSourceProjectData(
            user_id: auth()->id(),
            title: $validated['title'],
            category: $validated['category'],
            new_files: $request->file('files', []),
            status: $project->status,
            caption: $validated['caption'] ?? null,
            listing_type: $validated['listing_type'] ?? null,
            description: array_filter($validated['description'] ?? []),
            highlights: array_filter($validated['highlights'] ?? []),
            includes: array_filter($validated['includes'] ?? []),
            license: $validated['license'] ?? 'MIT',
            version: $validated['version'] ?? null,
            format: $validated['format'] ?? null,
        ));

        if ($wasRejected) {
            $project->update(['status' => 'pending', 'validated_by' => null]);
        }

        return redirect()->route('profile.show')
            ->with('success', __('Project updated.'));
    }

    public function destroy(OpenSourceProject $project, DeleteOpenSourceProjectAction $action): RedirectResponse
    {
        abort_if($project->user_id !== auth()->id(), 403);
        abort_if($project->status === 'approved', 403, 'Cannot delete an approved project.');

        $action->execute($project);

        return redirect()->route('profile.show')
            ->with('success', __('Project deleted.'));
    }
}
