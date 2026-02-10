<div>
    <x-header title="{{ __('Settings') }}" separator />

    <x-tabs selected="preferences-tab">

        {{-- TAB 1: PREFERENSI --}}
        <x-tab name="preferences-tab" label="{{ __('Preferences') }}" icon="o-cog-6-tooth">
            <x-card class="bg-base-100 shadow-sm mt-4">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- LANGUAGE SWITCHER --}}
                    {{-- Menggunakan .live agar langsung memicu updatedLocale() --}}
                    <x-select label="{{ __('Language') }}" icon="o-language" :options="[['id' => 'id', 'name' => 'Indonesia 🇮🇩'], ['id' => 'en', 'name' => 'English 🇺🇸']]" wire:model.live="locale" />

                    {{-- NOTIFICATIONS --}}
                    <div class="border p-4 rounded-lg border-base-300">
                        <div class="font-bold mb-3 text-sm">{{ __('Notification Channels') }}</div>

                        {{-- Form Notifikasi terpisah --}}
                        <x-form wire:submit="savePreferences">
                            <x-checkbox label="Email Notification" wire:model="notifications.email" />
                            <x-checkbox label="WhatsApp Notification" wire:model="notifications.wa" />

                            <x-slot:actions>
                                <x-button label="{{ __('Save Preferences') }}" class="btn-sm btn-primary" type="submit"
                                    spinner />
                            </x-slot:actions>
                        </x-form>
                    </div>
                </div>

            </x-card>
        </x-tab>

        {{-- TAB 2: PROFIL --}}
        <x-tab name="profile-tab" label="{{ __('Profile') }}" icon="o-user">
            <x-card class="bg-base-100 shadow-sm mt-4">
                <x-form wire:submit="saveProfile">
                    <x-input label="{{ __('Name') }}" wire:model="name" icon="o-user" />
                    <x-input label="{{ __('Email') }}" wire:model="email" icon="o-envelope" />

                    <x-slot:actions>
                        <x-button label="{{ __('Update Profile') }}" class="btn-primary" type="submit"
                            spinner="saveProfile" />
                    </x-slot:actions>
                </x-form>
            </x-card>
        </x-tab>

    </x-tabs>
</div>
