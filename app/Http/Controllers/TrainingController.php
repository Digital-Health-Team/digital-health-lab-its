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
            ->map(fn ($t) => $t->toCardArray());

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

        return back()->with('success', __('Payment proof uploaded. Awaiting admin verification.'));
    }

    /** @return array<string, mixed> */
    private function toDetailShape(Training $training): array
    {
        $card = $training->toCardArray();

        return [
            ...$card,
            'subtitle' => $training->localized('subtitle'),
            'previewImageUrl' => $training->thumbnail_url,
            'description' => $training->localized('description'),
            'whatYouWillLearn' => $training->localized('what_you_will_learn') ?? [],
            'includes' => $training->localized('includes') ?? [],
            'curriculum' => $training->localized('curriculum') ?? [],
            'isFull' => $training->isFull(),
            'maxParticipants' => $training->max_participants,
            'views' => $training->views,
            // The card's "students" is a view count; on the detail page the same
            // slot reads registrants, which the instructor block already formats.
            'students' => $card['instructor']['students'],
            'instructor' => [
                ...$card['instructor'],
                'bio' => $training->localized('instructor_bio'),
            ],
        ];
    }
}
