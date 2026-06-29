<?php

namespace App\Http\Controllers;

use App\Actions\Training\RegisterForTrainingAction;
use App\Actions\Training\UploadTrainingPaymentProofAction;
use App\DTOs\Training\TrainingRegistrationData;
use App\Models\Training;
use App\Models\TrainingRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrainingController extends Controller
{
    public function index(): Response
    {
        $trainings = Training::where('is_active', true)
            ->withCount('registrations')
            ->orderBy('date')
            ->get();

        $staffPick = $trainings->firstWhere('is_featured', true);
        $grid = $trainings->filter(fn ($t) => ! $t->is_featured)->values();

        return Inertia::render('Features/Training/Pages/TrainingPage', [
            'trainings' => $grid->map(fn ($t) => $this->toCourseShape($t)),
            'staffPick' => $staffPick ? $this->toStaffPickShape($staffPick) : null,
        ]);
    }

    public function show(Training $training): Response
    {
        $training->loadCount('registrations');

        $isRegistered = false;
        $userRegistration = null;

        if (auth()->check()) {
            $reg = $training->registrations()
                ->where('user_id', auth()->id())
                ->first();

            if ($reg) {
                $isRegistered = true;
                $userRegistration = [
                    'id' => $reg->id,
                    'status' => $reg->status,
                    'paymentStatus' => $reg->payment_status,
                ];
            }
        }

        $related = Training::where('is_active', true)
            ->where('id', '!=', $training->id)
            ->withCount('registrations')
            ->orderBy('date')
            ->limit(3)
            ->get()
            ->map(fn ($t) => $this->toCourseShape($t));

        return Inertia::render('Features/Training/Pages/TrainingDetailPage', [
            'training' => $this->toDetailShape($training),
            'isRegistered' => $isRegistered,
            'userRegistration' => $userRegistration,
            'isAuthenticated' => auth()->check(),
            'related' => $related,
            'paymentInfo' => $training->is_paid ? [
                'qrisImageUrl' => config('payment.qris_image_url'),
                'bankName' => config('payment.bank_name'),
                'bankAccountName' => config('payment.bank_account_name'),
                'bankAccountNumber' => config('payment.bank_account_number'),
            ] : null,
        ]);
    }

    public function register(Request $request, Training $training): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'preferred_session' => 'nullable|string|max:255',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        try {
            app(RegisterForTrainingAction::class)->execute(
                $training,
                new TrainingRegistrationData(
                    training_id: $training->id,
                    user_id: auth()->id(),
                    full_name: $validated['full_name'],
                    email: $validated['email'],
                    phone_number: $validated['phone_number'],
                    preferred_session: $validated['preferred_session'] ?? null,
                    additional_notes: $validated['additional_notes'] ?? null,
                )
            );
        } catch (\Exception $e) {
            return back()->withErrors(['registration' => $e->getMessage()]);
        }

        return back()->with('success', __('You have successfully registered for this training!'));
    }

    public function uploadPaymentProof(Request $request, Training $training): RedirectResponse
    {
        $reg = TrainingRegistration::where('training_id', $training->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        app(UploadTrainingPaymentProofAction::class)->execute($reg, $request->file('payment_proof'));

        return back()->with('success', __('Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.'));
    }

    private function toCourseShape(Training $training): array
    {
        return [
            'id' => $training->id,
            'slug' => $training->slug,
            'href' => route('training.show', $training->slug),
            'title' => $training->title,
            'thumbnailUrl' => $training->thumbnail_url,
            'price' => $training->price,
            'isPaid' => $training->is_paid,
            'level' => $training->level,
            'duration' => $training->duration,
            'language' => $training->language,
            'date' => $training->date?->toIso8601String(),
            'location' => $training->location,
            'instructorName' => $training->instructor_name,
            'instructorAvatarUrl' => $training->instructor_avatar_url,
            'participantsCount' => $training->registrations_count,
            'rating' => (float) $training->rating,
            'ratingCount' => $training->rating_count,
            'category' => $training->category,
            'extraTags' => $training->extra_tags ?: null,
            'students' => $this->formatCount($training->views),
            'staffPick' => $training->is_featured,
            'instructor' => [
                'name' => $training->instructor_name,
                'title' => $training->instructor_title,
                'avatarUrl' => $training->instructor_avatar_url,
                'verified' => true,
                'students' => $this->formatCount($training->registrations_count),
            ],
        ];
    }

    private function formatCount(int $n): string
    {
        if ($n >= 1000) {
            $k = $n / 1000;

            return rtrim(rtrim(number_format($k, 1), '0'), '.').'k';
        }

        return (string) $n;
    }

    private function toStaffPickShape(Training $training): array
    {
        return [
            ...$this->toCourseShape($training),
            'subtitle' => $training->subtitle,
            'description' => $training->description,
        ];
    }

    private function toDetailShape(Training $training): array
    {
        return [
            'id' => $training->id,
            'slug' => $training->slug,
            'title' => $training->title,
            'subtitle' => $training->subtitle,
            'previewImageUrl' => $training->thumbnail_url,
            'thumbnailUrl' => $training->thumbnail_url,
            'price' => $training->price,
            'isPaid' => $training->is_paid,
            'level' => $training->level,
            'duration' => $training->duration,
            'language' => $training->language,
            'date' => $training->date?->toIso8601String(),
            'location' => $training->location,
            'description' => $training->description,
            'whatYouWillLearn' => $training->what_you_will_learn ?? [],
            'includes' => $training->includes ?? [],
            'curriculum' => $training->curriculum ?? [],
            'participantsCount' => $training->registrations_count,
            'isFull' => $training->isFull(),
            'maxParticipants' => $training->max_participants,
            'rating' => (float) $training->rating,
            'ratingCount' => $training->rating_count,
            'category' => $training->category,
            'extraTags' => $training->extra_tags ?: null,
            'views' => $training->views,
            'students' => $this->formatCount($training->registrations_count),
            'instructor' => [
                'name' => $training->instructor_name,
                'title' => $training->instructor_title,
                'bio' => $training->instructor_bio,
                'avatarUrl' => $training->instructor_avatar_url,
                'verified' => true,
                'students' => $this->formatCount($training->registrations_count),
            ],
        ];
    }
}
