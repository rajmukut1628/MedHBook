@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10">
    <div class="max-w-5xl mx-auto px-6">

        <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 overflow-hidden">

            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-8 py-7">
                <h2 class="text-4xl font-extrabold text-white">Add New Doctor</h2>
                <p class="text-emerald-100 mt-2">
                    Create doctor profile with login email and password
                </p>
            </div>

            <form action="{{ route('doctors.store') }}" method="POST" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Doctor Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="Enter doctor name" required>
                        @error('name')
                            <p class="text-red-300 text-sm mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="doctor@example.com" required>
                        @error('email')
                            <p class="text-red-300 text-sm mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Password</label>
                        <input type="text" name="password" value="{{ old('password') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="Minimum 6 characters" required>
                        @error('password')
                            <p class="text-red-300 text-sm mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="+8801XXXXXXXXX" required>
                        @error('phone')
                            <p class="text-red-300 text-sm mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Specialist</label>
                        <select name="specialist"
                                class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                required>
                            <option value="">Select Specialist</option>
                            <option value="Medicine" {{ old('specialist') == 'Medicine' ? 'selected' : '' }}>Medicine</option>
                            <option value="Cardiologist" {{ old('specialist') == 'Cardiologist' ? 'selected' : '' }}>Cardiologist</option>
                            <option value="Neurologist" {{ old('specialist') == 'Neurologist' ? 'selected' : '' }}>Neurologist</option>
                            <option value="Dermatologist" {{ old('specialist') == 'Dermatologist' ? 'selected' : '' }}>Dermatologist</option>
                            <option value="Pediatrician" {{ old('specialist') == 'Pediatrician' ? 'selected' : '' }}>Pediatrician</option>
                            <option value="Orthopedic" {{ old('specialist') == 'Orthopedic' ? 'selected' : '' }}>Orthopedic</option>
                            <option value="Dentist" {{ old('specialist') == 'Dentist' ? 'selected' : '' }}>Dentist</option>
                            <option value="ENT Specialist" {{ old('specialist') == 'ENT Specialist' ? 'selected' : '' }}>ENT Specialist</option>
                            <option value="Gynecologist" {{ old('specialist') == 'Gynecologist' ? 'selected' : '' }}>Gynecologist</option>
                            <option value="Nephrologist" {{ old('specialist') == 'Nephrologist' ? 'selected' : '' }}>Nephrologist</option>
                            <option value="Endocrinologist" {{ old('specialist') == 'Endocrinologist' ? 'selected' : '' }}>Endocrinologist</option>
                            <option value="Gastroenterologist" {{ old('specialist') == 'Gastroenterologist' ? 'selected' : '' }}>Gastroenterologist</option>
                        </select>
                        @error('specialist')
                            <p class="text-red-300 text-sm mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Experience</label>
                        <input type="number" name="experience" value="{{ old('experience') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="Years of experience">
                        @error('experience')
                            <p class="text-red-300 text-sm mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Degree</label>
                        <input type="text" name="degree" value="{{ old('degree') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="MBBS, FCPS">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">License Number</label>
                        <input type="text" name="license_number" value="{{ old('license_number') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="BMDC license number">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-white mb-2">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification') }}"
                               class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                               placeholder="Professional qualifications">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-white mb-2">Chamber Address</label>
                        <textarea name="chamber_address" rows="4"
                                  class="w-full px-5 py-3 rounded-2xl border border-white/20 bg-slate-950/70 text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                  placeholder="Doctor chamber address">{{ old('chamber_address') }}</textarea>
                    </div>

                </div>

                <div class="mt-6 p-5 rounded-2xl bg-emerald-500/10 border border-emerald-400/20 text-emerald-100 font-semibold">
                    Doctor can login using this email and password after creation.
                </div>

                <div class="flex items-center justify-between pt-8">
                    <a href="{{ route('doctors.index') }}"
                       class="px-6 py-3 rounded-2xl bg-white/10 text-white font-bold hover:bg-white/20">
                        ← Back
                    </a>

                    <button type="submit"
                            class="px-8 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-extrabold shadow-lg hover:shadow-xl">
                        Create Doctor Account
                    </button>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection