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
 
    const btnEdit     = document.getElementById('btnEditInvoice');
    const btnCancel   = document.getElementById('btnCancel');
    const btnPrint    = document.getElementById('btnPrintInvoice');
 
    let sortCol = -1, sortDir = 1;
    let selectedRow = null;
 
    // ── data rows ────────────────────────────────────────────
    function dataRows() {
        return Array.from(tbody.querySelectorAll('tr.inv-row'));
    }
 
    // ── row selection ────────────────────────────────────────
    function selectRow(row) {
        if (selectedRow) selectedRow.classList.remove('row-selected');
        if (selectedRow === row) {
            selectedRow = null;
            setActionButtons(false);
            return;
        }
        selectedRow = row;
        row.classList.add('row-selected');
        setActionButtons(true);
    }
 
    function setActionButtons(enabled) {
        [btnEdit, btnCancel, btnPrint].forEach(btn => {
            if (!btn) return;
            btn.disabled = !enabled;
        });
    }
 
    dataRows().forEach(row => {
        row.addEventListener('click', () => selectRow(row));
    });
 
    // ── filter ───────────────────────────────────────────────
    function applyFilter() {
        const q    = (searchInput?.value || '').toLowerCase();
        const from = filterFrom?.value || '';
        const to   = filterTo?.value   || '';
        let visible = 0;
 
        dataRows().forEach(row => {
            const cells   = row.querySelectorAll('td');
            const invDate = cells[2]?.textContent.trim() || '';
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
    function applySort(colIdx) {
        const rows = dataRows();
        rows.sort((a, b) => {
            const aText = a.querySelectorAll('td')[colIdx + 1]?.textContent.trim() || '';
            const bText = b.querySelectorAll('td')[colIdx + 1]?.textContent.trim() || '';
            return aText.localeCompare(bText, undefined, { numeric: true }) * sortDir;
        });
        rows.forEach(r => tbody.appendChild(r));
        if (emptyRow) tbody.appendChild(emptyRow);
        applyFilter();
    }
 
    table.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col);
            table.querySelectorAll('th.sortable').forEach(h => h.classList.remove('sort-asc','sort-desc'));
            sortDir = (sortCol === col) ? sortDir * -1 : 1;
            sortCol = col;
            th.classList.add(sortDir === 1 ? 'sort-asc' : 'sort-desc');
            applySort(col);
        });
    });
 
    // ── action button stubs ──────────────────────────────────
    if (btnEdit) btnEdit.addEventListener('click', () => {
        const id = selectedRow?.dataset.id;
        if (id) window.location.href = `/finance/${id}/edit`;
    });
 
    if (btnPrint) btnPrint.addEventListener('click', () => {
        const id = selectedRow?.dataset.id;
        if (id) window.open(`/finance/${id}/print`, '_blank');
    });
 
    if (btnCancel) btnCancel.addEventListener('click', () => {
        const id = selectedRow?.dataset.id;
        if (!id) return;
        if (confirm(`Cancel invoice ${id}?`)) {
            // POST /finance/{id}/cancel
        }
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
