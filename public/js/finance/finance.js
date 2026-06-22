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
