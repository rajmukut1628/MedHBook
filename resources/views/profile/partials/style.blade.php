<style>
*{
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    margin:0;
    background:
        radial-gradient(circle at top right, rgba(16,185,129,.18), transparent 30%),
        radial-gradient(circle at bottom left, rgba(124,58,237,.18), transparent 30%),
        linear-gradient(135deg,#020617,#064e3b,#0f172a);
    color:white;
}

.page{
    min-height:100vh;
    padding:40px 20px;
    display:flex;
    justify-content:center;
    align-items:center;
}

.panel{
    width:100%;
    max-width:760px;
    background:rgba(255,255,255,0.10);
    backdrop-filter:blur(18px);
    border:1px solid rgba(255,255,255,0.18);
    border-radius:34px;
    padding:42px;
    box-shadow:
        0 35px 90px rgba(0,0,0,.40),
        inset 0 1px 0 rgba(255,255,255,.08);
}

h1{
    margin:0;
    font-size:42px;
    font-weight:900;
    letter-spacing:-0.5px;
}

p{
    color:#cbd5e1;
    margin-top:10px;
    line-height:1.6;
}

.form{
    margin-top:30px;
}

label{
    display:block;
    margin:18px 0 8px;
    font-weight:800;
    color:#f8fafc;
}

input,
textarea,
select{
    width:100%;
    padding:16px 18px;
    border-radius:18px;
    border:1px solid rgba(255,255,255,0.14);
    background:rgba(255,255,255,0.10);
    color:white;
    outline:none;
    transition:.25s;
}

input:focus,
textarea:focus,
select:focus{
    border-color:#10b981;
    box-shadow:0 0 0 4px rgba(16,185,129,.16);
    transform:translateY(-1px);
}

input::placeholder,
textarea::placeholder{
    color:#94a3b8;
}

button{
    margin-top:26px;
    width:100%;
    padding:17px;
    border:none;
    border-radius:20px;
    background:linear-gradient(135deg,#10b981,#22c55e);
    color:white;
    font-size:17px;
    font-weight:900;
    cursor:pointer;
    transition:.25s;
    box-shadow:0 18px 35px rgba(16,185,129,.28);
}

button:hover{
    transform:translateY(-3px);
    box-shadow:0 24px 45px rgba(16,185,129,.35);
}

.back{
    display:inline-block;
    margin-top:26px;
    color:#6ee7b7;
    text-decoration:none;
    font-weight:800;
    transition:.2s;
}

.back:hover{
    color:#ffffff;
}

.alert{
    margin-top:22px;
    padding:16px 18px;
    border-radius:18px;
    background:rgba(16,185,129,.22);
    border:1px solid #10b981;
    color:#ecfdf5;
    font-weight:700;
}

.settings-grid{
    display:grid;
    grid-template-columns:1fr;
    gap:18px;
    margin-top:30px;
}

.setting-card{
    padding:24px;
    border-radius:24px;
    background:rgba(255,255,255,0.10);
    border:1px solid rgba(255,255,255,0.16);
    transition:.25s;
}

.setting-card:hover{
    transform:translateY(-4px);
    background:rgba(255,255,255,0.14);
}

.setting-card h3{
    margin:0;
    font-size:22px;
    font-weight:900;
}

.setting-card p{
    margin-top:10px;
}

@media(max-width:768px){

    .page{
        padding:18px;
        align-items:flex-start;
    }

    .panel{
        padding:26px;
        border-radius:26px;
    }

    h1{
        font-size:32px;
    }
}
</style>