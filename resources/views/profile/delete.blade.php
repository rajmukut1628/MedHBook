<x-app-layout>
<div class="min-h-screen bg-slate-950 py-10 px-6">
    <div class="max-w-2xl mx-auto bg-white/10 border border-red-500/30 rounded-3xl p-8 text-white">
        @include('profile.partials.delete-user-form')

        <a href="{{ route('profile.edit') }}" class="mt-6 inline-block text-slate-300 underline">
            ← Back to Profile
        </a>
    </div>
</div>
</x-app-layout>