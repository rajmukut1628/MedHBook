<x-app-layout>
    <div class="p-8">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-bold text-yellow-600">
                Doctor Account Pending Verification
            </h1>

            <p class="mt-4 text-gray-700">
                Your account is waiting for admin approval.
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">
                    Logout
                </button>
            </form>
        </div>
    </div>
</x-app-layout>