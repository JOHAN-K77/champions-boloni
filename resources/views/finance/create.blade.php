@extends('layouts.app')

@section('title', 'Finance | Create Record')

@section('content')

{{-- Student / Payment Search --}}
<div class="p-3 border rounded bg-light mb-3">
    <div class="d-flex gap-2 mb-3 align-items-center">
        <h6 class="mb-0 text-nowrap">Search student or payment</h6>
        <input type="text" class="form-control form-control-sm w-100" placeholder="Search by name, ID, or invoice..." id="studentSearch">
    </div>
 
    {{-- ========================================================
         RICHBOX 1: Invoice Table
    ========================================================= --}}
    <div class="richbox mb-3" id="invoiceBox">
        <div class="richbox-header">
            <i class="bi bi-receipt me-1"></i> Invoices
        </div>
        <div class="richbox-body">
            <table class="richbox-table" id="invoiceTable">
                <thead>
                    <tr>
                        <th style="width:110px;">Invoice ID</th>
                        <th style="width:100px;">Invoice Date</th>
                        <th style="width:80px;">Code</th>
                        <th>Invoice Name</th>
                        <th style="width:70px;">Term</th>
                        <th>Description</th>
                        <th style="width:120px;">Total Invoice</th>
                        <th style="width:110px;">Total Paid</th>
                        <th style="width:110px;">Pay</th>
                        <th style="width:70px;" class="text-center">Paid Off</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dummy rows: replace with @foreach($invoices as $inv) later --}}
                    <tr>
                        <td class="text-muted">INV-2024-0001</td>
                        <td>2024-01-15</td>
                        <td><span class="richbox-badge">SPP</span></td>
                        <td>SPP Bulan Januari</td>
                        <td class="text-center">1</td>
                        <td class="text-muted">Pembayaran SPP semester 1</td>
                        <td class="text-end fw-semibold">Rp 1.500.000</td>
                        <td class="text-end text-success">Rp 500.000</td>
                        <td class="text-end">Rp 1.000.000</td>
                            <!-- <div class="input-group input-group-sm">
                                <span class="input-group-text px-1">Rp</span>
                                <input type="text" class="form-control pay-input" value="1000000"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    data-invoice="INV-2024-0001">
                            </div>
                        </td> -->
                        <td class="text-center">
                            <input class="richbox-check paid-check" type="checkbox" title="Mark as paid off">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">INV-2024-0002</td>
                        <td>2024-02-15</td>
                        <td><span class="richbox-badge badge-blue">UTS</span></td>
                        <td>Biaya UTS Semester 1</td>
                        <td class="text-center">1</td>
                        <td class="text-muted">Ujian Tengah Semester</td>
                        <td class="text-end fw-semibold">Rp 300.000</td>
                        <td class="text-end text-success">Rp 0</td>
                        <td class="text-end">Rp  300.000</td>
                        <td class="text-center">
                            <input class="richbox-check paid-check" type="checkbox" title="Mark as paid off">
                        </td>
                    </tr>
                    <tr class="richbox-row-muted">
                        <td class="text-muted">INV-2024-0003</td>
                        <td>2024-03-01</td>
                        <td><span class="richbox-badge badge-green">BUKU</span></td>
                        <td>Biaya Buku Paket</td>
                        <td class="text-center">2</td>
                        <td class="text-muted">Buku paket semester 2</td>
                        <td class="text-end fw-semibold">Rp 450.000</td>
                        <td class="text-end text-success">Rp 450.000</td>
                        <td class="text-end">Rp 0</td>
                        <td class="text-center">
                            <input class="richbox-check paid-check" type="checkbox" checked disabled title="Already paid off">
                        </td>
                    </tr>
                    {{-- Empty state row (shown when no data) --}}
                    <tr class="richbox-empty-row d-none">
                        <td colspan="10" class="text-center text-muted py-3">
                            <i class="bi bi-inbox me-1"></i> No invoice data. Search or select a student above.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
 
    {{-- Summary totals --}}
    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <label for="invoice_tot" class="form-label small mb-1">Total Invoice</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="invoice_tot" placeholder="0" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <label for="discount_tot" class="form-label small mb-1">Total Discount</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="discount_tot" placeholder="0" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <label for="tagihan_tot" class="form-label small mb-1">Total Tagihan</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="tagihan_tot" placeholder="0" readonly>
            </div>
        </div>
        <div class="col-md-3">
            <label for="pembayaran_tot" class="form-label small mb-1">Total Pembayaran</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="pembayaran_tot" placeholder="0" readonly>
            </div>
        </div>
    </div>
 
    {{-- Notes + Date --}}
    <div class="row g-2">
        <div class="col-md-8">
            <label for="notes" class="form-label small mb-1">Notes</label>
            <input type="text" class="form-control form-control-sm" id="notes" placeholder="Masukkan keterangan...">
        </div>
        <div class="col-md-4">
            <label for="date_sis" class="form-label small mb-1">System Date</label>
            <input type="text" class="form-control form-control-sm" id="date_sis" readonly>
        </div>
    </div>
