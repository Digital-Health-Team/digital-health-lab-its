<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OpenSourceProject;
use App\Models\ServiceBooking;
use App\Models\TrainingRegistration;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(): Response
    {
        $userId = auth()->id();

        $orders = ServiceBooking::where('user_id', $userId)
            ->with(['service'])
            ->latest()
            ->get()
            ->map(fn ($b) => [
                'id' => $b->id,
                'serviceName' => $b->service?->name,
                'serviceType' => $b->service?->service_type,
                'status' => $b->current_status,
                'agreedPrice' => $b->agreed_price,
                'createdAt' => $b->created_at->toDateString(),
            ]);

        $projects = OpenSourceProject::where('user_id', $userId)
            ->with(['attachments' => fn ($q) => $q->where('is_primary', true)])
            ->latest()
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->title,
                'caption' => $p->caption,
                'category' => $p->category,
                'listingType' => $p->listing_type,
                'status' => $p->status,
                'license' => $p->license,
                'version' => $p->version,
                'format' => $p->format,
                'description' => $p->description ?? [],
                'highlights' => $p->highlights ?? [],
                'includes' => $p->includes ?? [],
                'coverUrl' => $p->attachments->first()?->file_url
                    ? Storage::disk('public')->url($p->attachments->first()->file_url)
                    : null,
                'createdAt' => $p->created_at->toDateString(),
            ]);

        $enrollments = TrainingRegistration::where('user_id', $userId)
            ->with('training')
            ->latest()
            ->get()
            ->map(fn ($r) => [
                'id' => $r->id,
                'trainingId' => $r->training_id,
                'trainingTitle' => $r->training?->title,
                'trainingSlug' => $r->training?->slug,
                'trainingDate' => $r->training?->date?->toDateString(),
                'trainingLocation' => $r->training?->location,
                'status' => $r->status,
                'paymentStatus' => $r->payment_status,
                'createdAt' => $r->created_at->toDateString(),
            ]);

        return Inertia::render('Features/Portfolio/Pages/PortfolioPage', compact('orders', 'projects', 'enrollments'));
    }
}
