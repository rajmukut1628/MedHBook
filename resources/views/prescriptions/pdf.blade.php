<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Prescription PDF</title>

<style>
body{
    font-family: DejaVu Sans, sans-serif;
    font-size:14px;
    color:#111;
    margin:30px;
}

.header{
    text-align:center;
    border-bottom:2px solid #16a34a;
    padding-bottom:15px;
    margin-bottom:25px;
}

.header h1{
    margin:0;
    font-size:28px;
    color:#16a34a;
}

.header p{
    margin:4px 0;
    color:#444;
}

.section{
    margin-bottom:18px;
}

.label{
    font-weight:bold;
    color:#16a34a;
    margin-bottom:5px;
}

.box{
    border:1px solid #ddd;
    padding:12px;
    border-radius:8px;
}

.footer{
    margin-top:50px;
    text-align:right;
}

.signature{
    border-top:1px solid #000;
    display:inline-block;
    padding-top:5px;
    min-width:220px;
    text-align:center;
}
</style>

</head>
<body>

<div class="header">
    <h1>MedHBook Digital Prescription</h1>
    <p>Professional Healthcare Management System</p>
</div>

<div class="section">
    <div class="label">Patient Name</div>
    <div class="box">
        {{ $prescription->patient->name ?? $prescription->patient->user->name ?? 'Patient' }}
    </div>
</div>

<div class="section">
    <div class="label">Doctor Name</div>
    <div class="box">
        Dr. {{ $prescription->doctor->name ?? $prescription->doctor->user->name ?? 'Doctor' }}
    </div>
</div>

<div class="section">
    <div class="label">Prescription Date</div>
    <div class="box">
        {{ $prescription->prescription_date->format('d M Y') }}
    </div>
</div>

<div class="section">
    <div class="label">Diagnosis</div>
    <div class="box">
        {!! nl2br(e($prescription->diagnosis)) !!}
    </div>
</div>

<div class="section">
    <div class="label">Medicines</div>
    <div class="box">
        {!! nl2br(e($prescription->medicines)) !!}
    </div>
</div>

<div class="section">
    <div class="label">Advice</div>
    <div class="box">
        {!! nl2br(e($prescription->advice ?? 'No advice')) !!}
    </div>
</div>

<div class="section">
    <div class="label">Next Visit Date</div>
    <div class="box">
        {{ $prescription->next_visit_date ? $prescription->next_visit_date->format('d M Y') : 'Not Required' }}
    </div>
</div>

<div class="footer">
    <div class="signature">
        Doctor Signature
    </div>
</div>

</body>
</html>