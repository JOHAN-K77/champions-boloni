// ============================================================
// Header tab switching (Cash / Transfer / Card / Giro / Deposit)
// ============================================================
document.querySelectorAll('.tab-page').forEach((tab) => {
    tab.addEventListener('click', (e) => {
        e.preventDefault();
        const tabId = tab.id;
        const segmentId = tabId.split('_')[1]; // e.g. 'cash', 'transfer'
 
        document.querySelectorAll('.tab-content-area').forEach((content) => {
            content.style.display = 'none';
        });
 
        document.querySelectorAll('.tab-page').forEach((item) => {
            item.classList.remove('active');
        });
 
        tab.classList.add('active');
 
        const contentToShow = document.getElementById(`tab-${segmentId}`);
        if (contentToShow) {
            contentToShow.style.display = 'block';
        }
    });
});

// ============================================================
// FINANCE INDEX — Tab switching, row selection, search,
//                 sort, filter, preview modal
// ============================================================
(function () {
    if (!document.getElementById('financeTabs')) return;
 
    // ── tab config ───────────────────────────────────────────
    const TABS = {
        quotation: {
            title:    'Quotation',
            subtitle: 'Subject Fee records',
            label:    'QUOTATION RECORD',
            totalField: null,
        },
        invoice: {
            title:    'Invoice',
            subtitle: 'All invoice records',
            label:    'PAYMENT RECEIPT',
            totalField: 'total',
        },
        payment: {
            title:    'Payment',
            subtitle: 'All payment transactions',
            label:    'PAYMENT RECEIPT',
            totalField: 'cash',
        },
    };
 
    // Preview field definitions per tab
    const PREVIEW_FIELDS = {
        quotation: [
            ['Quotation ID',    'id'],
            ['Quotation Date',  'date'],
            ['Student ID',      'student'],
            ['Student Name',    'name'],
            ['Instalment',      'instalment'],
            ['Instalment Date', 'idate'],
        ],
        invoice: [
            ['Invoice ID',      'id'],
            ['Invoice Date',    'date'],
            ['Quotation ID',    'quotation'],
            ['Instalment',      'instalment'],
            ['Quotation Date',  'qdate'],
            ['Paid Status',     'status'],
        ],
        payment: [
            ['Invoice ID',      'id'],
            ['Payment Method',  'method'],
            ['Deposit ID',      'deposit'],
            ['Card',            'card'],
            ['Cek/Giro ID',     'cek'],
            ['Amount (Cash)',   'cash'],
            ['Amount (Card)',   'cardamt'],
        ],
    };
 
    let activeTab     = null;
    let selectedRows  = {};   // { tabName: <tr element> }
 
    // ── helpers ──────────────────────────────────────────────
    function getRows(tab) {
        return Array.from(
            document.querySelectorAll(`#panel-${tab} .fin-row`)
        );
    }
 
    function getActionBtns(tab) {
        return document.querySelectorAll(`#actions-${tab} .inv-action-btn`);
    }
 
    function setButtons(tab, enabled) {
        getActionBtns(tab).forEach(b => b.disabled = !enabled);
    }
 
    // ── row selection ────────────────────────────────────────
    function selectRow(tab, row) {
        const prev = selectedRows[tab];
        if (prev) prev.classList.remove('row-selected');
 
        const labelEl = document.querySelector(`#panel-${tab} .tab-selected-label`);
        const idEl    = document.querySelector(`#panel-${tab} .tab-selected-id`);
 
        if (prev === row) {
            // deselect
            selectedRows[tab] = null;
            if (labelEl) labelEl.style.opacity = '0';
            setButtons(tab, false);
            return;
        }
 
        selectedRows[tab] = row;
        row.classList.add('row-selected');
        if (idEl)    idEl.textContent     = row.dataset.id;
        if (labelEl) labelEl.style.opacity = '1';
        setButtons(tab, true);
    }
 
    function bindRowClicks(tab) {
        getRows(tab).forEach(row => {
            row.addEventListener('click', () => selectRow(tab, row));
            row.addEventListener('dblclick', () => {
                selectRow(tab, row);
                openPreview(tab, row);
            });
        });
    }
 
    // ── filter ───────────────────────────────────────────────
    function applyFilter(tab) {
        const q    = (document.getElementById('searchInput')?.value || '').toLowerCase();
        const from = document.getElementById('filterFrom')?.value || '';
        const to   = document.getElementById('filterTo')?.value   || '';
 
        const emptyRow = document.getElementById(`${tab}Empty`);
        const countEl  = document.querySelector(`#panel-${tab} .tab-row-count`);
        const infoEl   = document.querySelector(`#panel-${tab} .tab-page-info`);
 
        let visible = 0;
        getRows(tab).forEach(row => {
            const date = row.dataset.date || '';
            const text = row.textContent.toLowerCase();
            const show = (!q    || text.includes(q))
                      && (!from || date >= from)
                      && (!to   || date <= to);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
 
        // re-number
        let n = 1;
        getRows(tab).forEach(row => {
            if (row.style.display !== 'none') {
                const num = row.querySelector('.row-num');
                if (num) num.textContent = n++;
            }
        });
 
        if (emptyRow) emptyRow.classList.toggle('d-none', visible > 0);
        if (countEl)  countEl.textContent = visible;
        if (infoEl)   infoEl.textContent  =
            `Showing ${visible} record${visible !== 1 ? 's' : ''}`;
    }
 
    // ── sort ─────────────────────────────────────────────────
    function bindSort(tab) {
        let sortCol = -1, sortDir = 1;
        const table = document.getElementById(`${tab}Table`);
        if (!table) return;
 
        table.querySelectorAll('th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const col = parseInt(th.dataset.col);
                table.querySelectorAll('th.sortable')
                     .forEach(h => h.classList.remove('sort-asc','sort-desc'));
                sortDir = (sortCol === col) ? sortDir * -1 : 1;
                sortCol = col;
                th.classList.add(sortDir === 1 ? 'sort-asc' : 'sort-desc');
 
                const tbody = table.querySelector('tbody');
                const emptyRow = document.getElementById(`${tab}Empty`);
                const rows = getRows(tab);
                rows.sort((a, b) => {
                    const aT = a.querySelectorAll('td')[col + 1]?.textContent.trim() || '';
                    const bT = b.querySelectorAll('td')[col + 1]?.textContent.trim() || '';
                    return aT.localeCompare(bT, undefined, { numeric: true }) * sortDir;
                });
                rows.forEach(r => tbody.appendChild(r));
                if (emptyRow) tbody.appendChild(emptyRow);
                applyFilter(tab);
            });
        });
    }
 
    // ── action buttons ───────────────────────────────────────
    function bindActions(tab) {
        document.querySelectorAll(`#actions-${tab} .inv-action-btn`).forEach(btn => {
            btn.addEventListener('click', () => {
                const row = selectedRows[tab];
                if (!row) return;
                const action = btn.dataset.action;
                const id = row.dataset.id;
 
                if (action === 'preview') {
                    openPreview(tab, row);
                } else if (action === 'edit') {
                    window.location.href = `/finance/${tab}/${id}/edit`;
                } else if (action === 'cancel' || action === 'void') {
                    if (confirm(`Cancel/void ${id}?\nThis action cannot be undone.`)) {
                        // TODO: POST /finance/{tab}/{id}/cancel
                    }
                } else if (action === 'print') {
                    openPreview(tab, row);   // preview then print from modal
                }
            });
        });
    }
 
    // ── preview modal ────────────────────────────────────────
    function openPreview(tab, row) {
        const cfg    = TABS[tab];
        const fields = PREVIEW_FIELDS[tab];
        const d      = row.dataset;
 
        document.getElementById('previewModalTitle').textContent    = cfg.label;
        document.getElementById('previewModalSubtitle').textContent = d.id || '—';
        document.getElementById('receiptTypeLabel').textContent     = cfg.label;
 
        // Build field grid
        const container = document.getElementById('receiptFields');
        let html = '<div class="row g-0">';
        fields.forEach(([label, key], i) => {
            let val = d[key] || '—';
            // Special rendering
            if (key === 'status') {
                const cls = val === 'Paid' ? 'status-paid' : 'status-unpaid';
                val = `<span class="status-badge ${cls}">${val}</span>`;
            }
            if (i % 2 === 0) html += '<div class="col-6"><table style="width:100%;border:none;">';
            html += `<tr>
                <td style="width:115px;color:#555;padding:2px 0;">${label}</td>
                <td style="color:#111;">: ${val}</td>
            </tr>`;
            if (i % 2 === 1 || i === fields.length - 1) html += '</table></div>';
        });
        html += '</div>';
        container.innerHTML = html;
 
        // Total row
        const totalEl = document.getElementById('prev_total');
        if (cfg.totalField && d[cfg.totalField]) {
            totalEl.textContent = 'Rp ' + d[cfg.totalField];
        } else {
            totalEl.textContent = '—';
        }
 
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }
 
    document.getElementById('btnPrintFromModal')?.addEventListener('click', () => window.print());
 
    // ── tab switching ────────────────────────────────────────
    function activateTab(tab) {
        activeTab = tab;
        const cfg = TABS[tab];
 
        // Tabs
        document.querySelectorAll('.fin-tab').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tab);
        });
 
        // Panels
        document.querySelectorAll('.fin-tab-panel').forEach(p => p.style.display = 'none');
        document.getElementById(`panel-${tab}`).style.display = 'block';
 
        // Top buttons
        document.querySelectorAll('.tab-topbar').forEach(b => b.style.display = 'none');
        document.getElementById(`topbar-${tab}`).style.display = '';
 
        // Bottom action buttons
        document.querySelectorAll('.tab-actions').forEach(a => a.style.setProperty('display','none','important'));
        document.getElementById(`actions-${tab}`).style.removeProperty('display');
 
        // Page title
        document.getElementById('pageTitle').textContent    = cfg.title;
        document.getElementById('pageSubtitle').textContent = cfg.subtitle;
 
        // Restore button state for this tab
        setButtons(tab, !!selectedRows[tab]);
 
        // Run filter with current search values
        applyFilter(tab);
    }
 
    // ── init ─────────────────────────────────────────────────
    Object.keys(TABS).forEach(tab => {
        bindRowClicks(tab);
        bindSort(tab);
        bindActions(tab);
    });
 
    document.querySelectorAll('.fin-tab').forEach(btn => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });
 
    // Search & filter listeners
    const searchInput = document.getElementById('searchInput');
    const filterFrom  = document.getElementById('filterFrom');
    const filterTo    = document.getElementById('filterTo');
    const clearBtn    = document.getElementById('clearFilter');
 
    searchInput?.addEventListener('input',  () => { if (activeTab) applyFilter(activeTab); });
    filterFrom ?.addEventListener('change', () => { if (activeTab) applyFilter(activeTab); });
    filterTo   ?.addEventListener('change', () => { if (activeTab) applyFilter(activeTab); });
    clearBtn   ?.addEventListener('click',  () => {
        if (searchInput) searchInput.value = '';
        if (filterFrom)  filterFrom.value  = '';
        if (filterTo)    filterTo.value    = '';
        if (activeTab)   applyFilter(activeTab);
    });
 
    // Default: open Quotation tab
    activateTab('quotation');
 
})();

    if (tbody) {
        tbody.addEventListener('click', (event) => {
            const row = event.target.closest('tr.inv-row');
            if (row) selectRow(row);
        });

        tbody.addEventListener('dblclick', (event) => {
            const row = event.target.closest('tr.inv-row');
            if (row) {
                selectRow(row);
                openPreview(row);
            }
        });
    }

    if (btnPreview) {
        btnPreview.addEventListener('click', () => {
            const targetRow = selectedRow || dataRows().find(row => row.style.display !== 'none');
            if (targetRow) openPreview(targetRow);
        });
    }

    if (btnPrintModal) {
        btnPrintModal.addEventListener('click', () => {
            window.print();
        });
    }

    if (btnEdit) btnEdit.addEventListener('click', () => {
        if (selectedRow) window.location.href = `/finance/${selectedRow.dataset.id}/edit`;
    });

    if (btnPrint) btnPrint.addEventListener('click', () => {
        const targetRow = selectedRow || dataRows().find(row => row.style.display !== 'none');
        if (targetRow) openPreview(targetRow);
    });

    if (btnCancel) btnCancel.addEventListener('click', () => {
        if (!selectedRow) return;
        if (confirm(`Cancel invoice ${selectedRow.dataset.id}?\nThis action cannot be undone.`)) {
            // TODO: POST /finance/{id}/cancel
        }
    });

    function applyFilter() {
        const q    = (searchInput?.value || '').toLowerCase();
        const from = filterFrom?.value || '';
        const to   = filterTo?.value   || '';
        let visible = 0;

        dataRows().forEach(row => {
            const invDate = row.dataset.date || '';
            const text    = row.textContent.toLowerCase();

            const show = (!q    || text.includes(q))
                      && (!from || invDate >= from)
                      && (!to   || invDate <= to);

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        let n = 1;
        dataRows().forEach(row => {
            if (row.style.display !== 'none') {
                const numCell = row.querySelector('.row-num');
                if (numCell) numCell.textContent = n++;
            }
        });

        if (emptyRow) emptyRow.classList.toggle('d-none', visible > 0);
        if (rowCountEl) rowCountEl.textContent = visible;
        if (pageInfo) pageInfo.textContent = `Showing ${visible} record${visible !== 1 ? 's' : ''}`;
    }

    table.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            table.querySelectorAll('th.sortable').forEach(h => h.classList.remove('sort-asc','sort-desc'));
            sortDir = (sortCol === col) ? sortDir * -1 : 1;
            sortCol = col;
            th.classList.add(sortDir === 1 ? 'sort-asc' : 'sort-desc');

            const rows = dataRows();
            rows.sort((a, b) => {
                const aT = a.querySelectorAll('td')[col + 1]?.textContent.trim() || '';
                const bT = b.querySelectorAll('td')[col + 1]?.textContent.trim() || '';
                return aT.localeCompare(bT, undefined, { numeric: true }) * sortDir;
            });
            rows.forEach(r => tbody.appendChild(r));
            if (emptyRow) tbody.appendChild(emptyRow);
            applyFilter();
        });
    });

    searchInput?.addEventListener('input', applyFilter);
    filterFrom ?.addEventListener('change', applyFilter);
    filterTo   ?.addEventListener('change', applyFilter);
    clearBtn   ?.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        if (filterFrom)  filterFrom.value  = '';
        if (filterTo)    filterTo.value    = '';
        applyFilter();
    });

    applyFilter();
})();
 
 
// ============================================================
// RICHBOX 1: Invoice table — "Paid Off" checkbox behaviour
// When checked  → disable the Pay input, dim the row
// When unchecked → re-enable the Pay input, restore row
// ============================================================
document.querySelectorAll('.paid-check').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
        const row = this.closest('tr');
        const payInput = row.querySelector('.pay-input');
 
        if (this.checked) {
            row.classList.add('richbox-row-muted');
            if (payInput) {
                payInput.disabled = true;
                payInput.placeholder = '–';
            }
        } else {
            row.classList.remove('richbox-row-muted');
            if (payInput) {
                payInput.disabled = false;
                payInput.placeholder = '0';
            }
        }
    });
});
 
 
// ============================================================
// RICHBOX 2: Discount table — "Used" checkbox behaviour
// When checked  → dim the row to indicate discount applied
// When unchecked → restore the row
// ============================================================
document.querySelectorAll('.disc-check').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
        const row = this.closest('tr');
        if (this.checked) {
            row.classList.add('richbox-row-muted');
        } else {
            row.classList.remove('richbox-row-muted');
        }
    });
 
    // Apply initial state on page load
    if (checkbox.checked && !checkbox.disabled) {
        checkbox.closest('tr').classList.add('richbox-row-muted');
    }
});

 
// ============================================================
// Live System Date — updates every second without page reload
// ============================================================
function updateSystemDate() {
    const el = document.getElementById('date_sis');
    if (!el) return;
    const now = new Date();
    const pad = (n) => String(n).padStart(2, '0');
    const formatted =
        `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ` +
        `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    el.value = formatted;
}
 
updateSystemDate();
setInterval(updateSystemDate, 1000);
