<div class="max-w-4xl mx-auto">
    <x-header title="{{ __('Settings') }}" subtitle="{{ __('Manage your account and preferences') }}" separator />

    <x-tabs selected="profile-tab">

        {{-- TAB 1: PROFIL (UTAMA) --}}
        <x-tab name="profile-tab" label="{{ __('Profile') }}" icon="o-user">
            <x-card class="bg-base-100 shadow-sm mt-4">

                {{-- HEADER PROFIL BERDASARKAN ROLE --}}
                <div class="flex items-center gap-4 mb-8 p-4 bg-base-200/50 rounded-xl border border-base-300">
                    <div class="relative group">
                        {{-- Logika Preview: Cek file baru (temp) -> file lama (DB) -> default null --}}
                        <x-avatar :image="$profile_photo
                            ? $profile_photo->temporaryUrl()
                            : ($existing_photo
                                ? asset('storage/' . $existing_photo)
                                : null)" class="!w-20 !h-20" />

                        <div
                            class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition pointer-events-none">
                            <x-icon name="o-camera" class="w-6 h-6 text-white" />
                        </div>
                    </div>
                    <div>
                        <h3 class="font-black text-xl">{{ $name }}</h3>
                        <div class="flex gap-2 items-center">
                            @if ($role === 'super_admin')
                                <x-badge label="SUPER ADMIN" class="badge-error text-white font-bold text-[10px]" />
                            @elseif($role === 'pm')
                                <x-badge label="PROJECT MANAGER"
                                    class="badge-warning text-white font-bold text-[10px]" />
                            @else
                                <x-badge label="STAFF / DEVELOPER"
                                    class="badge-info text-white font-bold text-[10px]" />
                            @endif
                            <span class="text-xs opacity-50">{{ __('Member since') }}
                                {{ auth()->user()->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <x-form wire:submit="saveProfile">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="{{ __('Name') }}" wire:model="name" icon="o-user" inline />
                        <x-input label="{{ __('Email Address') }}" wire:model="email" icon="o-envelope" inline />

                        <div class="md:col-span-2">
                            {{-- Input file disesuaikan ke profile_photo --}}
                            <x-file label="{{ __('Change Profile Photo') }}" wire:model="profile_photo" accept="image/*"
                                hint="{{ __('Max 2MB. Square ratio recommended.') }}" />
                        </div>
                    </div>

                    <x-slot:actions>
                        <x-button label="{{ __('Update Profile') }}" class="btn-primary" type="submit"
                            spinner="saveProfile" />
                    </x-slot:actions>
                </x-form>
            </x-card>
        </x-tab>

        {{-- TAB 2: SECURITY (GANTI PASSWORD) --}}
        <x-tab name="security-tab" label="{{ __('Security') }}" icon="o-lock-closed">
            <x-card class="bg-base-100 shadow-sm mt-4">
                <div class="mb-6">
                    <h2 class="text-lg font-bold">{{ __('Update Password') }}</h2>
                    <p class="text-sm text-gray-500">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                </div>

                <x-form wire:submit="updatePassword">
                    <div class="grid grid-cols-1 gap-4 max-w-lg">

                        {{-- Current Password --}}
                        <div class="relative" x-data="{ show: false }">
                            <x-input label="{{ __('Current Password') }}" wire:model="current_password"
                                x-bind:type="show ? 'text' : 'password'" icon="o-key" class="pr-10" />
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-10 text-gray-400 hover:text-primary transition">
                                <x-icon name="o-eye" x-show="!show" class="w-4 h-4" />
                                <x-icon name="o-eye-slash" x-show="show" class="w-4 h-4" style="display: none;" />
                            </button>
                        </div>

                        {{-- New Password --}}
                        <div class="relative" x-data="{ show: false }">
                            <x-input label="{{ __('New Password') }}" wire:model="new_password"
                                x-bind:type="show ? 'text' : 'password'" icon="o-lock-closed" class="pr-10"
                                hint="{{ __('Minimum 8 characters') }}" />
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-10 text-gray-400 hover:text-primary transition">
                                <x-icon name="o-eye" x-show="!show" class="w-4 h-4" />
                                <x-icon name="o-eye-slash" x-show="show" class="w-4 h-4" style="display: none;" />
                            </button>
                        </div>

                        {{-- Confirm New Password --}}
                        <div class="relative" x-data="{ show: false }">
                            <x-input label="{{ __('Confirm New Password') }}" wire:model="new_password_confirmation"
                                x-bind:type="show ? 'text' : 'password'" icon="o-check-circle" class="pr-10" />
                            <button type="button" @click="show = !show"
                                class="absolute right-3 top-10 text-gray-400 hover:text-primary transition">
                                <x-icon name="o-eye" x-show="!show" class="w-4 h-4" />
                                <x-icon name="o-eye-slash" x-show="show" class="w-4 h-4" style="display: none;" />
                            </button>
                        </div>

                    </div>

                    <x-slot:actions>
                        <x-button label="{{ __('Save Password') }}" class="btn-primary" type="submit"
                            spinner="updatePassword" />
                    </x-slot:actions>
                </x-form>
            </x-card>
        </x-tab>

        {{-- TAB 3: PREFERENSI & NOTIFIKASI --}}
        <x-tab name="preferences-tab" label="{{ __('Preferences') }}" icon="o-cog-6-tooth">
            <x-card class="bg-base-100 shadow-sm mt-4">

                <div class="space-y-8">
                    {{-- LANGUAGE --}}
                    <div>
                        <div class="font-bold mb-3 flex items-center gap-2">
                            <x-icon name="o-language" class="w-4 h-4" /> {{ __('Language Settings') }}
                        </div>
                        <div class="max-w-xs">
                            <x-select :options="[
                                ['id' => 'id', 'name' => 'Indonesia 🇮🇩'],
                                ['id' => 'en', 'name' => 'English 🇺🇸'],
                            ]" wire:model.live="locale" />
                        </div>
                    </div>

                    {{-- SECURITY INFO (Hanya u/ Super Admin) --}}
                    @if ($role === 'super_admin')
                        <div class="divider"></div>
                        <div class="bg-error/5 p-4 rounded-xl border border-error/20">
                            <h4 class="font-bold text-error flex items-center gap-2">
                                <x-icon name="o-shield-exclamation" class="w-4 h-4" />
                                {{ __('Admin Security Notice') }}
                            </h4>
                            <p class="text-xs opacity-70 mt-1">
                                {{ __('As a Super Admin, your account has full access to system logs and sensitive data. Ensure you use a strong password and keep your email secure.') }}
                            </p>
                        </div>
                    @endif
                </div>

            </x-card>
        </x-tab>

    </x-tabs>
</div>
