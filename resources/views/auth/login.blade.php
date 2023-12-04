<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="flex items-center justify-center">
        <x-primary-link href="/auth/redirect">
            {{ __('Login (Github)') }}
        </x-primary-link>
    </div>
</x-guest-layout>
