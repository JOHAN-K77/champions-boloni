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
// FINANCE INDEX — Search, Date Filter, Sort, Row Select
// ============================================================
 
(function () {
    const table       = document.getElementById('invoiceTable');
    if (!table) return;
 
    const tbody       = document.getElementById('invoiceBody');
    const searchInput = document.getElementById('searchInput');
    const filterFrom  = document.getElementById('filterFrom');
    const filterTo    = document.getElementById('filterTo');
    const clearBtn    = document.getElementById('clearFilter');
    const rowCountEl  = document.getElementById('rowCount');
    const emptyRow    = document.getElementById('emptyRow');
    const pageInfo    = document.getElementById('paginationInfo');
    const selectedLabel = document.getElementById('selectedLabel');
    const selectedIdEl  = document.getElementById('selectedId');
 
    const btnPreview  = document.getElementById('btnPreview');
    const btnEdit     = document.getElementById('btnEditInvoice');
    const btnCancel   = document.getElementById('btnCancel');
    const btnPrint    = document.getElementById('btnPrintInvoice');
    const btnPrintModal = document.getElementById('btnPrintFromModal');
    const previewModal = document.getElementById('previewModal');
 
    let sortCol = -1, sortDir = 1;
    let selectedRow = null;
 
    function dataRows() {
        return Array.from(tbody?.querySelectorAll('tr.inv-row') || []);
    }
 
    function setButtons(enabled) {
        [btnPreview, btnEdit, btnCancel, btnPrint].forEach(b => {
            if (b) b.disabled = !enabled;
        });
    }
 
    function clearSelection() {
        if (selectedRow) {
            selectedRow.classList.remove('row-selected');
        }
        selectedRow = null;
        if (selectedLabel) selectedLabel.style.opacity = '0';
        setButtons(false);
    }
 
    function selectRow(row) {
        if (!row) return;
 
        if (selectedRow === row) {
            clearSelection();
            return;
        }
 
        if (selectedRow) selectedRow.classList.remove('row-selected');
        selectedRow = row;
        row.classList.add('row-selected');
        if (selectedIdEl) selectedIdEl.textContent = row.dataset.id;
        if (selectedLabel) selectedLabel.style.opacity = '1';
        setButtons(true);
    }
 
    function openPreview(row) {
        if (!row || !previewModal) return;
 
        const d = row.dataset;
        const subtitle = document.getElementById('previewModalSubtitle');
        const invoiceId = document.getElementById('prev_invoiceId');
        const invoiceDate = document.getElementById('prev_invoiceDate');
        const quotationId = document.getElementById('prev_quotationId');
        const instalment = document.getElementById('prev_instalment');
        const qdate = document.getElementById('prev_qdate');
        const total = document.getElementById('prev_total');
        const statusEl = document.getElementById('prev_status');
 
        if (subtitle) subtitle.textContent = d.id || '—';
        if (invoiceId) invoiceId.textContent = d.id || '—';
        if (invoiceDate) invoiceDate.textContent = d.date || '—';
        if (quotationId) quotationId.textContent = d.quotation || '—';
        if (instalment) instalment.textContent = d.instalment || '—';
        if (qdate) qdate.textContent = d.qdate || '—';
        if (total) total.textContent = 'Rp ' + (d.total || '—');
 
        if (statusEl) {
            statusEl.textContent = d.status || '—';
            statusEl.className = 'status-badge ' +
                (d.status === 'Paid' ? 'status-paid' : 'status-unpaid');
        }
 
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = bootstrap.Modal.getOrCreateInstance(previewModal);
            modal.show();
        } else {
            previewModal.classList.add('show');
            previewModal.style.display = 'block';
            previewModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
        }
    }
 
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
 
 
// ============================================================
// FINANCE INDEX — Tab strip switching
// Appended below the original invoice IIFE.
// Uses completely separate IDs so nothing above is affected.
// ============================================================
(function () {
    var tabs = document.getElementById('financeTabs');
    if (!tabs) return;
 
    var PANELS = {
        quotation: { panel: 'panel-quotation', actions: 'actions-quotation', topbar: 'topbar-quotation', title: 'Quotation',  subtitle: 'Subject Fee records'      },
        invoice:   { panel: 'panel-invoice',   actions: 'actions-invoice',   topbar: 'topbar-invoice',   title: 'Invoice',    subtitle: 'All settled invoice records' },
        payment:   { panel: 'panel-payment',   actions: 'actions-payment',   topbar: 'topbar-payment',   title: 'Payment',    subtitle: 'All payment transactions'   }
    };
 
    function switchTab(tab) {
        var cfg = PANELS[tab];
        if (!cfg) return;
 
        // Tab buttons
        tabs.querySelectorAll('.fin-tab').forEach(function (btn) {
            btn.classList.toggle('active', btn.dataset.tab === tab);
        });
 
        // Panels
        Object.keys(PANELS).forEach(function (key) {
            var el = document.getElementById(PANELS[key].panel);
            if (el) el.style.display = (key === tab) ? '' : 'none';
        });
 
        // Action buttons
        Object.keys(PANELS).forEach(function (key) {
            var el = document.getElementById(PANELS[key].actions);
            if (!el) return;
            if (key === tab) {
                el.style.removeProperty('display');
            } else {
                el.style.setProperty('display', 'none', 'important');
            }
        });
 
        // Top-right buttons
        Object.keys(PANELS).forEach(function (key) {
            var el = document.getElementById(PANELS[key].topbar);
            if (el) el.style.display = (key === tab) ? (key === 'quotation' ? 'flex' : '') : 'none';
        });
 
        // Page title
        var titleEl    = document.getElementById('pageTitle');
        var subtitleEl = document.getElementById('pageSubtitle');
        if (titleEl)    titleEl.textContent    = cfg.title;
        if (subtitleEl) subtitleEl.textContent = cfg.subtitle;
    }
 
    tabs.querySelectorAll('.fin-tab').forEach(function (btn) {
        btn.addEventListener('click', function () { switchTab(btn.dataset.tab); });
    });
 
    // Simple filter for quotation and payment panels (invoice is handled by original IIFE)
    function filterSimple(rowClass, emptyId, countId, infoId) {
        var searchEl = document.getElementById('searchInput');
        var fromEl   = document.getElementById('filterFrom');
        var toEl     = document.getElementById('filterTo');
        var q        = searchEl ? searchEl.value.toLowerCase() : '';
        var from     = fromEl ? fromEl.value : '';
        var to       = toEl   ? toEl.value   : '';
 
        var rows     = Array.from(document.querySelectorAll('.' + rowClass));
        var emptyRow = document.getElementById(emptyId);
        var countEl  = document.getElementById(countId);
        var infoEl   = document.getElementById(infoId);
        var visible  = 0;
 
        rows.forEach(function (row) {
            var date = row.dataset.date || '';
            var text = row.textContent.toLowerCase();
            var show = (!q || text.includes(q)) && (!from || date >= from) && (!to || date <= to);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
 
        var n = 1;
        rows.forEach(function (row) {
            if (row.style.display !== 'none') {
                var num = row.querySelector('.row-num');
                if (num) num.textContent = n++;
            }
        });
 
        if (emptyRow) emptyRow.classList.toggle('d-none', visible > 0);
        if (countEl)  countEl.textContent = visible;
        if (infoEl)   infoEl.textContent  = 'Showing ' + visible + ' record' + (visible !== 1 ? 's' : '');
    }
 
    // Row selection for quotation and payment panels
    function bindSimpleSelection(rowClass, selectedLabelId, selectedIdId, btnIds) {
        var selectedRow = null;
 
        function setButtons(enabled) {
            btnIds.forEach(function (id) {
                var btn = document.getElementById(id);
                if (btn) btn.disabled = !enabled;
            });
        }
 
        document.querySelectorAll('.' + rowClass).forEach(function (row) {
            row.addEventListener('click', function () {
                if (selectedRow === row) {
                    row.classList.remove('row-selected');
                    selectedRow = null;
                    var lbl = document.getElementById(selectedLabelId);
                    if (lbl) lbl.style.opacity = '0';
                    setButtons(false);
                    return;
                }
                if (selectedRow) selectedRow.classList.remove('row-selected');
                selectedRow = row;
                row.classList.add('row-selected');
                var lbl = document.getElementById(selectedLabelId);
                var sid = document.getElementById(selectedIdId);
                if (sid) sid.textContent = row.querySelector('td:nth-child(2)').textContent.trim();
                if (lbl) lbl.style.opacity = '1';
                setButtons(true);
            });
        });
    }
 
    bindSimpleSelection('qt-row', 'qtSelectedLabel', 'qtSelectedId', ['btnQtPreview','btnQtEdit','btnQtCancel','btnQtPrint']);
    bindSimpleSelection('py-row', 'pySelectedLabel', 'pySelectedId', ['btnPyPreview','btnPyEdit','btnPyVoid','btnPyPrint']);
 
    // Wire search/filter to also update quotation and payment panels
    ['searchInput','filterFrom','filterTo'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            el.addEventListener('input',  function () { filterSimple('qt-row','qtEmptyRow','qtRowCount','qtPageInfo'); filterSimple('py-row','pyEmptyRow','pyRowCount','pyPageInfo'); });
            el.addEventListener('change', function () { filterSimple('qt-row','qtEmptyRow','qtRowCount','qtPageInfo'); filterSimple('py-row','pyEmptyRow','pyRowCount','pyPageInfo'); });
        }
    });
 
    // Run initial filter counts
    filterSimple('qt-row', 'qtEmptyRow', 'qtRowCount', 'qtPageInfo');
    filterSimple('py-row', 'pyEmptyRow', 'pyRowCount', 'pyPageInfo');
 
    // Start on Invoice tab (same as the original working state)
    switchTab('invoice');
 
})();