</div>
 
{{-- ============================================================
     Payment Method Tabs (Cash / Transfer / Card / Giro / Deposit)
============================================================= --}}
 
<div id="tab-cash" class="tab-content-area">
    <div class="p-3 border rounded bg-white mb-3">
        <div class="mb-3">
            <label for="cash_amountInput" class="form-label small">Total Amount</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="cash_amountInput"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Enter amount">
            </div>
        </div>
        <div class="mb-3">
            <label for="cash_kodeKas" class="form-label small">Kode Kas</label>
            <select class="form-select form-select-sm" id="cash_kodeKas">
                <option>-- Pilih kode --</option>
            </select>
        </div>
    </div>
</div>
 
<div id="tab-transfer" class="tab-content-area" style="display:none;">
    <div class="p-3 border rounded bg-white mb-3">
        <div class="mb-3">
            <label for="transferDate" class="form-label small">Transfer Date</label>
            <input type="date" class="form-control form-control-sm" id="transferDate">
        </div>
        <div class="mb-3">
            <label for="transfer_amountInput" class="form-label small">Total Amount</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="transfer_amountInput"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Enter amount">
            </div>
        </div>
        <div class="mb-3">
            <label for="transfer_ref_id" class="form-label small">ID Reference</label>
            <input type="text" class="form-control form-control-sm" id="transfer_ref_id">
        </div>
        <div class="mb-3">
            <label for="transfer_kodeKas" class="form-label small">Kode Kas</label>
            <select class="form-select form-select-sm" id="transfer_kodeKas">
                <option>-- Pilih kode --</option>
            </select>
        </div>
    </div>
</div>
 
<div id="tab-card" class="tab-content-area" style="display:none;">
    <div class="p-3 border rounded bg-white mb-3">
        <div class="mb-3">
            <label for="card_amountInput" class="form-label small">Total Amount</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="card_amountInput"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Enter amount">
            </div>
        </div>
        <div class="mb-3">
            <label for="card_ref_id" class="form-label small">ID Reference</label>
            <input type="text" class="form-control form-control-sm" id="card_ref_id">
        </div>
        <div class="mb-3">
            <label for="card_kodeKas" class="form-label small">Kode Kas</label>
            <select class="form-select form-select-sm" id="card_kodeKas">
                <option>-- Pilih kode --</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label small">Tipe Kartu &amp; Fee</label>
            <div class="input-group input-group-sm">
                <select class="form-select" id="cardType">
                    <option>-- Pilih tipe kartu --</option>
                    <option>Visa</option>
                    <option>Mastercard</option>
                    <option>GPN</option>
                </select>
                <input type="text" class="form-control" id="cardFee" placeholder="0"
                    oninput="this.value=this.value.replace(/[^0-9.]/g,'')">
                <span class="input-group-text">% Fee</span>
            </div>
        </div>
    </div>
</div>
 
<div id="tab-giro" class="tab-content-area" style="display:none;">
    <div class="p-3 border rounded bg-white mb-3">
        <div class="richbox mb-3" id="giroBox">
            <div class="richbox-header">
                <i class="bi bi-bank me-1"></i> Giro / Cheque
            </div>
            <div class="richbox-body">
                <table class="richbox-table" id="giroTable">
                    <thead>
                        <tr>
                            <th style="width:90px;">Type</th>
                            <th style="width:100px;">Date</th>
                            <th style="width:150px;">Cheque/Giro Number</th>
                            <th style="width:110px;">Ref. ID</th>
                            <th style="width:130px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Dummy rows: replace with @foreach($giros as $giro) later --}}
                        <tr>
                            <td><span class="richbox-badge badge-blue">Giro</span></td>
                            <td>2024-03-10</td>
                            <td class="text-muted">GR-0098231</td>
                            <td class="text-muted">REF-77210</td>
                            <td class="text-end fw-semibold">Rp 2.000.000</td>
                        </tr>
                        <tr>
                            <td><span class="richbox-badge badge-green">Cheque</span></td>
                            <td>2024-03-12</td>
                            <td class="text-muted">CQ-0044120</td>
                            <td class="text-muted">REF-77298</td>
                            <td class="text-end fw-semibold">Rp 750.000</td>
                        </tr>
                        {{-- Empty state row --}}
                        <tr class="richbox-empty-row d-none">
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="bi bi-inbox me-1"></i> No giro/cheque data available.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <label for="giro_amountInput" class="form-label small">Total Amount</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="giro_amountInput"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Enter amount">
            </div>
        </div>
        <div class="mb-3">
            <label for="giro_kodeKas" class="form-label small">Kode Kas</label>
            <select class="form-select form-select-sm" id="giro_kodeKas">
                <option>-- Pilih kode --</option>
            </select>
        </div>
    </div>
</div>
 
