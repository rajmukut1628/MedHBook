<x-app-layout>
    <div class="min-h-screen bg-slate-950 py-10 px-6">
        <div class="max-w-2xl mx-auto bg-white/10 p-8 rounded-3xl border border-white/10">
            @include('profile.partials.update-password-form')

            <a href="{{ route('profile.edit') }}" class="mt-6 inline-block text-white underline">
                ← Back to Profile
            </a>
        </div>
    </div>
</x-app-layout>