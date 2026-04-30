<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MedHBook</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#050505] text-white">

<nav class="w-full border-b border-white/10 bg-black/80">
<div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

<a href="/" class="flex items-center gap-3">
<div class="w-11 h-11 rounded-xl bg-emerald-500 flex items-center justify-center">
❤
</div>
<span class="text-2xl font-bold">MedHBook</span>
</a>

</div>
</nav>

<section class="max-w-7xl mx-auto px-6 py-16">
<div class="grid grid-cols-1 lg:grid-cols-2 min-h-[560px] rounded-[28px] overflow-hidden border border-white/10 bg-[#111]">

<div class="flex items-center px-10 lg:px-16 py-16">
<div>

<h1 class="text-5xl font-black leading-tight">
One Platform for
<span class="text-emerald-400">Prescriptions,</span>
Appointments & Reports
</h1>

<p class="mt-6 text-gray-400">
Manage patients, doctors, appointments and reports.
</p>

<div class="mt-10 flex flex-wrap gap-4">

<a href="{{ route('login') }}"
class="px-7 py-3 rounded-xl bg-white text-black font-bold">
Login
</a>

<a href="{{ route('register', ['role' => 'patient']) }}"
class="px-7 py-3 rounded-xl bg-emerald-500 text-white font-bold">
Patient Register
</a>

<a href="{{ route('register', ['role' => 'doctor']) }}"
class="px-7 py-3 rounded-xl border border-white/20 text-white font-bold">
Doctor Register
</a>

</div>

</div>
</div>

<div class="bg-[#250000] flex items-center justify-center">
<h2 class="text-8xl font-black text-red-600">Care</h2>
</div>

</div>
</section>

</body>
</html>