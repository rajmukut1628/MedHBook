<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 py-10 px-6">
    <div class="max-w-6xl mx-auto">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-extrabold text-white">Create Prescription</h1>
                <p class="text-slate-300 mt-2">Search patient by ID, name, email or phone. Verify privacy key to unlock prescription.</p>
            </div>

            <a href="{{ route('doctor.dashboard') }}"
               class="px-5 py-3 rounded-xl bg-white/10 text-white border border-white/20 hover:bg-white/20">
                Back
            </a>
        </div>

        @if(session('error'))
            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-400/30 text-red-200 p-5 font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-400/30 text-red-200 p-5">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li class="font-semibold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('prescriptions.store') }}"
              class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl">
            @csrf

            <input type="hidden" name="patient_id" id="patient_id">

            <div class="grid lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 rounded-3xl bg-slate-950/50 border border-white/10 p-6">
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Search Patient
                    </label>

                    <input type="text"
                           id="patientSearch"
                           autocomplete="off"
                           placeholder="Search by P1, name, email or phone"
                           class="w-full rounded-2xl bg-slate-900/90 border border-white/20 text-white px-4 py-4 outline-none focus:border-emerald-400">

                    <div id="searchStatus" class="text-sm text-slate-400 mt-3">
                        Type patient ID, name, email or phone to search.
                    </div>

                    <div id="searchResults" class="mt-4 space-y-3"></div>
                </div>

                <div class="rounded-3xl bg-slate-950/50 border border-white/10 p-6">
                    <h3 class="text-white text-lg font-extrabold mb-4">Privacy Verification</h3>

                    <div id="selectedPatientBox" class="hidden mb-5 rounded-2xl bg-emerald-500/10 border border-emerald-400/20 p-4">
                        <p class="text-xs text-emerald-200 font-bold">Selected Patient</p>
                        <h4 id="selectedPatientName" class="text-white text-xl font-black mt-1"></h4>
                        <p id="selectedPatientCode" class="text-slate-300 text-sm mt-1"></p>
                        <p id="selectedPatientEmail" class="text-slate-400 text-sm mt-1"></p>
                    </div>

                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Patient Privacy Key
                    </label>

                    <input type="text"
                           name="privacy_key"
                           id="privacy_key"
                           placeholder="Enter patient privacy key"
                           class="w-full rounded-2xl bg-slate-900/90 border border-white/20 text-white px-4 py-4 outline-none focus:border-emerald-400">

                    <button type="button"
                            id="verifyBtn"
                            disabled
                            class="mt-4 w-full rounded-2xl bg-emerald-600/40 text-white font-black py-4 cursor-not-allowed">
                        Verify & Unlock
                    </button>

                    <div id="verifyMessage" class="mt-4 text-sm font-bold text-slate-300">
                        Select patient first.
                    </div>
                </div>

            </div>

            <div id="patientInfoCard" class="hidden mt-6 rounded-3xl bg-emerald-500/10 border border-emerald-400/20 p-6">
                <div class="grid md:grid-cols-5 gap-4 text-white">
                    <div>
                        <p class="text-xs text-emerald-200 font-bold">Patient ID</p>
                        <p id="infoCode" class="font-black"></p>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-200 font-bold">Name</p>
                        <p id="infoName" class="font-black"></p>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-200 font-bold">Phone</p>
                        <p id="infoPhone" class="font-black"></p>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-200 font-bold">Blood Group</p>
                        <p id="infoBlood" class="font-black"></p>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-200 font-bold">Gender</p>
                        <p id="infoGender" class="font-black"></p>
                    </div>
                </div>
            </div>

            <div id="lockedNotice" class="mt-8 rounded-3xl bg-yellow-500/10 border border-yellow-400/20 p-6 text-yellow-100 font-bold">
                Prescription form is locked. Search patient and verify patient privacy key first.
            </div>

            <div id="prescriptionFormArea" class="hidden mt-8 grid md:grid-cols-2 gap-6">

                <div>
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Prescription Date
                    </label>

                    <input type="date"
                           name="prescription_date"
                           value="{{ date('Y-m-d') }}"
                           class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"
                           required>
                </div>

                <div>
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Next Visit Date
                    </label>

                    <input type="date"
                           name="next_visit_date"
                           class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Diagnosis
                    </label>

                    <textarea name="diagnosis"
                              rows="4"
                              placeholder="Write diagnosis..."
                              class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"
                              required></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Medicines
                    </label>

                    <textarea name="medicines"
                              rows="5"
                              placeholder="Write medicines, dose, schedule..."
                              class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"
                              required></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-bold text-slate-200 mb-2 block">
                        Advice
                    </label>

                    <textarea name="advice"
                              rows="4"
                              placeholder="Write advice..."
                              class="w-full rounded-2xl bg-slate-900/80 border border-white/20 text-white px-4 py-3"></textarea>
                </div>

                <div class="md:col-span-2 text-right">
                    <button type="submit"
                            class="px-8 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black shadow-xl">
                        Save Prescription
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
let selectedPatient = null;
let searchTimer = null;

const patientSearch = document.getElementById('patientSearch');
const searchResults = document.getElementById('searchResults');
const searchStatus = document.getElementById('searchStatus');
const patientIdInput = document.getElementById('patient_id');
const privacyKeyInput = document.getElementById('privacy_key');
const verifyBtn = document.getElementById('verifyBtn');
const verifyMessage = document.getElementById('verifyMessage');

