@extends('layouts.app')
 
@section('title', 'Finance')
 
@section('content')
 
<div class="dashboard-card">
 
    {{-- ══ TOP BAR ══════════════════════════════════════════════════ --}}
    <div class="d-flex align-items-start justify-content-between mb-3">
        <div>
            <h5 class="mb-0 fw-bold" id="pageTitle">Finance</h5>
            <small class="text-muted" id="pageSubtitle">Select a tab to view records</small>
        </div>
 
        <div class="d-flex flex-column gap-1 align-items-end">
            <div class="tab-topbar" id="topbar-quotation" style="display:none;">
                <a href="#" class="btn btn-sm btn-dark">
                    <i class="bi bi-plus-lg me-1"></i> New Quotation
                </a>
                <a href="#" class="btn btn-sm btn-outline-dark mt-1">
                    <i class="bi bi-file-earmark-plus me-1"></i> Make Invoice
                </a>
            </div>
            <div class="tab-topbar" id="topbar-invoice" style="display:none;">
                <a href="#" class="btn btn-sm btn-dark">
                    <i class="bi bi-plus-lg me-1"></i> New Invoice
                </a>
            </div>
            <div class="tab-topbar" id="topbar-payment" style="display:none;">
                <a href="{{ route('finance.create') }}" class="btn btn-sm btn-dark">
                    <i class="bi bi-plus-lg me-1"></i> New Payment
                </a>
            </div>
        </div>
    </div>
 
    {{-- ══ TAB STRIP ════════════════════════════════════════════════ --}}
    <div class="finance-tabs" id="financeTabs">
        <button class="fin-tab" data-tab="quotation">
            <i class="bi bi-file-text me-1"></i> Quotation
        </button>
        <button class="fin-tab" data-tab="invoice">
            <i class="bi bi-receipt me-1"></i> Invoice
        </button>
        <button class="fin-tab" data-tab="payment">
            <i class="bi bi-cash-stack me-1"></i> Payment
        </button>
    </div>
 
    {{-- ══ SEARCH & FILTER ══════════════════════════════════════════ --}}
    <div class="d-flex gap-2 mt-2 mb-2">
        <div class="input-group input-group-sm" style="max-width:320px;">
            <span class="input-group-text bg-white">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" id="searchInput" class="form-control border-start-0"
                   placeholder="Search records...">
        </div>
        <input type="date" id="filterFrom" class="form-control form-control-sm"
               style="max-width:145px;" title="Date from">
        <input type="date" id="filterTo" class="form-control form-control-sm"
               style="max-width:145px;" title="Date to">
        <button class="btn btn-sm btn-outline-secondary" id="clearFilter" title="Clear filters">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
 
    {{-- ══ PANEL: QUOTATION ═════════════════════════════════════════ --}}
    <div class="fin-tab-panel" id="panel-quotation" style="display:none;">
        <div class="richbox richbox-fullwidth">
            <div class="richbox-header">
                <i class="bi bi-file-text me-1"></i> Quotation Records
                <span class="ms-2 badge bg-secondary fw-normal tab-row-count"
                      style="font-size:10px;">0</span>
                <span class="ms-auto fst-italic fw-normal tab-selected-label"
                      style="font-size:11px;color:#e3b705;opacity:0;">
                    <i class="bi bi-check2-circle me-1"></i>
                    <span class="tab-selected-id"></span> selected
                </span>
            </div>
            <div class="richbox-body" style="max-height:calc(100vh - 400px);">
                <table class="richbox-table" id="quotationTable">
                    <thead>
                        <tr>
                            <th style="width:38px;">#</th>
                            <th class="sortable" data-col="0" style="width:140px;">Quotation ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="1" style="width:110px;">Quotation Date <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="2" style="width:130px;">Student ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="3">Student Name <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="4">Instalment Name <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="5" style="width:110px;">Instalment Date <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        </tr>
                    </thead>
                    <tbody id="quotationBody">
                        {{-- @foreach($quotations as $i => $q)
                        <tr class="fin-row" data-tab="quotation"
                            data-id="{{ $q->Quotation_ID }}"
                            data-date="{{ $q->Quotation_Date }}"
                            data-student="{{ $q->Student_Student_ID }}"
                            data-name="{{ $q->Student_Name }}"
                            data-instalment="{{ $q->Installment_Name }}"
                            data-idate="{{ $q->Installment_Date }}">
                            <td class="row-num text-muted">{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $q->Quotation_ID }}</td>
                            <td>{{ \Carbon\Carbon::parse($q->Quotation_Date)->format('Y-m-d') }}</td>
                            <td class="text-muted">{{ $q->Student_Student_ID }}</td>
                            <td>{{ $q->Student_Name }}</td>
                            <td>{{ $q->Installment_Name }}</td>
                            <td>{{ \Carbon\Carbon::parse($q->Installment_Date)->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach --}}
 
                        <tr class="fin-row" data-tab="quotation" data-id="QT-2024-001"
                            data-date="2024-01-10" data-student="STD-0042"
                            data-name="Andi Pratama" data-instalment="SPP Semester 1"
                            data-idate="2024-01-01">
                            <td class="row-num text-muted">1</td>
                            <td class="fw-semibold">QT-2024-001</td>
                            <td>2024-01-10</td>
                            <td class="text-muted">STD-0042</td>
                            <td>Andi Pratama</td>
                            <td>SPP Semester 1</td>
                            <td>2024-01-01</td>
                        </tr>
                        <tr class="fin-row" data-tab="quotation" data-id="QT-2024-002"
                            data-date="2024-02-20" data-student="STD-0017"
                            data-name="Budi Santoso" data-instalment="Biaya Buku Paket"
                            data-idate="2024-02-15">
                            <td class="row-num text-muted">2</td>
                            <td class="fw-semibold">QT-2024-002</td>
                            <td>2024-02-20</td>
                            <td class="text-muted">STD-0017</td>
                            <td>Budi Santoso</td>
                            <td>Biaya Buku Paket</td>
                            <td>2024-02-15</td>
                        </tr>
                        <tr class="fin-row" data-tab="quotation" data-id="QT-2024-003"
                            data-date="2024-03-25" data-student="STD-0055"
                            data-name="Citra Dewi" data-instalment="UTS Semester 1"
                            data-idate="2024-03-20">
                            <td class="row-num text-muted">3</td>
                            <td class="fw-semibold">QT-2024-003</td>
                            <td>2024-03-25</td>
                            <td class="text-muted">STD-0055</td>
                            <td>Citra Dewi</td>
                            <td>UTS Semester 1</td>
                            <td>2024-03-20</td>
                        </tr>
 
                        <tr id="quotationEmpty" class="d-none">
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No quotation records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="richbox-footer">
                <small class="text-muted tab-page-info">Showing all records</small>
            </div>
        </div>
    </div>
 
    {{-- ══ PANEL: INVOICE ═══════════════════════════════════════════ --}}
    <div class="fin-tab-panel" id="panel-invoice" style="display:none;">
        <div class="richbox richbox-fullwidth">
            <div class="richbox-header">
                <i class="bi bi-receipt me-1"></i> Invoice Records
                <span class="ms-2 badge bg-secondary fw-normal tab-row-count"
                      style="font-size:10px;">0</span>
                <span class="ms-auto fst-italic fw-normal tab-selected-label"
                      style="font-size:11px;color:#e3b705;opacity:0;">
                    <i class="bi bi-check2-circle me-1"></i>
                    <span class="tab-selected-id"></span> selected
                </span>
            </div>
            <div class="richbox-body" style="max-height:calc(100vh - 400px);">
                <table class="richbox-table" id="invoiceTable">
                    <thead>
                        <tr>
                            <th style="width:38px;">#</th>
                            <th class="sortable" data-col="0" style="width:145px;">Invoice ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="1" style="width:110px;">Invoice Date <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="2" style="width:145px;">Quotation ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="3">Instalment Name <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="4" style="width:110px;">Quotation Date <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th style="width:120px;" class="text-end">Total Paid</th>
                            <th style="width:90px;" class="text-center">Paid Status</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceBody">
                        {{-- @foreach($invoices as $i => $inv) ... @endforeach --}}
 
                        <tr class="fin-row" data-tab="invoice" data-id="INV-2024-0001"
                            data-date="2024-01-15" data-quotation="QT-2024-001"
                            data-instalment="SPP Semester 1" data-qdate="2024-01-10"
                            data-total="1.500.000" data-status="Paid">
                            <td class="row-num text-muted">1</td>
                            <td class="fw-semibold">INV-2024-0001</td>
                            <td>2024-01-15</td>
                            <td class="text-muted">QT-2024-001</td>
                            <td>SPP Semester 1</td>
                            <td>2024-01-10</td>
                            <td class="text-end fw-semibold">Rp 1.500.000</td>
                            <td class="text-center"><span class="status-badge status-paid">Paid</span></td>
                        </tr>
                        <tr class="fin-row" data-tab="invoice" data-id="INV-2024-0002"
                            data-date="2024-02-15" data-quotation="QT-2024-001"
                            data-instalment="SPP Semester 1" data-qdate="2024-01-10"
                            data-total="300.000" data-status="Unpaid">
                            <td class="row-num text-muted">2</td>
                            <td class="fw-semibold">INV-2024-0002</td>
                            <td>2024-02-15</td>
                            <td class="text-muted">QT-2024-001</td>
                            <td>SPP Semester 1</td>
                            <td>2024-01-10</td>
                            <td class="text-end fw-semibold">Rp 300.000</td>
                            <td class="text-center"><span class="status-badge status-unpaid">Unpaid</span></td>
                        </tr>
                        <tr class="fin-row" data-tab="invoice" data-id="INV-2024-0003"
                            data-date="2024-03-01" data-quotation="QT-2024-002"
                            data-instalment="Biaya Buku Paket" data-qdate="2024-02-20"
                            data-total="450.000" data-status="Paid">
                            <td class="row-num text-muted">3</td>
                            <td class="fw-semibold">INV-2024-0003</td>
                            <td>2024-03-01</td>
                            <td class="text-muted">QT-2024-002</td>
                            <td>Biaya Buku Paket</td>
                            <td>2024-02-20</td>
                            <td class="text-end fw-semibold">Rp 450.000</td>
                            <td class="text-center"><span class="status-badge status-paid">Paid</span></td>
                        </tr>
 
                        <tr id="invoiceEmpty" class="d-none">
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No invoice records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="richbox-footer">
                <small class="text-muted tab-page-info">Showing all records</small>
            </div>
        </div>
    </div>
 
    {{-- ══ PANEL: PAYMENT ═══════════════════════════════════════════ --}}
    <div class="fin-tab-panel" id="panel-payment" style="display:none;">
        <div class="richbox richbox-fullwidth">
            <div class="richbox-header">
                <i class="bi bi-cash-stack me-1"></i> Payment Records
                <span class="ms-2 badge bg-secondary fw-normal tab-row-count"
                      style="font-size:10px;">0</span>
                <span class="ms-auto fst-italic fw-normal tab-selected-label"
                      style="font-size:11px;color:#e3b705;opacity:0;">
                    <i class="bi bi-check2-circle me-1"></i>
                    <span class="tab-selected-id"></span> selected
                </span>
            </div>
            <div class="richbox-body" style="max-height:calc(100vh - 400px);">
                <table class="richbox-table" id="paymentTable">
                    <thead>
                        <tr>
                            <th style="width:38px;">#</th>
                            <th class="sortable" data-col="0" style="width:145px;">Invoice ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="1" style="width:120px;">Payment Method <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="2" style="width:130px;">Deposit ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="3" style="width:120px;">Card <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="4" style="width:130px;">Cek/Giro ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th style="width:120px;" class="text-end">Amount (Cash)</th>
                            <th style="width:120px;" class="text-end">Amount (Card)</th>
                        </tr>
                    </thead>
                    <tbody id="paymentBody">
                        {{-- @foreach($payments as $i => $pay) ... @endforeach --}}
 
                        <tr class="fin-row" data-tab="payment" data-id="INV-2024-0001"
                            data-method="Cash" data-deposit="" data-card=""
                            data-cek="" data-cash="1.500.000" data-cardamt="0">
                            <td class="row-num text-muted">1</td>
                            <td class="fw-semibold">INV-2024-0001</td>
                            <td><span class="richbox-badge badge-green">Cash</span></td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td class="text-end fw-semibold">Rp 1.500.000</td>
                            <td class="text-end text-muted">—</td>
                        </tr>
                        <tr class="fin-row" data-tab="payment" data-id="INV-2024-0003"
                            data-method="Card" data-deposit="" data-card="Visa"
                            data-cek="" data-cash="0" data-cardamt="450.000">
                            <td class="row-num text-muted">2</td>
                            <td class="fw-semibold">INV-2024-0003</td>
                            <td><span class="richbox-badge badge-blue">Card</span></td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">Visa</td>
                            <td class="text-muted">—</td>
                            <td class="text-end text-muted">—</td>
                            <td class="text-end fw-semibold">Rp 450.000</td>
                        </tr>
                        <tr class="fin-row" data-tab="payment" data-id="INV-2024-0004"
                            data-method="Giro" data-deposit="" data-card=""
                            data-cek="GR-0098231" data-cash="0" data-cardamt="0">
                            <td class="row-num text-muted">3</td>
                            <td class="fw-semibold">INV-2024-0004</td>
                            <td><span class="richbox-badge">Giro</span></td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">GR-0098231</td>
                            <td class="text-end text-muted">—</td>
                            <td class="text-end text-muted">—</td>
                        </tr>
 
                        <tr id="paymentEmpty" class="d-none">
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No payment records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="richbox-footer">
                <small class="text-muted tab-page-info">Showing all records</small>
            </div>
        </div>
    </div>
 
    {{-- ══ ACTION BUTTONS ═══════════════════════════════════════════ --}}
    <div class="tab-actions d-flex gap-2 mt-3" id="actions-quotation" style="display:none !important;">
        <button class="btn btn-sm btn-outline-primary  inv-action-btn" data-action="preview" disabled><i class="bi bi-eye me-1"></i> Preview</button>
        <button class="btn btn-sm btn-outline-dark     inv-action-btn" data-action="edit"    disabled><i class="bi bi-pencil me-1"></i> Edit Quotation</button>
        <button class="btn btn-sm btn-outline-danger   inv-action-btn" data-action="cancel"  disabled><i class="bi bi-x-circle me-1"></i> Cancel</button>
        <button class="btn btn-sm btn-outline-secondary inv-action-btn" data-action="print"  disabled><i class="bi bi-printer me-1"></i> Print</button>
    </div>
 
    <div class="tab-actions d-flex gap-2 mt-3" id="actions-invoice" style="display:none !important;">
        <button class="btn btn-sm btn-outline-primary  inv-action-btn" data-action="preview" disabled><i class="bi bi-eye me-1"></i> Preview</button>
        <button class="btn btn-sm btn-outline-dark     inv-action-btn" data-action="edit"    disabled><i class="bi bi-pencil me-1"></i> Edit Invoice</button>
        <button class="btn btn-sm btn-outline-danger   inv-action-btn" data-action="cancel"  disabled><i class="bi bi-x-circle me-1"></i> Cancel</button>
        <button class="btn btn-sm btn-outline-secondary inv-action-btn" data-action="print"  disabled><i class="bi bi-printer me-1"></i> Print Invoice</button>
    </div>
 
    <div class="tab-actions d-flex gap-2 mt-3" id="actions-payment" style="display:none !important;">
        <button class="btn btn-sm btn-outline-primary  inv-action-btn" data-action="preview" disabled><i class="bi bi-eye me-1"></i> Preview</button>
        <button class="btn btn-sm btn-outline-dark     inv-action-btn" data-action="edit"    disabled><i class="bi bi-pencil me-1"></i> Edit Payment</button>
        <button class="btn btn-sm btn-outline-danger   inv-action-btn" data-action="cancel"  disabled><i class="bi bi-x-circle me-1"></i> Void</button>
        <button class="btn btn-sm btn-outline-secondary inv-action-btn" data-action="print"  disabled><i class="bi bi-printer me-1"></i> Print Receipt</button>
    </div>
 
</div>{{-- /dashboard-card --}}
 
 
{{-- ══ PREVIEW MODAL ════════════════════════════════════════════════ --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:8px;overflow:hidden;">
 
            <div class="modal-header"
                 style="background:#111;border-bottom:2px solid #e3b705;padding:10px 16px;">
                <div>
                    <h6 class="modal-title mb-0"
                        style="color:#e3b705;font-weight:700;letter-spacing:.04em;font-size:13px;">
                        <i class="bi bi-receipt me-1"></i>
                        <span id="previewModalTitle">RECORD PREVIEW</span>
                    </h6>
                    <small id="previewModalSubtitle" style="color:#aaa;font-size:11px;">—</small>
                </div>
                <button type="button" class="btn-close btn-close-white btn-close-sm"
                        data-bs-dismiss="modal"></button>
            </div>
 
            <div class="modal-body p-0">
                <div id="receiptArea" style="padding:28px 36px;background:#fff;">
 
                    <div class="text-center mb-4">
                        <div style="font-size:17px;font-weight:800;color:#111;letter-spacing:.02em;">
                            THE CHAMPIONS SCHOOL
                        </div>
                        <div style="font-size:11px;color:#666;margin-top:2px;">
                            Jl. Pendidikan No. 1, Boloni &nbsp;|&nbsp; Telp. (021) 000-0000
                        </div>
                        <hr style="border-top:2px solid #111;margin:10px 0 4px;">
                        <hr style="border-top:1px solid #111;margin:0 0 12px;">
                        <div id="receiptTypeLabel"
                             style="font-size:13px;font-weight:700;letter-spacing:.08em;color:#7e1e0d;">
                            RECORD DETAIL
                        </div>
                    </div>
 
                    <div id="receiptFields" style="font-size:13px;" class="mb-3"></div>
 
                    <hr style="border-top:1px dashed #bbb;margin:12px 0;">
 
                    <div style="font-size:12px;font-weight:700;text-transform:uppercase;
                                letter-spacing:.06em;color:#555;margin-bottom:8px;">
                        Detail
                    </div>
                    <table style="width:100%;border-collapse:collapse;font-size:13px;">
                        <thead>
                            <tr style="background:#f4f4f4;border-bottom:2px solid #ddd;">
                                <th style="padding:5px 8px;text-align:left;font-size:12px;color:#444;">Description</th>
                                <th style="padding:5px 8px;text-align:right;font-size:12px;color:#444;width:130px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2"
                                    style="padding:10px 8px;text-align:center;color:#aaa;font-size:12px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Detailed breakdown will be available once DB is connected.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="border-top:2px solid #ddd;">
                                <td style="padding:6px 8px;font-weight:700;">Total</td>
                                <td style="padding:6px 8px;text-align:right;font-weight:700;
                                           font-size:14px;color:#7e1e0d;" id="prev_total">—</td>
                            </tr>
                        </tfoot>
                    </table>
 
                    <hr style="border-top:1px dashed #bbb;margin:16px 0 12px;">
 
                    <div class="row g-0" style="font-size:12px;color:#555;">
                        <div class="col-6 text-center">
                            <div>Prepared by,</div>
                            <div style="height:48px;"></div>
                            <div style="border-top:1px solid #999;width:130px;margin:0 auto;padding-top:4px;">
                                Finance Staff
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div>Received by,</div>
                            <div style="height:48px;"></div>
                            <div style="border-top:1px solid #999;width:130px;margin:0 auto;padding-top:4px;">
                                Parent / Guardian
                            </div>
                        </div>
                    </div>
 
                    <div class="text-center mt-4" style="font-size:10px;color:#aaa;">
                        This document is computer generated and valid without signature when printed.
                    </div>
 
                </div>
            </div>
 
            <div class="modal-footer"
                 style="background:#f8f9fa;border-top:1px solid #e0e0e0;padding:8px 16px;">
                <small class="text-muted me-auto" style="font-size:11px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Breakdown available once DB is connected.
                </small>
                <button type="button" class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-dark" id="btnPrintFromModal">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
 
        </div>
    </div>
</div>
 
@endsection
