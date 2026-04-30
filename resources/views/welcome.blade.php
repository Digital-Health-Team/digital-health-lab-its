<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gretiva - Hybrid Action-Oriented Architecture</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace']
                    },
                    colors: {
                        gretiva: {
                            purple: '#5b2c9d',
                            'purple-dark': '#2d1b4e',
                            orange: '#e65c00',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Syntax Highlighting Custom Colors */
        .keyword { color: #f472b6; } /* pink-400 */
        .class-name { color: #34d399; font-weight: bold; } /* emerald-400 */
        .variable { color: #60a5fa; } /* blue-400 */
        .function { color: #93c5fd; } /* blue-300 */
        .string { color: #fde047; } /* yellow-300 */
        .comment { color: #64748b; font-style: italic; } /* slate-500 */
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 selection:bg-gretiva-purple selection:text-white">

    <header class="relative bg-slate-900 text-white overflow-hidden pb-20 lg:pb-32 pt-10">
        <div class="absolute inset-0 bg-gretiva-purple-dark opacity-90"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-gretiva-purple/80 via-transparent to-gretiva-orange/40 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>

        <nav class="relative z-10 container mx-auto px-6 lg:px-12 flex justify-between items-center h-16">
            <div class="flex items-center gap-3 font-bold text-xl tracking-tight">
                <img src="{{ asset('assets/images/logo_idig_htech.png') }}" alt="IDIG Logo" class="w-10 h-10 object-contain drop-shadow-lg" onerror="this.style.display='none';">
                IDIG
            </div>
            <div class="flex gap-4 items-center">
                <a href="https://github.com/ekanata14" target="_blank" class="text-slate-300 hover:text-white transition">GitHub</a>
                <a href="{{ route('login') }}" class="px-5 py-2 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md transition text-sm font-semibold">Sign In</a>
            </div>
        </nav>

        <div class="relative z-10 container mx-auto px-6 lg:px-12 mt-20 text-center max-w-4xl opacity-0 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold tracking-wide text-orange-300 mb-6">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                </span>
                Laravel 12 & Livewire 3
            </div>
            <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight mb-6 leading-tight">
                Scale Faster with <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-purple-400">Hybrid Action-Oriented</span>
            </h1>
            <p class="text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                An architectural pattern designed by Gretiva to eliminate "Fat Controllers" and "Spaghetti Livewire", ensuring your codebase remains pristine, testable, and lightning-fast to develop.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#playground" class="px-8 py-4 rounded-xl bg-gradient-to-r from-gretiva-purple to-gretiva-orange font-bold shadow-lg shadow-purple-500/30 hover:scale-105 transition-transform text-white">
                    Explore Code Workflow
                </a>

                <a href="https://github.com/dreamy-company/laravel-12-hybrid-action-oriented-template" target="_blank" class="px-8 py-4 rounded-xl bg-slate-800 border border-slate-700 font-bold hover:bg-slate-700 transition text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                    GitHub Repository
                </a>


            </div>
        </div>
    </header>

    <section id="architecture" class="py-24 bg-white relative">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-16 opacity-0 animate-fade-in-up delay-100">
                <h2 class="text-sm font-bold text-gretiva-purple tracking-widest uppercase mb-2">The Philosophy</h2>
                <h3 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-4">Separation of Concerns, Perfected.</h3>
                <p class="text-slate-600 text-lg">
                    Traditional MVC often leads to bloated controllers. Putting everything inside Livewire components makes logic hard to reuse. Our Hybrid Action-Oriented approach solves this by isolating responsibilities.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm hover:shadow-md transition opacity-0 animate-fade-in-up delay-100">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">1. Thin Livewire UI</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Livewire acts strictly as a presentation layer. It handles component state, user interactions, and triggers actions. No complex logic resides here.
                    </p>
                </div>

                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm hover:shadow-md transition opacity-0 animate-fade-in-up delay-200">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">2. DTOs (Data Bags)</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Data Transfer Objects (DTO) provide strict typing. Data moving from the UI to the backend is strongly typed and predictable.
                    </p>
                </div>

                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm hover:shadow-md transition opacity-0 animate-fade-in-up delay-300">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-gretiva-purple flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">3. Actions (Logic)</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        The core of the architecture. Single-responsibility classes (e.g., <code>CreateProjectAction</code>) encapsulate business logic and database writes entirely.
                    </p>
                </div>

                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 shadow-sm hover:shadow-md transition opacity-0 animate-fade-in-up delay-300">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-gretiva-orange flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold mb-2">4. Services</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Reusable helper logic across actions, such as API calls or our custom <code>AutoTranslationService</code> for multi-language inputs.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="playground" class="py-24 bg-slate-900 text-slate-300 relative border-t border-slate-800">
        <div class="absolute -right-40 top-0 w-96 h-96 bg-gretiva-orange rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>
        <div class="absolute -left-40 bottom-0 w-96 h-96 bg-gretiva-purple rounded-full mix-blend-multiply filter blur-3xl opacity-10"></div>

        <div class="container mx-auto px-6 lg:px-12 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-12 opacity-0 animate-fade-in-up">
                <h2 class="text-sm font-bold text-orange-400 tracking-widest uppercase mb-2">Interactive Code Playground</h2>
                <h3 class="text-3xl lg:text-4xl font-extrabold text-white mb-4">See the Data Flow in Action</h3>
                <p class="text-slate-400 text-lg">
                    Click through the workflow tabs to understand how data moves from the UI, gets structured, and is processed safely.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 opacity-0 animate-fade-in-up delay-200">

                <div class="lg:col-span-4 space-y-3">
                    <button id="tab-livewire" onclick="switchTab('livewire')" class="code-tab w-full text-left p-4 rounded-xl border-l-4 bg-white/10 border-gretiva-orange transition-all duration-200 hover:bg-white/5">
                        <h4 class="text-white font-bold">1. Livewire Component</h4>
                        <p class="text-sm text-slate-400 mt-1">Gathers form data and passes it to the Action.</p>
                    </button>
                    <button id="tab-dto" onclick="switchTab('dto')" class="code-tab w-full text-left p-4 rounded-xl border-l-4 border-transparent hover:bg-white/5 transition-all duration-200">
                        <h4 class="text-white font-bold">2. Data Transfer Object</h4>
                        <p class="text-sm text-slate-400 mt-1">Validates and strongly types the payload.</p>
                    </button>
                    <button id="tab-action" onclick="switchTab('action')" class="code-tab w-full text-left p-4 rounded-xl border-l-4 border-transparent hover:bg-white/5 transition-all duration-200">
                        <h4 class="text-white font-bold">3. Action Class</h4>
                        <p class="text-sm text-slate-400 mt-1">Executes the business logic & DB write.</p>
                    </button>
                    <button id="tab-service" onclick="switchTab('service')" class="code-tab w-full text-left p-4 rounded-xl border-l-4 border-transparent hover:bg-white/5 transition-all duration-200">
                        <h4 class="text-white font-bold">4. Shared Service</h4>
                        <p class="text-sm text-slate-400 mt-1">Processes auto-translation before saving.</p>
                    </button>
                </div>

                <div class="lg:col-span-8">
                    <div class="bg-[#0d1117] border border-slate-700 rounded-2xl overflow-hidden shadow-2xl h-[550px] flex flex-col">
                        <div class="bg-slate-800/80 px-4 py-3 flex items-center border-b border-slate-700">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <span id="code-filename" class="mx-auto text-xs text-slate-400 font-mono tracking-wider">app/Livewire/Admin/Project/Index.php</span>
                        </div>

                        <div class="p-6 overflow-y-auto flex-1 font-mono text-sm leading-relaxed whitespace-pre overflow-x-auto text-white">

<div id="code-livewire" class="code-content block">
<span class="comment">&lt;?php</span>

<span class="keyword">namespace</span> <span class="class-name">App\Livewire\Admin\Project</span>;

<span class="keyword">use</span> <span class="class-name">Livewire\Component</span>;
<span class="keyword">use</span> <span class="class-name">App\DTOs\Project\ProjectData</span>;
<span class="keyword">use</span> <span class="class-name">App\Actions\Project\CreateProjectAction</span>;

<span class="keyword">class</span> <span class="class-name text-white">Index</span> <span class="keyword">extends</span> <span class="class-name">Component</span>
{
    <span class="comment">// State terikat langsung dengan input View (v-model / wire:model)</span>
    <span class="keyword">public</span> <span class="class-name">array</span> <span class="variable">$name</span> = [<span class="string">'id'</span> => <span class="string">''</span>, <span class="string">'en'</span> => <span class="string">''</span>];
    <span class="keyword">public</span> <span class="class-name">string</span> <span class="variable">$status</span> = <span class="string">'active'</span>;

    <span class="comment">/**
     * Method dipanggil saat form disubmit.
     * Injection Action class secara otomatis oleh Laravel Container.
     */</span>
    <span class="keyword">public function</span> <span class="function">save</span>(<span class="class-name">CreateProjectAction</span> <span class="variable">$action</span>)
    {
        <span class="comment">// 1. Validasi dasar Livewire</span>
        <span class="variable">$this</span>-><span class="function">validate</span>();

        <span class="comment">// 2. [BEST PRACTICE] Bungkus data mentah ke dalam DTO
        // Ini mencegah kita mengirim parameter yang berantakan ke Action</span>
        <span class="variable">$data</span> = <span class="keyword">new</span> <span class="class-name">ProjectData</span>(
            name: <span class="variable">$this</span>->name,
            status: <span class="variable">$this</span>->status
        );

        <span class="comment">// 3. Eksekusi logika bisnis yang ada di Action</span>
        <span class="variable">$action</span>-><span class="function">execute</span>(<span class="variable">$data</span>);

        <span class="comment">// 4. Selesai! Tutup modal dan reset UI</span>
        <span class="variable">$this</span>->modalOpen = <span class="keyword">false</span>;
        <span class="variable">$this</span>-><span class="function">success</span>(<span class="string">'Project created successfully!'</span>);
    }
}
</div>

<div id="code-dto" class="code-content hidden">
<span class="comment">&lt;?php</span>

<span class="keyword">namespace</span> <span class="class-name">App\DTOs\Project</span>;

<span class="comment">/**
 * Data Transfer Object (DTO)
 * Bertugas sebagai "Kontrak Ketat" antara UI dan Backend.
 */</span>
<span class="keyword">class</span> <span class="class-name text-white">ProjectData</span>
{
    <span class="comment">// Menggunakan PHP 8 Constructor Property Promotion + Readonly
    // Data yang sudah masuk ke DTO tidak boleh diubah lagi (Immutable)</span>
    <span class="keyword">public function</span> <span class="function">__construct</span>(
        <span class="keyword">public readonly</span> <span class="class-name">array</span> <span class="variable">$name</span>,
        <span class="keyword">public readonly</span> <span class="class-name">string</span> <span class="variable">$status</span>,
        <span class="keyword">public readonly</span> <span class="class-name">?string</span> <span class="variable">$description</span> = <span class="keyword">null</span>
    ) {}

    <span class="comment">/*
     * Mengapa pakai DTO daripada $request->all() atau array biasa?
     * 1. IDE Auto-completion: Saat mengetik $data->..., IDE akan tahu pasti ada ->name
     * 2. Keamanan: Mencegah serangan mass-assignment karena field sudah pasti
     * 3. Kejelasan: Developer tahu pasti data apa saja yang dibutuhkan untuk membuat Project.
     */</span>
}
</div>

<div id="code-action" class="code-content hidden">
<span class="comment">&lt;?php</span>

<span class="keyword">namespace</span> <span class="class-name">App\Actions\Project</span>;

<span class="keyword">use</span> <span class="class-name">App\Models\Project</span>;
<span class="keyword">use</span> <span class="class-name">App\DTOs\Project\ProjectData</span>;
<span class="keyword">use</span> <span class="class-name">App\Services\AutoTranslationService</span>;

<span class="comment">/**
 * Action Class - "The Heart of Business Logic"
 * Class ini hanya punya satu tujuan (Single Responsibility): Membuat Project.
 */</span>
<span class="keyword">class</span> <span class="class-name text-white">CreateProjectAction</span>
{
    <span class="keyword">public function</span> <span class="function">__construct</span>(
        <span class="keyword">protected</span> <span class="class-name">AutoTranslationService</span> <span class="variable">$translator</span>
    ) {}

    <span class="comment">/**
     * Menerima DTO yang sudah divalidasi, bukan array mentah.
     */</span>
    <span class="keyword">public function</span> <span class="function">execute</span>(<span class="class-name">ProjectData</span> <span class="variable">$data</span>): <span class="class-name">Project</span>
    {
        <span class="comment">// 1. Panggil Service Eksternal (contoh: Auto-Translate Google)
        // Jika Admin hanya input bahasa Indonesia, sistem otomatis isi versi Inggrisnya.</span>
        <span class="variable">$finalName</span> = <span class="variable">$this</span>->translator-><span class="function">fillMissingTranslations</span>(<span class="variable">$data</span>->name);

        <span class="comment">// 2. Eksekusi ke Database (Eloquent)</span>
        <span class="keyword">return</span> <span class="class-name">Project</span>::<span class="function">create</span>([
            <span class="string">'name'</span>   => <span class="variable">$finalName</span>, <span class="comment">// Disimpan sebagai JSON {"id":"..", "en":".."} oleh Spatie</span>
            <span class="string">'status'</span> => <span class="variable">$data</span>->status,
            <span class="string">'created_by'</span> => <span class="function">auth</span>()-><span class="function">id</span>(),
        ]);

        <span class="comment">// * Jika kita butuh trigger email atau notif, letakkan event() disini.</span>
    }
}
</div>

<div id="code-service" class="code-content hidden">
<span class="comment">&lt;?php</span>

<span class="keyword">namespace</span> <span class="class-name">App\Services</span>;

<span class="keyword">use</span> <span class="class-name">Stichoza\GoogleTranslate\GoogleTranslate</span>;

<span class="comment">/**
 * Service Class
 * Logika utilitas yang bisa dipakai berulang kali oleh berbagai Action.
 */</span>
<span class="keyword">class</span> <span class="class-name text-white">AutoTranslationService</span>
{
    <span class="comment">/**
     * Memeriksa array terjemahan dan mengisi bagian yang kosong via Google Translate.
     * @param array $data Format yang diharapkan: ['id' => '...', 'en' => '...']
     */</span>
    <span class="keyword">public function</span> <span class="function">fillMissingTranslations</span>(<span class="class-name">array</span> <span class="variable">$data</span>): <span class="class-name">array</span>
    {
        <span class="variable">$id</span> = <span class="variable">$data</span>[<span class="string">'id'</span>] ?? <span class="keyword">null</span>;
        <span class="variable">$en</span> = <span class="variable">$data</span>[<span class="string">'en'</span>] ?? <span class="keyword">null</span>;

        <span class="comment">// Skenario 1: Admin ketik Indo, Inggris kosong -> Translate ID ke EN</span>
        <span class="keyword">if</span> (!<span class="keyword">empty</span>(<span class="variable">$id</span>) && <span class="keyword">empty</span>(<span class="variable">$en</span>)) {
            <span class="variable">$data</span>[<span class="string">'en'</span>] = <span class="class-name">GoogleTranslate</span>::<span class="function">trans</span>(<span class="variable">$id</span>, <span class="string">'en'</span>, <span class="string">'id'</span>);
        }

        <span class="comment">// Skenario 2: Admin ketik Inggris, Indo kosong -> Translate EN ke ID</span>
        <span class="keyword">elseif</span> (!<span class="keyword">empty</span>(<span class="variable">$en</span>) && <span class="keyword">empty</span>(<span class="variable">$id</span>)) {
            <span class="variable">$data</span>[<span class="string">'id'</span>] = <span class="class-name">GoogleTranslate</span>::<span class="function">trans</span>(<span class="variable">$en</span>, <span class="string">'id'</span>, <span class="string">'en'</span>);
        }

        <span class="keyword">return</span> <span class="variable">$data</span>;
    }
}
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white text-slate-800 overflow-hidden relative">
        <div class="container mx-auto px-6 lg:px-12 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 font-mono text-sm shadow-lg relative z-10 opacity-0 animate-fade-in-up">
                <div class="flex items-center gap-2 mb-4 border-b border-slate-200 pb-4">
                    <span class="text-slate-500 text-xs font-bold uppercase">Directory Structure</span>
                </div>
                <pre class="text-slate-700 leading-loose">
<span class="text-blue-600 font-bold">app/</span>
├── <span class="text-emerald-600 font-bold">Actions/</span> <span class="text-slate-400 italic">             # DB Writes & Logic</span>
│   └── Project/
│       └── CreateProjectAction.php
├── <span class="text-emerald-600 font-bold">DTOs/</span> <span class="text-slate-400 italic">                # Type-safe Contracts</span>
│   └── Project/
│       └── ProjectData.php
├── <span class="text-emerald-600 font-bold">Services/</span> <span class="text-slate-400 italic">            # Helpers</span>
│   └── AutoTranslationService.php
├── <span class="text-emerald-600 font-bold">Livewire/</span> <span class="text-slate-400 italic">            # UI Controllers</span>
│   └── Admin/Project/Index.php
└── <span class="text-emerald-600 font-bold">Models/</span> <span class="text-slate-400 italic">              # Eloquent</span>
    └── Project.php
                </pre>
            </div>

            <div class="opacity-0 animate-fade-in-up delay-200 relative z-10">
                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-6">Why organizing like this?</h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="mt-1 flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-gretiva-orange">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-900 font-bold text-lg">Highly Reusable Code</h4>
                            <p class="text-slate-600 text-sm mt-1">Want to create a project via an API Endpoint instead of Livewire? Just inject <code>CreateProjectAction</code>. No code duplication required.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="mt-1 flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-gretiva-orange">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-900 font-bold text-lg">Easy to Test</h4>
                            <p class="text-slate-600 text-sm mt-1">Testing a Livewire component requires mocking UI interactions. Testing an Action class is as simple as passing a DTO and asserting the DB state.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="mt-1 flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-gretiva-orange">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-900 font-bold text-lg">Predictable Data Flow</h4>
                            <p class="text-slate-600 text-sm mt-1">Because of DTOs, the Action always knows exactly what data structure to expect. Goodbye <code>$request->all()</code> ambiguity.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800">
        <div class="container mx-auto px-6 lg:px-12 flex flex-col md:flex-row justify-between items-center gap-6">

            <div class="flex items-center gap-2 font-bold text-xl tracking-tight text-white">
                <img src="{{ asset('assets/images/logo_idig_htech.png') }}" alt="IDIG Logo" class="w-8 h-8 object-contain drop-shadow-lg" onerror="this.style.display='none';">
                IDIG Template
            </div>

            <div class="text-center md:text-right">
                <p class="text-sm text-slate-500 font-medium">Architected & Engineered by</p>
                <div class="font-extrabold text-lg text-white mt-1">Eka Nata</div>
                <div class="flex items-center justify-center md:justify-end gap-4 mt-2">
                    <a href="https://github.com/ekanata14" target="_blank" class="text-slate-400 hover:text-white transition flex items-center gap-1 text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                        ekanata14
                    </a>
                    <a href="https://instagram.com/ekanata_" target="_blank" class="text-slate-400 hover:text-pink-500 transition flex items-center gap-1 text-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path></svg>
                        @ekanata_
                    </a>
                </div>
            </div>

        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elements = document.querySelectorAll('.opacity-0');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0');
                        entry.target.classList.add('animate-fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });

            elements.forEach(el => observer.observe(el));
        });

        const fileNames = {
            'livewire': 'app/Livewire/Admin/Project/Index.php',
            'dto': 'app/DTOs/Project/ProjectData.php',
            'action': 'app/Actions/Project/CreateProjectAction.php',
            'service': 'app/Services/AutoTranslationService.php'
        };

        function switchTab(tabId) {
            document.querySelectorAll('.code-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('code-' + tabId).classList.remove('hidden');

            document.querySelectorAll('.code-tab').forEach(el => {
                el.classList.remove('bg-white/10', 'border-gretiva-orange');
                el.classList.add('border-transparent');
            });

            const activeTab = document.getElementById('tab-' + tabId);
            activeTab.classList.remove('border-transparent');
            activeTab.classList.add('bg-white/10', 'border-gretiva-orange');

            document.getElementById('code-filename').innerText = fileNames[tabId];
        }
    </script>
</body>
</html>
