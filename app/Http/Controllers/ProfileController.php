<?php

namespace App\Http\Controllers;

use App\Actions\User\UpdateUserAction;
use App\DTOs\User\UserData;
use App\Enums\BookingStatus;
use App\Models\OpenSourceProject;
use App\Models\ServiceBooking;
use App\Models\TrainingRegistration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show(): Response
    {
        /** @var User $user */
        $user = auth()->user()->load('profile', 'role');
        $userId = $user->id;

        $orders = ServiceBooking::where('user_id', $userId)
            // Consultation threads are not orders — they live at /services/consultation.
            ->where('current_status', '!=', BookingStatus::Consultation->value)
            ->with(['service', 'transaction', 'progressUpdates'])
            ->latest()
            ->get()
            ->map(fn ($b) => [
                'id' => $b->id,
                'invoice' => 'INV-'.str_pad((string) $b->id, 4, '0', STR_PAD_LEFT),
                'serviceName' => $b->service?->localized('name'),
                'serviceType' => $b->service?->service_type,
                'status' => $b->current_status,
                'priceLabel' => $b->agreed_price
                    ? 'Rp '.number_format($b->agreed_price, 0, ',', '.')
                    : null,
                'agreedPrice' => $b->agreed_price,
                'paymentStatus' => $b->transaction?->payment_status,
                'progressPercentage' => $b->progressUpdates->sortByDesc('created_at')->first()?->percentage ?? 0,
                'createdAt' => $b->created_at->toDateString(),
            ]);

        $projects = OpenSourceProject::where('user_id', $userId)
            ->with(['attachments' => fn ($q) => $q->where('is_primary', true)])
            ->latest()
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->localized('title'),
                'caption' => $p->localized('caption'),
                'category' => $p->category,
                'listingType' => $p->listing_type,
                'status' => $p->status,
                'license' => $p->license,
                'version' => $p->version,
                'format' => $p->format,
                'description' => $p->localized('description') ?? [],
                'highlights' => $p->localized('highlights') ?? [],
                'includes' => $p->localized('includes') ?? [],
                'coverUrl' => $p->attachments->first()?->public_url,
                'createdAt' => $p->created_at->toDateString(),
            ]);

        $enrollments = TrainingRegistration::where('user_id', $userId)
            ->with('training')
            ->latest()
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'trainingId' => $r->training_id,
                'trainingTitle' => $r->training?->localized('title'),
                'trainingSlug' => $r->training?->slug,
                'trainingDate' => $r->training?->date?->toDateString(),
                'trainingLocation' => $r->training?->localized('location'),
                'status' => $r->status,
                'paymentStatus' => $r->payment_status,
                'createdAt' => $r->created_at->toDateString(),
            ]);

        return inertia('Features/Dashboard/Pages/ProfilePage', [
            'profile' => $this->profileData($user),
            'orders' => $orders,
            'projects' => $projects,
            'enrollments' => $enrollments,
        ]);
    }

    public function edit(): Response
    {
        /** @var User $user */
        $user = auth()->user()->load('profile', 'role');

        return inertia('Features/Dashboard/Pages/ProfileEditPage', [
            'profile' => $this->profileData($user),
        ]);
    }

    private function profileData(User $user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role?->name,
            'avatar' => $user->profile_photo
                ? Storage::disk('public')->url($user->profile_photo)
                : null,
            'full_name' => $user->profile?->full_name,
            'nim' => $user->profile?->nim,
            'nik' => $user->profile?->nik,
            'university' => $user->profile?->university,
            'faculty' => $user->profile?->faculty,
            'department' => $user->profile?->department,
            'phone' => $user->profile?->phone,
            'address' => $user->profile?->address,
            'member_since' => $user->created_at?->translatedFormat('F Y'),
            'verified' => $user->hasVerifiedEmail(),
        ];
    }

    public function update(Request $request, UpdateUserAction $action): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $isMhs = $user->role?->name === 'mahasiswa';

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => 'nullable|image|max:2048',
            'nik' => 'required|string|max:20',
            'nim' => $isMhs ? 'required|string|max:50' : 'nullable|string|max:50',
            'university' => $isMhs ? 'required|string|max:255' : 'nullable|string|max:255',
            'faculty' => $isMhs ? 'required|string|max:255' : 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $data = new UserData(
            full_name: $validated['name'],
            email: $validated['email'],
            role_id: $user->role_id,
            profile_photo: $request->hasFile('profile_photo')
                ? $request->file('profile_photo')
                : null,
            nik: $validated['nik'] ?? null,
            nim: $validated['nim'] ?? null,
            university: $validated['university'] ?? null,
            faculty: $validated['faculty'] ?? null,
            department: $validated['department'] ?? null,
            phone: $validated['phone'] ?? null,
            address: $validated['address'] ?? null,
        );

        // Sync users.name alongside profile.full_name
        $user->update(['name' => $validated['name']]);

        $action->execute($user, $data);

        return to_route('profile.show')->with('success', __('Profile updated successfully.'));
    }
}
