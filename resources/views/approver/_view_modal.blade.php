{{--
    Request Detail Modal — shared across all approver list views.
    Usage:
      1. Include this partial at the bottom of any approver view that lists $requests.
      2. Add a "View" button: <button onclick="openDetail({{ json_encode($r->toArray() + ['_type'=>$r->_type]) }})">View</button>
--}}

{{-- ── Modal Overlay ── --}}
<div id="reqDetailModal"
     class="fixed inset-0 z-[999] hidden items-center justify-center p-4"
     style="background:rgba(15,23,42,0.55);backdrop-filter:blur(4px);"
     onclick="if(event.target===this)closeDetail()">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative">

        {{-- Header --}}
        <div id="rdm-header" class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white z-10 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div id="rdm-icon" class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 id="rdm-title" class="text-base font-bold text-gray-900 leading-tight">Request Details</h2>
                    <p id="rdm-subtitle" class="text-xs text-gray-400"></p>
                </div>
            </div>
            <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-700 transition-colors p-1.5 rounded-lg hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div id="rdm-body" class="px-6 py-5 space-y-6"></div>

    </div>
</div>

<style>
    #reqDetailModal.open { display: flex; }
    .rdm-section-title {
        font-size: 10px; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: #6b7280;
        padding-bottom: 8px; border-bottom: 1px solid #f3f4f6;
        margin-bottom: 12px;
    }
    .rdm-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
    @media(max-width:480px){ .rdm-grid { grid-template-columns: 1fr; } }
    .rdm-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
    .rdm-field-label { font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing:.05em; margin-bottom: 2px; }
    .rdm-field-value { font-size: 13.5px; font-weight: 500; color: #111827; word-break: break-word; }
    .rdm-field-value.muted { color: #6b7280; }
    .rdm-badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 700;
                 padding: 2px 10px; border-radius: 9999px; }
    .rdm-status-pending       { background:#fef3c7; color:#92400e; }
    .rdm-status-approved_by_1 { background:#dbeafe; color:#1e40af; }
    .rdm-status-approved_by_2 { background:#d1fae5; color:#065f46; }
    .rdm-status-completed     { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
    .rdm-status-rejected      { background:#fee2e2; color:#991b1b; }
    .rdm-type-vpn     { background:#eef2ff; color:#4338ca; }
    .rdm-type-internet{ background:#ecfeff; color:#0e7490; }
    .rdm-type-vm      { background:#f0fdf4; color:#166534; }
    .rdm-type-hosting { background:#fff7ed; color:#c2410c; }
    .rdm-approval-track { display: flex; align-items: flex-start; gap: 0; }
    .rdm-track-step { display: flex; flex-direction: column; align-items: center; flex: 1; }
    .rdm-track-dot { width:12px; height:12px; border-radius:50%; flex-shrink:0; margin-bottom:4px; }
    .rdm-track-line { flex:1; height:2px; align-self: center; margin-bottom: 14px; }
    .rdm-dot-done   { background:#10b981; }
    .rdm-dot-active { background:#f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,.25); }
    .rdm-dot-future { background:#e5e7eb; }
    .rdm-line-done  { background:#10b981; }
    .rdm-line-future{ background:#e5e7eb; }
</style>

<script>
const TYPE_META = {
    'VPN':             { cls: 'rdm-type-vpn',      iconColor:'#4338ca', bg:'#eef2ff' },
    'Internet Access': { cls: 'rdm-type-internet', iconColor:'#0e7490', bg:'#ecfeff' },
    'VM Request':      { cls: 'rdm-type-vm',       iconColor:'#166534', bg:'#f0fdf4' },
    'Web Hosting':     { cls: 'rdm-type-hosting',  iconColor:'#c2410c', bg:'#fff7ed' },
};

const STATUS_LABELS = {
    pending:       'Pending Recommendation',
    approved_by_1: 'Recommended (Awaiting Dean)',
    approved_by_2: 'Dean Approved',
    completed:     'Completed',
    rejected:      'Rejected',
};

function fmtDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
}
function fmtDateOnly(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'});
}
function val(v) {
    return (v === null || v === undefined || v === '') ? '—' : v;
}
function field(label, value, muted) {
    return `<div>
        <p class="rdm-field-label">${label}</p>
        <p class="rdm-field-value ${muted ? 'muted' : ''}">${val(value)}</p>
    </div>`;
}

function buildBody(r) {
    const type = r._type;
    const status = r.approval_status || 'pending';
    const sections = [];

    // ── Section: Requester Info ──────────────────────────────────────
    const requesterName  = r.name || r.owner_name || '—';
    const requesterEmail = r.email || r.institute_email || '—';

    let reqFields = '';
    if (type === 'VPN') {
        reqFields = `
            ${field('Full Name', requesterName)}
            ${field('Email', requesterEmail)}
            ${field('Contact / Phone', r.contact)}
            ${field('Operating System', r.operating_system)}
            ${field('VPN Start Date', fmtDateOnly(r.start_date))}
            ${field('VPN End Date', fmtDateOnly(r.end_date))}`;
    } else if (type === 'Internet Access') {
        reqFields = `
            ${field('Full Name', requesterName)}
            ${field('Roll No.', r.roll_no)}
            ${field('Email', requesterEmail)}
            ${field('Phone', r.phone)}
            ${field('Device Type', r.device_type)}
            ${field('Operating System', r.operating_system)}
            ${field('MAC Address', r.mac_address)}
            ${field('Connection Duration', r.connection_duration)}`;
    } else if (type === 'VM Request') {
        reqFields = `
            ${field('Owner Name', r.owner_name)}
            ${field('Institute Email', r.institute_email)}
            ${field('Department', r.department_name)}
            ${field('Mobile Number', r.mobile_number)}
            ${field('Employee Category', r.employee_category)}
            ${field('VM Expiry Date', fmtDateOnly(r.vm_expiry_date))}`;
    } else if (type === 'Web Hosting') {
        reqFields = `
            ${field('Owner Name', r.owner_name)}
            ${field('Institute Email', r.institute_email)}
            ${field('Department', r.department_name)}
            ${field('Mobile Number', r.mobile_number)}
            ${field('Employee Category', r.employee_category)}
            ${field('Website Name', r.website_name)}
            ${field('Suggested Domain', r.suggested_domain_name)}
            ${field('Operating System', r.operating_system)}`;
    }

    sections.push(`<div>
        <p class="rdm-section-title">👤 Requester Information</p>
        <div class="rdm-grid">${reqFields}</div>
    </div>`);

    // ── Section: Service / Technical Details ─────────────────────────
    let techFields = '';
    if (type === 'VPN') {
        techFields = `
            <div class="col-span-2">
                ${field('Purpose / Usage', r.purpose)}
            </div>
            <div class="col-span-2">
                ${field('Resources / Access Needed', r.resources)}
            </div>`;
    } else if (type === 'Internet Access') {
        // Already covered above; no extra tech section needed
        techFields = '';
    } else if (type === 'VM Request') {
        techFields = `
            ${field('Operating System', r.operating_system)}
            ${field('OS Type', r.os_type)}
            ${field('CPU Cores', r.cpu_cores)}
            ${field('RAM (GB)', r.ram_gb)}
            ${field('Hard Disk (GB)', r.hard_disk_gb)}
            ${field('License Type', r.license_type)}
            ${field('Sub-domain', r.sub_domain || '—')}
            ${field('SSL Configuration', r.ssl_configuration)}
            <div class="col-span-2">${field('Purpose / Usage', r.purpose_usage)}</div>
            <div class="col-span-2">${field('Justification', r.justification)}</div>
            ${r.software_list ? `<div class="col-span-2">${field('Software List', r.software_list)}</div>` : ''}`;
    } else if (type === 'Web Hosting') {
        techFields = `
            <div class="col-span-2">${field('Purpose', r.purpose)}</div>
            ${r.comment ? `<div class="col-span-2">${field('Additional Comments', r.comment)}</div>` : ''}`;
    }

    if (techFields) {
        sections.push(`<div>
            <p class="rdm-section-title">⚙️ Service / Technical Details</p>
            <div class="rdm-grid">${techFields}</div>
        </div>`);
    }

    // ── Section: Designated Recommender (filled at form submission) ──────
    if (r.approver_email) {
        sections.push(`<div>
            <p class="rdm-section-title">🔖 Designated Recommender (selected by requester)</p>
            <div class="rdm-grid">
                ${field('Name', r.approver_name)}
                ${field('Email', r.approver_email)}
                ${field('Designation', r.approver_designation)}
                ${field('Department', r.approver_department)}
            </div>
        </div>`);
    }

    // ── Section: Approval Timeline ────────────────────────────────────
    const stages = ['Recommender', 'Dean IT', 'CITC'];
    const doneCount = { pending: 0, approved_by_1: 1, approved_by_2: 2, completed: 3 }[status] ?? (status === 'rejected' ? -1 : 0);

    let trackHtml = '';
    if (status !== 'rejected') {
        let dots = '';
        stages.forEach((stage, i) => {
            const isDone   = i < doneCount;
            const isActive = (i === doneCount && doneCount < 3);
            const dotCls   = isDone ? 'rdm-dot-done' : (isActive ? 'rdm-dot-active' : 'rdm-dot-future');
            const lineCls  = isDone ? 'rdm-line-done' : 'rdm-line-future';
            const isLast   = i === stages.length - 1;
            dots += `<div class="rdm-track-step">
                        <div class="rdm-track-dot ${dotCls}"></div>
                        <span style="font-size:10px;color:#9ca3af;text-align:center;">${stage}</span>
                     </div>`;
            if (!isLast) dots += `<div class="rdm-track-line ${lineCls}"></div>`;
        });
        trackHtml = `<div class="rdm-approval-track mb-4">${dots}</div>`;
    }

    let timelineFields = '';
    if (r.approver1_email) {
        timelineFields += `${field('Recommended By', `${r.approver1_name || ''} (${r.approver1_email})`)}
                           ${field('Recommended At', fmtDate(r.approved_by_1_at))}`;
    }
    if (r.approver2_email) {
        timelineFields += `${field('Approved By', `${r.approver2_name || ''} (${r.approver2_email})`)}
                           ${field('Approved At', fmtDate(r.approved_by_2_at))}`;
    }
    if (r.citc_completed_by) {
        timelineFields += `${field('Completed By (CITC)', r.citc_completed_by)}
                           ${field('Completed At', fmtDate(r.citc_completed_at))}`;
    }
    if (status === 'rejected') {
        timelineFields += `<div class="col-span-2" style="background:#fff1f2;border:1px solid #fecdd3;border-radius:12px;padding:12px;">
            <p class="rdm-field-label" style="color:#f43f5e;">Rejection Reason</p>
            <p class="rdm-field-value" style="color:#be123c;">${val(r.rejection_reason)}</p>
            <p style="font-size:11px;color:#fb7185;margin-top:4px;">Rejected by Level ${r.rejected_by_level || '?'} · ${fmtDate(r.rejected_at)}</p>
        </div>`;
    }

    sections.push(`<div>
        <p class="rdm-section-title">✅ Approval Timeline</p>
        ${trackHtml}
        <div class="rdm-grid">${timelineFields || field('No approval actions yet', '')}</div>
    </div>`);

    // ── Footer: Meta ──────────────────────────────────────────────────
    sections.push(`<div style="background:#f9fafb;border-radius:12px;padding:12px 14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
        <span style="font-size:11px;color:#9ca3af;">Request #${r.id} · Submitted ${fmtDate(r.created_at)}</span>
        <span class="rdm-badge rdm-status-${status}">${STATUS_LABELS[status] || status}</span>
    </div>`);

    return sections.join('');
}

function openDetail(r) {
    const modal  = document.getElementById('reqDetailModal');
    const header = document.getElementById('rdm-header');
    const title  = document.getElementById('rdm-title');
    const sub    = document.getElementById('rdm-subtitle');
    const icon   = document.getElementById('rdm-icon');
    const body   = document.getElementById('rdm-body');

    const meta = TYPE_META[r._type] || { cls:'', iconColor:'#374151', bg:'#f3f4f6' };

    icon.style.background = meta.bg;
    icon.querySelector('svg').style.color = meta.iconColor;
    title.textContent  = r._type + ' Request';
    sub.textContent    = (r.name || r.owner_name || '') + ' · #' + r.id;

    body.innerHTML = buildBody(r);
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeDetail() {
    document.getElementById('reqDetailModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });
</script>
