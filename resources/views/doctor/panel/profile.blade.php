<x-app-layout>
<div class="p-6 max-w-4xl mx-auto">

    <h1 class="text-3xl font-bold mb-6">Doctor Profile</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST"
          action="{{ route('doctor.profile.update') }}"
          enctype="multipart/form-data"
          class="bg-white shadow rounded-xl p-6 space-y-4">

        @csrf

        <div>
            <label class="font-semibold">Photo</label>
            <input type="file" name="photo"
                   class="w-full border rounded-lg p-2 mt-1">
        </div>

        <div>
            <label class="font-semibold">Qualification</label>
            <input type="text" name="qualification"
                   value="{{ $profile->qualification ?? '' }}"
                   class="w-full border rounded-lg p-2 mt-1">
        </div>

        <div>
            <label class="font-semibold">Degrees</label>
            <input type="text" name="degrees"
                   value="{{ $profile->degrees ?? '' }}"
                   class="w-full border rounded-lg p-2 mt-1">
        </div>

        <div>
            <label class="font-semibold">Experience</label>
            <input type="number" name="experience"
                   value="{{ $profile->experience ?? '' }}"
                   class="w-full border rounded-lg p-2 mt-1">
        </div>

        <div>
            <label class="font-semibold">Consultation Fee</label>
            <input type="number" name="consultation_fee"
                   value="{{ $profile->consultation_fee ?? '' }}"
                   class="w-full border rounded-lg p-2 mt-1">
        </div>

        <div>
            <label class="font-semibold">Chamber Address</label>
            <textarea name="chamber_address"
                class="w-full border rounded-lg p-2 mt-1">{{ $profile->chamber_address ?? '' }}</textarea>
        </div>

        <div>
            <label class="font-semibold">Languages</label>
            <input type="text" name="languages"
                   value="{{ $profile->languages ?? '' }}"
                   class="w-full border rounded-lg p-2 mt-1">
        </div>

        <div>
            <label class="font-semibold">
                <input type="checkbox" name="online_status" value="1"
                {{ isset($profile) && $profile->online_status ? 'checked' : '' }}>
                Online Available
            </label>
        </div>

        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg">
            Save Profile
        </button>

    </form>

</div>
</x-app-layout>