const selectedPatientBox = document.getElementById('selectedPatientBox');
const selectedPatientName = document.getElementById('selectedPatientName');
const selectedPatientCode = document.getElementById('selectedPatientCode');
const selectedPatientEmail = document.getElementById('selectedPatientEmail');

const patientInfoCard = document.getElementById('patientInfoCard');
const lockedNotice = document.getElementById('lockedNotice');
const prescriptionFormArea = document.getElementById('prescriptionFormArea');

patientSearch.addEventListener('input', function () {
    clearTimeout(searchTimer);

    const query = this.value.trim();

    resetVerificationOnly();

    if (query.length < 1) {
        searchResults.innerHTML = '';
        searchStatus.innerText = 'Type patient ID, name, email or phone to search.';
        return;
    }

    searchStatus.innerText = 'Searching patient...';

    searchTimer = setTimeout(() => {
        fetch(`{{ route('prescriptions.patient.search') }}?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            searchResults.innerHTML = '';

            if (!data.length) {
                searchStatus.innerText = 'No patient found.';
                return;
            }

            searchStatus.innerText = `${data.length} patient found. Click one patient to select.`;

            data.forEach(patient => {
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'w-full text-left rounded-2xl bg-white/10 border border-white/10 hover:border-emerald-400/60 p-4 transition';

                item.innerHTML = `
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-white font-black text-lg">${escapeHtml(patient.name)}</div>
                            <div class="text-slate-300 text-sm mt-1">
                                ${escapeHtml(patient.patient_code)} • ${escapeHtml(patient.email)} • ${escapeHtml(patient.phone)}
                            </div>
                        </div>
                        <div class="text-emerald-200 font-black text-sm">Select</div>
                    </div>
                `;

                item.addEventListener('click', () => selectPatient(patient));
                searchResults.appendChild(item);
            });
        })
        .catch(() => {
            searchStatus.innerText = 'Search failed. Please try again.';
        });
    }, 350);
});

function selectPatient(patient) {
    selectedPatient = patient;
    patientIdInput.value = patient.id;

    selectedPatientBox.classList.remove('hidden');
    selectedPatientName.innerText = patient.name;
    selectedPatientCode.innerText = patient.patient_code;
    selectedPatientEmail.innerText = patient.email;

    verifyBtn.disabled = false;
    verifyBtn.className = 'mt-4 w-full rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 cursor-pointer';
    verifyMessage.innerText = 'Enter privacy key and click verify.';
    verifyMessage.className = 'mt-4 text-sm font-bold text-slate-300';

    searchStatus.innerText = 'Patient selected. Now verify privacy key.';
}

verifyBtn.addEventListener('click', function () {
    if (!selectedPatient) {
        verifyMessage.innerText = 'Please select a patient first.';
        return;
    }

    const privacyKey = privacyKeyInput.value.trim();

    if (!privacyKey) {
        verifyMessage.innerText = 'Please enter patient privacy key.';
        verifyMessage.className = 'mt-4 text-sm font-bold text-yellow-200';
        return;
    }

    verifyBtn.disabled = true;
    verifyBtn.innerText = 'Verifying...';

    fetch(`{{ route('prescriptions.patient.verify') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': `{{ csrf_token() }}`,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            patient_id: selectedPatient.id,
            privacy_key: privacyKey
        })
    })
    .then(async res => {
        const data = await res.json();

        if (!res.ok) {
            throw data;
        }

        unlockPrescription(data.patient, data.message);
    })
    .catch(err => {
        verifyBtn.disabled = false;
        verifyBtn.innerText = 'Verify & Unlock';
        verifyMessage.innerText = err.message || 'Invalid privacy key.';
        verifyMessage.className = 'mt-4 text-sm font-bold text-red-300';
    });
});

function unlockPrescription(patient, message) {
    verifyBtn.innerText = 'Verified';
    verifyBtn.className = 'mt-4 w-full rounded-2xl bg-emerald-500 text-white font-black py-4';

    verifyMessage.innerText = message;
    verifyMessage.className = 'mt-4 text-sm font-bold text-emerald-200';

    document.getElementById('infoCode').innerText = patient.patient_code;
    document.getElementById('infoName').innerText = patient.name;
    document.getElementById('infoPhone').innerText = patient.phone;
    document.getElementById('infoBlood').innerText = patient.blood_group;
    document.getElementById('infoGender').innerText = patient.gender;

    patientInfoCard.classList.remove('hidden');
    lockedNotice.classList.add('hidden');
    prescriptionFormArea.classList.remove('hidden');
}

function resetVerificationOnly() {
    selectedPatient = null;
    patientIdInput.value = '';

    selectedPatientBox.classList.add('hidden');
    patientInfoCard.classList.add('hidden');
    prescriptionFormArea.classList.add('hidden');
    lockedNotice.classList.remove('hidden');

    verifyBtn.disabled = true;
    verifyBtn.innerText = 'Verify & Unlock';
    verifyBtn.className = 'mt-4 w-full rounded-2xl bg-emerald-600/40 text-white font-black py-4 cursor-not-allowed';

    verifyMessage.innerText = 'Select patient first.';
    verifyMessage.className = 'mt-4 text-sm font-bold text-slate-300';
}

function escapeHtml(text) {
    return String(text ?? '').replace(/[&<>"']/g, function (m) {
        return ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[m];
    });
}
</script>
</x-app-layout>