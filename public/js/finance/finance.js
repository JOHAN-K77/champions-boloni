// ============================================================
// finance.js — Champions Boloni School Admin
//
// Sections:
//   A. Create page — header payment-method tab switching
//   B. Create page — Paid Off checkbox (Invoice richbox)
//   C. Create page — Used checkbox (Discount richbox)
//   D. Create page — Deposit "All" checkbox sync
//   E. Create page — Live system date clock
//   F. Index page  — Tab strip, row select, sort, filter,
//                    preview modal
// ============================================================
 
 
// ============================================================
// A. CREATE PAGE — Payment method tab switching
//    Driven by header tabs (.tab-page) → shows #tab-{method}
// ============================================================
document.querySelectorAll('.tab-page').forEach(function (tab) {
    tab.addEventListener('click', function (e) {
        e.preventDefault();
        var segmentId = this.id.split('_')[1]; // e.g. 'cash', 'transfer'
 
        document.querySelectorAll('.tab-content-area').forEach(function (el) {
            el.style.display = 'none';
        });
        document.querySelectorAll('.tab-page').forEach(function (el) {
            el.classList.remove('active');
        });
 
        tab.classList.add('active');
 
        var target = document.getElementById('tab-' + segmentId);
        if (target) target.style.display = 'block';
    });
});
 
 
// ============================================================
// B. CREATE PAGE — Invoice richbox "Paid Off" checkbox
//    Checked  → dim row, disable pay input
//    Unchecked → restore row and input
// ============================================================
document.querySelectorAll('.paid-check').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        var row      = this.closest('tr');
        var payInput = row ? row.querySelector('.pay-input') : null;
 
        if (this.checked) {
            if (row) row.classList.add('richbox-row-muted');
            if (payInput) { payInput.disabled = true; payInput.placeholder = '–'; }
        } else {
            if (row) row.classList.remove('richbox-row-muted');
            if (payInput) { payInput.disabled = false; payInput.placeholder = '0'; }
        }
    });
});
 
 
// ============================================================
// C. CREATE PAGE — Discount richbox "Used" checkbox
//    Checked  → dim row
//    Unchecked → restore row
// ============================================================
document.querySelectorAll('.disc-check').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        var row = this.closest('tr');
        if (!row) return;
        row.classList.toggle('richbox-row-muted', this.checked);
    });
 
    // Apply initial dimming on page load
    if (checkbox.checked && !checkbox.disabled) {
        var row = checkbox.closest('tr');
        if (row) row.classList.add('richbox-row-muted');
    }
});
 
 
// ============================================================
// D. CREATE PAGE — Deposit richbox "All" checkbox sync
//    "All" checks → checks Used + Paid for that row
//    Used or Paid individually → syncs "All" state
// ============================================================
document.querySelectorAll('#depositTable tbody tr').forEach(function (row) {
    var usedCheck = row.querySelector('.dep-used-check');
    var paidCheck = row.querySelector('.dep-paid-check');
    var allCheck  = row.querySelector('.dep-all-check');
 
    if (!allCheck) return; // rows without the three checkboxes (e.g. empty-state row)
 
    allCheck.addEventListener('change', function () {
        if (usedCheck) usedCheck.checked = this.checked;
        if (paidCheck) paidCheck.checked = this.checked;
    });
 
    function syncAll() {
        if (allCheck && usedCheck && paidCheck) {
            allCheck.checked = usedCheck.checked && paidCheck.checked;
        }
    }
 
    if (usedCheck) usedCheck.addEventListener('change', syncAll);
    if (paidCheck) paidCheck.addEventListener('change', syncAll);
});
 
 
// ============================================================
// E. CREATE PAGE — Live system date (ticks every second)
// ============================================================
function updateSystemDate() {
    var el = document.getElementById('date_sis');
    if (!el) return;
    var now = new Date();
    var pad = function (n) { return String(n).padStart(2, '0'); };
    el.value =
        now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate()) +
        ' ' + pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
}
updateSystemDate();
setInterval(updateSystemDate, 1000);
 
 
// ============================================================
// F. INDEX PAGE — Tab strip, row selection, sort, filter,
//                 preview modal
//    Wrapped in IIFE; exits early if #financeTabs not found
//    (i.e. on create page, this whole block does nothing)
// ============================================================
(function () {
    if (!document.getElementById('financeTabs')) return;
 
    // ── Tab configuration ────────────────────────────────────
    var TABS = {
        quotation: {
            title:      'Quotation',
            subtitle:   'Subject Fee records',
            label:      'QUOTATION RECORD',
            totalField: null
        },
        invoice: {
            title:      'Invoice',
            subtitle:   'All invoice records',
            label:      'PAYMENT RECEIPT',
            totalField: 'total'
        },
        payment: {
            title:      'Payment',
            subtitle:   'All payment transactions',
            label:      'PAYMENT RECEIPT',
            totalField: 'cash'
        }
    };
 
    var PREVIEW_FIELDS = {
        quotation: [
            ['Quotation ID',    'id'],
            ['Quotation Date',  'date'],
            ['Student ID',      'student'],
            ['Student Name',    'name'],
            ['Instalment',      'instalment'],
            ['Instalment Date', 'idate']
        ],
        invoice: [
            ['Invoice ID',      'id'],
            ['Invoice Date',    'date'],
            ['Quotation ID',    'quotation'],
            ['Instalment',      'instalment'],
            ['Quotation Date',  'qdate'],
            ['Paid Status',     'status']
        ],
        payment: [
            ['Invoice ID',      'id'],
            ['Payment Method',  'method'],
            ['Deposit ID',      'deposit'],
            ['Card',            'card'],
            ['Cek/Giro ID',     'cek'],
            ['Amount (Cash)',   'cash'],
            ['Amount (Card)',   'cardamt']
        ]
    };
 
    var activeTab    = null;
    var selectedRows = {}; // { tabName: <tr> | null }
 
    // ── Helpers ──────────────────────────────────────────────
    function getRows(tab) {
        return Array.from(document.querySelectorAll('#panel-' + tab + ' .fin-row'));
    }
 
    function setButtons(tab, enabled) {
        document.querySelectorAll('#actions-' + tab + ' .inv-action-btn').forEach(function (b) {
            b.disabled = !enabled;
        });
    }
 
    // ── Row selection ────────────────────────────────────────
    function selectRow(tab, row) {
        var prev     = selectedRows[tab];
        var labelEl  = document.querySelector('#panel-' + tab + ' .tab-selected-label');
        var idEl     = document.querySelector('#panel-' + tab + ' .tab-selected-id');
 
        if (prev) prev.classList.remove('row-selected');
 
        if (prev === row) {
            // clicking same row → deselect
            selectedRows[tab] = null;
            if (labelEl) labelEl.style.opacity = '0';
            setButtons(tab, false);
            return;
        }
 
        selectedRows[tab] = row;
        row.classList.add('row-selected');
        if (idEl)    idEl.textContent      = row.dataset.id || '';
        if (labelEl) labelEl.style.opacity = '1';
        setButtons(tab, true);
    }
 
    function bindRowClicks(tab) {
        getRows(tab).forEach(function (row) {
            row.addEventListener('click', function () {
                selectRow(tab, row);
            });
            row.addEventListener('dblclick', function () {
                selectRow(tab, row);
                openPreview(tab, row);
            });
        });
    }
 
    // ── Filter ───────────────────────────────────────────────
    function applyFilter(tab) {
        var q        = (document.getElementById('searchInput') || {}).value || '';
        var from     = (document.getElementById('filterFrom')  || {}).value || '';
        var to       = (document.getElementById('filterTo')    || {}).value || '';
        q = q.toLowerCase();
 
        var emptyRow = document.getElementById(tab + 'Empty');
        var countEl  = document.querySelector('#panel-' + tab + ' .tab-row-count');
        var infoEl   = document.querySelector('#panel-' + tab + ' .tab-page-info');
        var visible  = 0;
 
        getRows(tab).forEach(function (row) {
            var date = row.dataset.date || '';
            var text = row.textContent.toLowerCase();
            var show = (!q    || text.includes(q))
                    && (!from || date >= from)
                    && (!to   || date <= to);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
 
        // Re-number visible rows
        var n = 1;
        getRows(tab).forEach(function (row) {
            if (row.style.display !== 'none') {
                var num = row.querySelector('.row-num');
                if (num) num.textContent = n++;
            }
        });
 
        if (emptyRow) emptyRow.classList.toggle('d-none', visible > 0);
        if (countEl)  countEl.textContent = visible;
        if (infoEl)   infoEl.textContent  =
            'Showing ' + visible + ' record' + (visible !== 1 ? 's' : '');
    }
 
    // ── Sort ─────────────────────────────────────────────────
    function bindSort(tab) {
        var sortCol = -1, sortDir = 1;
        var table   = document.getElementById(tab + 'Table');
        if (!table) return;
 
        table.querySelectorAll('th.sortable').forEach(function (th) {
            th.addEventListener('click', function () {
                var col   = parseInt(th.dataset.col, 10);
                var tbody = table.querySelector('tbody');
                var emptyRow = document.getElementById(tab + 'Empty');
 
                table.querySelectorAll('th.sortable')
                     .forEach(function (h) { h.classList.remove('sort-asc', 'sort-desc'); });
 
                sortDir = (sortCol === col) ? sortDir * -1 : 1;
                sortCol = col;
                th.classList.add(sortDir === 1 ? 'sort-asc' : 'sort-desc');
 
                var rows = getRows(tab);
                rows.sort(function (a, b) {
                    var aT = (a.querySelectorAll('td')[col + 1] || {}).textContent || '';
                    var bT = (b.querySelectorAll('td')[col + 1] || {}).textContent || '';
                    return aT.trim().localeCompare(bT.trim(), undefined, { numeric: true }) * sortDir;
                });
                rows.forEach(function (r) { tbody.appendChild(r); });
                if (emptyRow) tbody.appendChild(emptyRow);
                applyFilter(tab);
            });
        });
    }
 
    // ── Action buttons ───────────────────────────────────────
    function bindActions(tab) {
        document.querySelectorAll('#actions-' + tab + ' .inv-action-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var row    = selectedRows[tab];
                if (!row) return;
                var action = btn.dataset.action;
                var id     = row.dataset.id;
 
                if (action === 'preview') {
                    openPreview(tab, row);
                } else if (action === 'edit') {
                    window.location.href = '/finance/' + tab + '/' + id + '/edit';
                } else if (action === 'cancel' || action === 'void') {
                    if (confirm('Cancel/void ' + id + '?\nThis action cannot be undone.')) {
                        // TODO: POST /finance/{tab}/{id}/cancel
                    }
                } else if (action === 'print') {
                    openPreview(tab, row); // open preview first; print from modal
                }
            });
        });
    }
 
    // ── Preview modal ────────────────────────────────────────
    function openPreview(tab, row) {
        var cfg    = TABS[tab];
        var fields = PREVIEW_FIELDS[tab];
        var d      = row.dataset;
 
        var titleEl    = document.getElementById('previewModalTitle');
        var subtitleEl = document.getElementById('previewModalSubtitle');
        var labelEl    = document.getElementById('receiptTypeLabel');
        var totalEl    = document.getElementById('prev_total');
        var container  = document.getElementById('receiptFields');
 
        if (titleEl)    titleEl.textContent    = cfg.label;
        if (subtitleEl) subtitleEl.textContent = d.id || '—';
        if (labelEl)    labelEl.textContent    = cfg.label;
 
        // Build two-column field grid
        var html = '<div class="row g-0">';
        fields.forEach(function (pair, i) {
            var label = pair[0];
            var key   = pair[1];
            var val   = d[key] || '—';
 
            if (key === 'status') {
                var cls = (val === 'Paid') ? 'status-paid' : 'status-unpaid';
                val = '<span class="status-badge ' + cls + '">' + val + '</span>';
            }
 
            if (i % 2 === 0) html += '<div class="col-6"><table style="width:100%;border:none;">';
            html += '<tr>' +
                '<td style="width:115px;color:#555;padding:2px 0;">' + label + '</td>' +
                '<td style="color:#111;">: ' + val + '</td>' +
                '</tr>';
            if (i % 2 === 1 || i === fields.length - 1) html += '</table></div>';
        });
        html += '</div>';
        if (container) container.innerHTML = html;
 
        if (totalEl) {
            totalEl.textContent = (cfg.totalField && d[cfg.totalField])
                ? 'Rp ' + d[cfg.totalField]
                : '—';
        }
 
        var modalEl = document.getElementById('previewModal');
        if (modalEl) new bootstrap.Modal(modalEl).show();
    }
 
    var btnPrintModal = document.getElementById('btnPrintFromModal');
    if (btnPrintModal) {
        btnPrintModal.addEventListener('click', function () { window.print(); });
    }
 
    // ── Tab switching ────────────────────────────────────────
    function activateTab(tab) {
        activeTab = tab;
        var cfg   = TABS[tab];
 
        // Tab buttons
        document.querySelectorAll('.fin-tab').forEach(function (btn) {
            btn.classList.toggle('active', btn.dataset.tab === tab);
        });
 
        // Panels
        document.querySelectorAll('.fin-tab-panel').forEach(function (p) {
            p.style.display = 'none';
        });
        var panel = document.getElementById('panel-' + tab);
        if (panel) panel.style.display = 'block';
 
        // Top-right buttons
        document.querySelectorAll('.tab-topbar').forEach(function (b) {
            b.style.display = 'none';
        });
        var topbar = document.getElementById('topbar-' + tab);
        if (topbar) topbar.style.display = '';
 
        // Bottom action buttons
        document.querySelectorAll('.tab-actions').forEach(function (a) {
            a.style.setProperty('display', 'none', 'important');
        });
        var actions = document.getElementById('actions-' + tab);
        if (actions) actions.style.removeProperty('display');
 
        // Page title/subtitle
        var titleEl    = document.getElementById('pageTitle');
        var subtitleEl = document.getElementById('pageSubtitle');
        if (titleEl)    titleEl.textContent    = cfg.title;
        if (subtitleEl) subtitleEl.textContent = cfg.subtitle;
 
        // Restore button state
        setButtons(tab, !!selectedRows[tab]);
 
        // Refresh filter counts
        applyFilter(tab);
    }
 
    // ── Init ─────────────────────────────────────────────────
    Object.keys(TABS).forEach(function (tab) {
        bindRowClicks(tab);
        bindSort(tab);
        bindActions(tab);
    });
 
    document.querySelectorAll('.fin-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            activateTab(btn.dataset.tab);
        });
    });
 
    var searchInput = document.getElementById('searchInput');
    var filterFrom  = document.getElementById('filterFrom');
    var filterTo    = document.getElementById('filterTo');
    var clearBtn    = document.getElementById('clearFilter');
 
    if (searchInput) searchInput.addEventListener('input',  function () { if (activeTab) applyFilter(activeTab); });
    if (filterFrom)  filterFrom.addEventListener('change',  function () { if (activeTab) applyFilter(activeTab); });
    if (filterTo)    filterTo.addEventListener('change',    function () { if (activeTab) applyFilter(activeTab); });
    if (clearBtn)    clearBtn.addEventListener('click', function () {
        if (searchInput) searchInput.value = '';
        if (filterFrom)  filterFrom.value  = '';
        if (filterTo)    filterTo.value    = '';
        if (activeTab)   applyFilter(activeTab);
    });
 
    // Open Quotation tab by default
    activateTab('quotation');
 
})();