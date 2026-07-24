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
 
    let sortCol = -1, sortDir = 1;
    let selectedRow = null;
 
    // ── data rows ────────────────────────────────────────────
    function dataRows() {
        return Array.from(tbody.querySelectorAll('tr.inv-row'));
    }
 
    function setButtons(enabled) {
        [btnPreview, btnEdit, btnCancel, btnPrint].forEach(b => {
            if (b) b.disabled = !enabled;
        });
    }
 
    // ── row selection ────────────────────────────────────────
    function selectRow(row) {
        // clicking selected row deselects
        if (selectedRow === row) {
            row.classList.remove('row-selected');
            selectedRow = null;
            selectedLabel.style.opacity = '0';
            setButtons(false);
            return;
        }
        if (selectedRow) selectedRow.classList.remove('row-selected');
        selectedRow = row;
        row.classList.add('row-selected');
        if (selectedIdEl) selectedIdEl.textContent = row.dataset.id;
        if (selectedLabel) selectedLabel.style.opacity = '1';
        setButtons(true);
    }
 
    dataRows().forEach(row => {
        row.addEventListener('click', () => selectRow(row));
    });
     
    // ── preview modal ────────────────────────────────────────
    function openPreview(row) {
        const d = row.dataset;
        document.getElementById('previewModalSubtitle').textContent = d.id;
        document.getElementById('prev_invoiceId').textContent   = d.id       || '—';
        document.getElementById('prev_invoiceDate').textContent  = d.date     || '—';
        document.getElementById('prev_quotationId').textContent  = d.quotation|| '—';
        document.getElementById('prev_instalment').textContent   = d.instalment|| '—';
        document.getElementById('prev_qdate').textContent        = d.qdate    || '—';
        document.getElementById('prev_total').textContent        = 'Rp ' + (d.total || '—');
 
        const statusEl = document.getElementById('prev_status');
        statusEl.textContent = d.status || '—';
        statusEl.className   = 'status-badge ' +
            (d.status === 'Paid' ? 'status-paid' : 'status-unpaid');
 
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }
 
    if (btnPreview) {
        btnPreview.addEventListener('click', () => {
            if (selectedRow) openPreview(selectedRow);
        });
    }
 
    // Double-click row = instant preview
    dataRows().forEach(row => {
        row.addEventListener('dblclick', () => {
            selectRow(row);
            openPreview(row);
        });
    });
 
    // Print from modal
    if (btnPrintModal) {
        btnPrintModal.addEventListener('click', () => {
            window.print();
        });
    }
 
    // ── other action stubs ───────────────────────────────────
    if (btnEdit) btnEdit.addEventListener('click', () => {
        if (selectedRow) window.location.href = `/finance/${selectedRow.dataset.id}/edit`;
    });
 
    if (btnPrint) btnPrint.addEventListener('click', () => {
        if (selectedRow) openPreview(selectedRow); // open preview first, then print from there
    });
 
    if (btnCancel) btnCancel.addEventListener('click', () => {
        if (!selectedRow) return;
        if (confirm(`Cancel invoice ${selectedRow.dataset.id}?\nThis action cannot be undone.`)) {
            // TODO: POST /finance/{id}/cancel
        }
    });
 
    // ── filter ───────────────────────────────────────────────
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
 
        // re-number visible rows
        let n = 1;
        dataRows().forEach(row => {
            if (row.style.display !== 'none') {
                const numCell = row.querySelector('.row-num');
                if (numCell) numCell.textContent = n++;
            }
        });
 
        if (emptyRow)   emptyRow.classList.toggle('d-none', visible > 0);
        if (rowCountEl) rowCountEl.textContent = visible;
        if (pageInfo)   pageInfo.textContent   = `Showing ${visible} record${visible !== 1 ? 's' : ''}`;
    }
 
    // ── sort ─────────────────────────────────────────────────
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
 
    // ── event listeners ──────────────────────────────────────
    searchInput?.addEventListener('input',  applyFilter);
    filterFrom ?.addEventListener('change', applyFilter);
    filterTo   ?.addEventListener('change', applyFilter);
    clearBtn   ?.addEventListener('click',  () => {
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