<div id="tab-deposit" class="tab-content-area" style="display:none;">
    <div class="p-3 border rounded bg-white mb-3">
        <div class="richbox mb-3" id="depositBox">
            <div class="richbox-header">
                <i class="bi bi-piggy-bank me-1"></i> Deposit
            </div>
            <div class="richbox-body">
                <table class="richbox-table" id="depositTable">
                    <thead>
                        <tr>
                            <th style="width:100px;">Date</th>
                            <th style="width:110px;">Deposit ID</th>
                            <th>Description</th>
                            <th style="width:120px;">Amount</th>
                            <th style="width:60px;" class="text-center">Used</th>
                            <th style="width:60px;" class="text-center">Paid</th>
                            <th style="width:50px;" class="text-center">All</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Dummy rows: replace with @foreach($deposits as $dep) later --}}
                        <tr id="dep-r1">
                            <td>2024-01-05</td>
                            <td class="text-muted">DEP-2024-001</td>
                            <td class="text-muted">Deposit awal pendaftaran</td>
                            <td class="text-end fw-semibold">Rp 1.000.000</td>
                            <td class="text-end">Rp 0</td>
                            <td class="text-end">Rp 0</td>
                            <td class="text-center">
                                <input class="richbox-check dep-all-check" type="checkbox" title="Select all (Used + Paid)">
                            </td>
                        </tr>
                        <tr id="dep-r2">
                            <td>2024-02-20</td>
                            <td class="text-muted">DEP-2024-002</td>
                            <td class="text-muted">Deposit tambahan semester 1</td>
                            <td class="text-end fw-semibold">Rp 500.000</td>
                            <td class="text-end">Rp 100.000</td>
                            <td class="text-end">Rp 0</td>
                            <td class="text-center">
                                <input class="richbox-check dep-all-check" type="checkbox" title="Select all (Used + Paid)">
                            </td>
                        </tr>
                        <tr class="richbox-empty-row d-none">
                            <td colspan="7" class="text-center text-muted py-3">
                                <i class="bi bi-inbox me-1"></i> No deposit data available.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-3">
            <label for="deposit_amountInput" class="form-label small">Total Amount</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="deposit_amountInput"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Enter amount">
            </div>
        </div>
        <div class="mb-3">
            <label for="deposit_compensation" class="form-label small">Compensation</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="deposit_compensation"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Enter compensation">
            </div>
        </div>
        <div class="mb-3">
            <label for="deposit_surplus" class="form-label small">Deposit Surplus Payment</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="deposit_surplus"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="Enter surplus payment">
            </div>
        </div>
        <div class="mb-3">
            <label for="deposit_kodeKas" class="form-label small">Kode Kas</label>
            <select class="form-select form-select-sm" id="deposit_kodeKas">
                <option>-- Pilih kode --</option>
            </select>
        </div>
    </div>
</div>
 
{{-- ============================================================
     RICHBOX 2: Discount Table
============================================================= --}}
<div class="p-3 border rounded bg-light">
    <div class="mb-3">
        <label for="card_fee_total" class="form-label small">Total Card Fee</label>
        <input type="text" class="form-control form-control-sm" id="card_fee_total" placeholder="0" readonly>
    </div>
    <div class="mb-5">
        <label for="card_pay_total" class="form-label small">Total Pay by Card</label>
        <input type="text" class="form-control form-control-sm" id="card_pay_total" placeholder="0" readonly>
    </div>
    <div class="richbox" id="discountBox">
        <div class="richbox-header">
            <i class="bi bi-tag me-1"></i> Additional Discount
        </div>
        <div class="richbox-body">
            <table class="richbox-table" id="discountTable">
                <thead>
                    <tr>
                        <th style="width:130px;">Disc. ID Number</th>
                        <th style="width:120px;">Fee</th>
                        <th>Description</th>
                        <th style="width:130px;">Student ID</th>
                        <th style="width:70px;" class="text-center">Used</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dummy rows: replace with @foreach($discounts as $disc) later --}}
                    <tr>
                        <td class="text-muted">DISC-2024-001</td>
                        <td class="text-end text-danger fw-semibold">10%</td>
                        <td>Early Payment Discount</td>
                        <td class="text-muted">STD-0042</td>
                        <td class="text-center">
                            <input class="richbox-check disc-check" type="checkbox" title="Mark as used">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">DISC-2024-002</td>
                        <td class="text-end text-danger fw-semibold">20%</td>
                        <td>Scholarship – Prestasi</td>
                        <td class="text-muted">STD-0042</td>
                        <td class="text-center">
                            <input class="richbox-check disc-check" type="checkbox" title="Mark as used">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">DISC-2024-003</td>
                        <td class="text-end text-danger fw-semibold">Rp 150.000</td>
                        <td>Sibling Discount</td>
                        <td class="text-muted">STD-0017</td>
                        <td class="text-center">
                            <input class="richbox-check disc-check" type="checkbox" checked title="Mark as used">
                        </td>
                    </tr>
                    {{-- Empty state row --}}
                    <tr class="richbox-empty-row d-none">
                        <td colspan="5" class="text-center text-muted py-3">
                            <i class="bi bi-inbox me-1"></i> No discount data available.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="btn btn-primary mt-2" id="saveButton">Save Changes</div>

@endsection