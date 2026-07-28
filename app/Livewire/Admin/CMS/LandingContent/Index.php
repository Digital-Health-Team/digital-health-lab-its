<?php

namespace App\Livewire\Admin\CMS\LandingContent;

use App\Models\PageSection;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    // ── Hero ──────────────────────────────────────────────────────────────────
    public string $heroDescription = '';

    public string $heroCtaText = '';

    public string $heroBgImageUrl = '';

    // ── About ─────────────────────────────────────────────────────────────────
    public string $aboutHeadlineLine1 = '';

    public string $aboutHeadlineLine2 = '';

    public string $aboutHeadlineAccent = '';

    public string $aboutBody1 = '';

    public string $aboutBody2 = '';

    public string $cap1Tag = '';

    public string $cap1Title = '';

    public string $cap1Desc = '';

    public string $cap1Image = '';

    public string $cap1Accent = '';

    public string $cap2Tag = '';

    public string $cap2Title = '';

    public string $cap2Desc = '';

    public string $cap2Image = '';

    public string $cap2Accent = '';

    public string $cap3Tag = '';

    public string $cap3Title = '';

    public string $cap3Desc = '';

    public string $cap3Image = '';

    public string $cap3Accent = '';

    // ── Services ──────────────────────────────────────────────────────────────
    public string $servicesHeading = '';

    public string $servicesSubheading = '';

    public string $servicesBody = '';

    public string $svc1Title = '';

    public string $svc1Body = '';

    public string $svc1Image = '';

    public string $svc1Gradient = '';

    public string $svc2Title = '';

    public string $svc2Body = '';

    public string $svc2Image = '';

    public string $svc2Gradient = '';

    public string $svc3Title = '';

    public string $svc3Body = '';

    public string $svc3Image = '';

    public string $svc3Gradient = '';

    // ── Collaboration ─────────────────────────────────────────────────────────
    public string $collabHeading = '';

    public string $collabSubheading = '';

    public string $collabBody = '';

    public string $chap1Name = '';

    public string $chap1NameLine1 = '';

    public string $chap1NameLine2 = '';

    public string $chap1Type = '';

    public string $chap1Period = '';

    public string $chap1Desc = '';

    public string $chap2Name = '';

    public string $chap2NameLine1 = '';

    public string $chap2NameLine2 = '';

    public string $chap2Type = '';

    public string $chap2Period = '';

    public string $chap2Desc = '';

    public string $chap3Name = '';

    public string $chap3NameLine1 = '';

    public string $chap3NameLine2 = '';

    public string $chap3Type = '';

    public string $chap3Period = '';

    public string $chap3Desc = '';

    public string $chap4Name = '';

    public string $chap4NameLine1 = '';

    public string $chap4NameLine2 = '';

    public string $chap4Type = '';

    public string $chap4Period = '';

    public string $chap4Desc = '';

    // ── Wisdom ────────────────────────────────────────────────────────────────
    public string $wisdomHeadingLine1 = '';

    public string $wisdomHeadingLine2 = '';

    public string $wisdomQuote = '';

    public string $wisdomAttrName = '';

    public string $wisdomAttrRole = '';

    public string $wisdomAttrInitials = '';

    // ── Articles ──────────────────────────────────────────────────────────────
    public string $articlesHeading = '';

    public string $articlesSubheading = '';

    public string $articlesBody = '';

    // ── CTA ───────────────────────────────────────────────────────────────────
    public string $ctaHeading = '';

    public string $ctaSubheading = '';

    public string $ctaBody = '';

    public string $ctaPrimaryLabel = '';

    public string $ctaSecondaryLabel = '';

    // ── Contact ───────────────────────────────────────────────────────────────
    public string $contactCopy = '';

    public string $contactEmail = '';

    public string $contactWhatsapp = '';

    public string $contactInstagram = '';

    // ── Footer ────────────────────────────────────────────────────────────────
    public string $footerTagline = '';

    public string $footerAddress = '';

    public string $footerPhone = '';

    public string $footerEmail = '';

    public string $footerYoutube = '';

    public string $footerInstagram = '';

    public string $footerFacebook = '';

    public string $footerLinkedin = '';

    // ── Gradient presets for service cards ────────────────────────────────────
    public array $gradientOptions = [
        ['id' => 'bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900', 'name' => 'Biru Navy'],
        ['id' => 'bg-gradient-to-br from-teal-600 via-teal-800 to-slate-900', 'name' => 'Hijau Teal'],
        ['id' => 'bg-gradient-to-br from-rose-700 via-rose-900 to-fuchsia-950', 'name' => 'Merah Marun'],
        ['id' => 'bg-gradient-to-br from-indigo-600 via-indigo-800 to-slate-900', 'name' => 'Ungu Indigo'],
        ['id' => 'bg-gradient-to-br from-amber-600 via-orange-700 to-red-900', 'name' => 'Oranye Emas'],
    ];

    /**
     * Which language this form is editing. Indonesian lives in the base rows;
     * English lives in `<key>_en` rows — no migration, since page_sections is
     * unique on (page_name, section_key).
     */
    public string $editLocale = 'id';

    /** Media and contact rows are locale-independent and stay on the base key. */
    private const SHARED_KEYS = [
        'hero_bg_image_url',
        'contact_email', 'contact_whatsapp', 'contact_instagram',
        'footer_phone', 'footer_email',
        'footer_youtube_url', 'footer_instagram_url', 'footer_facebook_url', 'footer_linkedin_url',
        'wisdom_attribution_initials',
    ];

    private function key(string $key): string
    {
        if ($this->editLocale === 'id' || in_array($key, self::SHARED_KEYS, true)) {
            return $key;
        }

        return $key.'_en';
    }

    public function updatedEditLocale(): void
    {
        $this->mount();
    }

    public function mount(): void
    {
        $sections = PageSection::where('page_name', 'landing')
            ->get()
            ->keyBy('section_key');

        $get = fn (string $key) => $sections->get($this->key($key))?->content ?? '';

        // JSON blobs mix copy with media (image_url, gradient, accent). Media is
        // locale-independent, so the localised blob is layered over the base one —
        // otherwise editing in English would save an empty image and blank the card.
        $getJson = function (string $key) use ($sections): string {
            $base = json_decode($sections->get($key)?->content ?? '', true) ?? [];
            $localised = json_decode($sections->get($this->key($key))?->content ?? '', true) ?? [];

            return json_encode(array_merge(
                $base,
                array_filter($localised, fn ($v) => $v !== '' && $v !== null)
            ));
        };

        $this->heroDescription = $get('hero_description');
        $this->heroCtaText = $get('hero_cta_text');
        $this->heroBgImageUrl = $get('hero_bg_image_url');

        [$this->aboutHeadlineLine1, $this->aboutHeadlineLine2] = array_pad(
            explode(' / ', $get('about_headline'), 2), 2, ''
        );
        $this->aboutHeadlineAccent = $get('about_headline_accent');
        $this->aboutBody1 = $get('about_body_1');
        $this->aboutBody2 = $get('about_body_2');

        $this->loadCapability(1, $getJson('about_capability_1'));
        $this->loadCapability(2, $getJson('about_capability_2'));
        $this->loadCapability(3, $getJson('about_capability_3'));

        $this->servicesHeading = $get('services_heading');
        $this->servicesSubheading = $get('services_subheading');
        $this->servicesBody = $get('services_body');

        $this->loadService(1, $getJson('services_card_1'));
        $this->loadService(2, $getJson('services_card_2'));
        $this->loadService(3, $getJson('services_card_3'));

        $this->collabHeading = $get('collaboration_heading');
        $this->collabSubheading = $get('collaboration_subheading');
        $this->collabBody = $get('collaboration_body');

        $this->loadChapter(1, $getJson('collaboration_chapter_1'));
        $this->loadChapter(2, $getJson('collaboration_chapter_2'));
        $this->loadChapter(3, $getJson('collaboration_chapter_3'));
        $this->loadChapter(4, $getJson('collaboration_chapter_4'));

        [$this->wisdomHeadingLine1, $this->wisdomHeadingLine2] = array_pad(
            explode(' / ', $get('wisdom_heading'), 2), 2, ''
        );
        $this->wisdomQuote = $get('wisdom_quote');
        $this->wisdomAttrName = $get('wisdom_attribution_name');
        $this->wisdomAttrRole = $get('wisdom_attribution_role');
        $this->wisdomAttrInitials = $get('wisdom_attribution_initials');

        $this->articlesHeading = $get('articles_heading');
        $this->articlesSubheading = $get('articles_subheading');
        $this->articlesBody = $get('articles_body');

        $this->ctaHeading = $get('cta_heading');
        $this->ctaSubheading = $get('cta_subheading');
        $this->ctaBody = $get('cta_body');
        $this->ctaPrimaryLabel = $get('cta_primary_label');
        $this->ctaSecondaryLabel = $get('cta_secondary_label');

        $this->contactCopy = $get('contact_copy');
        $this->contactEmail = $get('contact_email');
        $this->contactWhatsapp = $get('contact_whatsapp');
        $this->contactInstagram = $get('contact_instagram');

        $this->footerTagline = $get('footer_tagline');
        $this->footerAddress = $get('footer_address');
        $this->footerPhone = $get('footer_phone');
        $this->footerEmail = $get('footer_email');
        $this->footerYoutube = $get('footer_youtube_url');
        $this->footerInstagram = $get('footer_instagram_url');
        $this->footerFacebook = $get('footer_facebook_url');
        $this->footerLinkedin = $get('footer_linkedin_url');
    }

    private function loadCapability(int $n, string $json): void
    {
        $data = json_decode($json, true) ?? [];
        $p = "cap{$n}";
        $this->{$p.'Tag'} = $data['tag'] ?? '';
        $this->{$p.'Title'} = $data['title'] ?? '';
        $this->{$p.'Desc'} = $data['description'] ?? '';
        $this->{$p.'Image'} = $data['image_url'] ?? '';
        $this->{$p.'Accent'} = $data['accent'] ?? '#000000';
    }

    private function loadService(int $n, string $json): void
    {
        $data = json_decode($json, true) ?? [];
        $p = "svc{$n}";
        $this->{$p.'Title'} = $data['title'] ?? '';
        $this->{$p.'Body'} = $data['body'] ?? '';
        $this->{$p.'Image'} = $data['image_url'] ?? '';
        $this->{$p.'Gradient'} = $data['gradient'] ?? '';
    }

    private function loadChapter(int $n, string $json): void
    {
        $data = json_decode($json, true) ?? [];
        $p = "chap{$n}";
        $this->{$p.'Name'} = $data['name'] ?? '';
        $this->{$p.'NameLine1'} = $data['name_line_1'] ?? '';
        $this->{$p.'NameLine2'} = $data['name_line_2'] ?? '';
        $this->{$p.'Type'} = $data['type'] ?? '';
        $this->{$p.'Period'} = $data['period'] ?? '';
        $this->{$p.'Desc'} = $data['description'] ?? '';
    }

    private function upsert(string $key, string $value): void
    {
        PageSection::updateOrCreate(
            ['page_name' => 'landing', 'section_key' => $this->key($key)],
            ['content' => $value, 'updated_by' => auth()->id()]
        );
    }

    public function saveHero(): void
    {
        $this->upsert('hero_description', $this->heroDescription);
        $this->upsert('hero_cta_text', $this->heroCtaText);
        $this->upsert('hero_bg_image_url', $this->heroBgImageUrl);
        $this->success('Hero section saved.');
    }

    public function saveAbout(): void
    {
        $this->upsert('about_headline', trim($this->aboutHeadlineLine1).' / '.trim($this->aboutHeadlineLine2));
        $this->upsert('about_headline_accent', $this->aboutHeadlineAccent);
        $this->upsert('about_body_1', $this->aboutBody1);
        $this->upsert('about_body_2', $this->aboutBody2);

        foreach ([1, 2, 3] as $n) {
            $p = "cap{$n}";
            $this->upsert("about_capability_{$n}", json_encode([
                'tag' => $this->{$p.'Tag'},
                'title' => $this->{$p.'Title'},
                'description' => $this->{$p.'Desc'},
                'image_url' => $this->{$p.'Image'},
                'accent' => $this->{$p.'Accent'},
            ]));
        }

        $this->success('About section saved.');
    }

    public function saveServices(): void
    {
        $this->upsert('services_heading', $this->servicesHeading);
        $this->upsert('services_subheading', $this->servicesSubheading);
        $this->upsert('services_body', $this->servicesBody);

        foreach ([1, 2, 3] as $n) {
            $p = "svc{$n}";
            $this->upsert("services_card_{$n}", json_encode([
                'title' => $this->{$p.'Title'},
                'body' => $this->{$p.'Body'},
                'image_url' => $this->{$p.'Image'},
                'gradient' => $this->{$p.'Gradient'},
            ]));
        }

        $this->success('Services section saved.');
    }

    public function saveCollaboration(): void
    {
        $this->upsert('collaboration_heading', $this->collabHeading);
        $this->upsert('collaboration_subheading', $this->collabSubheading);
        $this->upsert('collaboration_body', $this->collabBody);

        foreach ([1, 2, 3, 4] as $n) {
            $p = "chap{$n}";
            $key = "collaboration_chapter_{$n}";

            // Preserve the existing photo list — the curated form edits text only.
            $existing = json_decode(
                PageSection::where('page_name', 'landing')->where('section_key', $this->key($key))->value('content') ?? '',
                true
            ) ?? [];

            $this->upsert($key, json_encode([
                'name' => $this->{$p.'Name'},
                'name_line_1' => $this->{$p.'NameLine1'},
                'name_line_2' => $this->{$p.'NameLine2'},
                'type' => $this->{$p.'Type'},
                'period' => $this->{$p.'Period'},
                'description' => $this->{$p.'Desc'},
                'images' => $existing['images'] ?? [],
            ]));
        }

        $this->success('Collaboration section saved.');
    }

    public function saveWisdom(): void
    {
        $this->upsert('wisdom_heading', trim($this->wisdomHeadingLine1).' / '.trim($this->wisdomHeadingLine2));
        $this->upsert('wisdom_heading_accent', trim($this->wisdomHeadingLine2));
        $this->upsert('wisdom_quote', $this->wisdomQuote);
        $this->upsert('wisdom_attribution_name', $this->wisdomAttrName);
        $this->upsert('wisdom_attribution_role', $this->wisdomAttrRole);
        $this->upsert('wisdom_attribution_initials', $this->wisdomAttrInitials);
        $this->success('Wisdom section saved.');
    }

    public function saveArticles(): void
    {
        $this->upsert('articles_heading', $this->articlesHeading);
        $this->upsert('articles_subheading', $this->articlesSubheading);
        $this->upsert('articles_body', $this->articlesBody);
        $this->success('Articles section saved.');
    }

    public function saveCta(): void
    {
        $this->upsert('cta_heading', $this->ctaHeading);
        $this->upsert('cta_subheading', $this->ctaSubheading);
        $this->upsert('cta_body', $this->ctaBody);
        $this->upsert('cta_primary_label', $this->ctaPrimaryLabel);
        $this->upsert('cta_secondary_label', $this->ctaSecondaryLabel);
        $this->success('CTA section saved.');
    }

    public function saveContact(): void
    {
        $this->upsert('contact_copy', $this->contactCopy);
        $this->upsert('contact_email', $this->contactEmail);
        $this->upsert('contact_whatsapp', $this->contactWhatsapp);
        $this->upsert('contact_instagram', $this->contactInstagram);
        $this->success('Contact section saved.');
    }

    public function saveFooter(): void
    {
        $this->upsert('footer_tagline', $this->footerTagline);
        $this->upsert('footer_address', $this->footerAddress);
        $this->upsert('footer_phone', $this->footerPhone);
        $this->upsert('footer_email', $this->footerEmail);
        $this->upsert('footer_youtube_url', $this->footerYoutube);
        $this->upsert('footer_instagram_url', $this->footerInstagram);
        $this->upsert('footer_facebook_url', $this->footerFacebook);
        $this->upsert('footer_linkedin_url', $this->footerLinkedin);
        $this->success('Footer section saved.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.cms.landing-content.index')
            ->layout('layouts.app', ['title' => 'Landing Page CMS']);
    }
}
