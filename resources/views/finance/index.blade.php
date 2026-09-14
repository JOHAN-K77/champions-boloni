@extends('layouts.app')
 
@section('title', 'Finance')
 
@section('content')
 
<div class="dashboard-card">
 
    <h3>Finance Module</h3>
    <hr>
 
    {{-- ══ TOP BAR ══════════════════════════════════════════════════ --}}
    <div class="d-flex align-items-start justify-content-between mb-2">
        <div>
            <h5 class="mb-0 fw-bold" id="pageTitle">Invoice</h5>
            <small class="text-muted" id="pageSubtitle">All settled invoice records</small>
        </div>
        <div id="topbar-quotation" style="display:none; flex-direction:column; gap:4px; align-items:flex-end;">
            <a href="#" class="btn btn-sm btn-dark">
                <i class="bi bi-plus-lg me-1"></i> New Quotation
            </a>
            <a href="#" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-file-earmark-plus me-1"></i> Make Invoice
            </a>
        </div>
        <div id="topbar-invoice" style="display:none;">
            <a href="#" class="btn btn-sm btn-dark">
                <i class="bi bi-plus-lg me-1"></i> New Invoice
            </a>
        </div>
        <div id="topbar-payment" style="display:none;">
            <a href="{{ route('finance.create') }}" class="btn btn-sm btn-dark">
                <i class="bi bi-plus-lg me-1"></i> New Payment
            </a>
        </div>
    </div>
 
    {{-- ══ TAB STRIP ════════════════════════════════════════════════ --}}
    <div class="finance-tabs" id="financeTabs">
        <button class="fin-tab" data-tab="quotation">
            <i class="bi bi-file-text me-1"></i> Quotation
        </button>
        <button class="fin-tab active" data-tab="invoice">
            <i class="bi bi-receipt me-1"></i> Invoice
        </button>
        <button class="fin-tab" data-tab="payment">
            <i class="bi bi-cash-stack me-1"></i> Payment
        </button>
    </div>
 
    {{-- ══ SEARCH & FILTER ══════════════════════════════════════════ --}}
    <div class="d-flex gap-2 mt-2 mb-2">
        <div class="input-group input-group-sm" style="max-width:320px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
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
 
 
    {{-- ════════════════════════════════════════════════════════════
         PANEL 1: QUOTATION
         Cols: Quotation Date | Quotation ID | Class |
               Student ID | Student Name | Note
    ═════════════════════════════════════════════════════════════ --}}
    <div id="panel-quotation" style="display:none;">
        <div class="richbox richbox-fullwidth">
            <div class="richbox-header">
                <i class="bi bi-file-text me-1"></i> Quotation Records
                <span class="ms-2 badge bg-secondary fw-normal" id="qtRowCount" style="font-size:10px;">0</span>
                <span class="ms-auto fst-italic fw-normal" id="qtSelectedLabel"
                      style="font-size:11px; color:#e3b705; opacity:0;">
                    <i class="bi bi-check2-circle me-1"></i>
                    <span id="qtSelectedId"></span> selected
                </span>
            </div>
            <div class="richbox-body" style="max-height:calc(100vh - 380px);">
                <table class="richbox-table" id="quotationTable">
                    <thead>
                        <tr>
                            <th style="width:38px;">#</th>
                            <th class="sortable" data-col="0" style="width:110px;">Quotation Date <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="1" style="width:140px;">Quotation ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="2" style="width:65px;">Class <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="3" style="width:120px;">Student ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="4">Student Name <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="5">Note <i class="bi bi-arrow-down-up sort-icon"></i></th>
                        </tr>
                    </thead>
                    <tbody id="quotationBody">
                        @forelse($quotations as $i => $q)
                        <tr class="qt-row"
                            data-date="{{ $q->Quotation_Date ? \Carbon\Carbon::parse($q->Quotation_Date)->format('Y-m-d') : '' }}">
                            <td class="row-num text-muted">{{ $i + 1 }}</td>
                            <td>{{ $q->Quotation_Date ? \Carbon\Carbon::parse($q->Quotation_Date)->format('Y-m-d') : '—' }}</td>
                            <td class="fw-semibold">{{ $q->Quotation_ID }}</td>
                            <td class="text-center">{{ $q->Grade_Name ?? '—' }}</td>
                            <td class="text-muted">{{ $q->Student_ID }}</td>
                            <td>{{ $q->Student_Name ?? '—' }}</td>
                            <td class="text-muted" style="white-space:normal; max-width:220px;">{{ $q->Installment_Desc ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No quotation records found.
                            </td>
                        </tr>
                        @endforelse
                        <tr id="qtEmptyRow" class="d-none">
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No quotation records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="richbox-footer">
                <small class="text-muted" id="qtPageInfo">
                    Showing {{ count($quotations) }} record{{ count($quotations) !== 1 ? 's' : '' }}
                </small>
            </div>
        </div>
    </div>
 
 
    {{-- ════════════════════════════════════════════════════════════
         PANEL 2: INVOICE
         Cols: Invoice Date | Invoice ID | Quotation ID |
               Student Name | Term | Description | Total Paid | Status
    ═════════════════════════════════════════════════════════════ --}}
    <div id="panel-invoice">
        <div class="richbox richbox-fullwidth" id="invoiceBox">
            <div class="richbox-header">
                <i class="bi bi-receipt me-1"></i> Invoice Records
                <span class="ms-2 badge bg-secondary fw-normal" id="rowCount" style="font-size:10px;">0</span>
                <span class="ms-auto fst-italic fw-normal" id="selectedLabel"
                      style="font-size:11px; color:#e3b705; opacity:0;">
                    <i class="bi bi-check2-circle me-1"></i><span id="selectedId"></span> selected
                </span>
            </div>
            <div class="richbox-body" style="max-height:calc(100vh - 380px);">
                <table class="richbox-table" id="invoiceTable">
                    <thead>
                        <tr>
                            <th style="width:38px;">#</th>
                            <th class="sortable" data-col="0" style="width:110px;">Invoice Date <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="1" style="width:145px;">Invoice ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="2" style="width:140px;">Quotation ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="3">Student Name <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="4" style="width:100px;">Term <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="5">Description <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th style="width:120px;" class="text-end">Total Paid</th>
                            <th style="width:90px;" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceBody">
                        @forelse($invoices as $i => $inv)
                        <tr class="inv-row"
                            data-id="{{ $inv->Invoice_ID }}"
                            data-date="{{ $inv->Invoice_Date ? \Carbon\Carbon::parse($inv->Invoice_Date)->format('Y-m-d') : '' }}"
                            data-quotation="{{ $inv->Quotation_ID }}"
                            data-instalment="{{ $inv->Installment_Desc ?? '' }}"
                            data-qdate=""
                            data-total="{{ number_format($inv->total_paid ?? 0, 0, ',', '.') }}"
                            data-status="{{ $inv->paid_status ? 'Paid' : 'Unpaid' }}">
                            <td class="row-num text-muted">{{ $i + 1 }}</td>
                            <td>{{ $inv->Invoice_Date ? \Carbon\Carbon::parse($inv->Invoice_Date)->format('Y-m-d') : '—' }}</td>
                            <td class="fw-semibold">{{ $inv->Invoice_ID }}</td>
                            <td class="text-muted">{{ $inv->Quotation_ID }}</td>
                            <td>{{ $inv->Student_Name ?? '—' }}</td>
                            <td class="text-center text-muted" style="white-space:normal;">{{ $inv->Acd_Year ?? '—' }}</td>
                            <td class="text-muted" style="white-space:normal; max-width:200px;">{{ $inv->Installment_Desc ?? '—' }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($inv->total_paid ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="status-badge {{ $inv->paid_status ? 'status-paid' : 'status-unpaid' }}">
                                    {{ $inv->paid_status ? 'Paid' : 'Unpaid' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No invoice records found.
                            </td>
                        </tr>
                        @endforelse
                        <tr id="emptyRow" class="d-none">
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No invoice records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="richbox-footer">
                <small class="text-muted" id="paginationInfo">
                    Showing {{ count($invoices) }} record{{ count($invoices) !== 1 ? 's' : '' }}
                </small>
                <div>{{-- {{ $invoices->links() }} --}}</div>
            </div>
        </div>
    </div>
 
 
    {{-- ════════════════════════════════════════════════════════════
         PANEL 3: PAYMENT
         Cols: Invoice ID | Total Paid (pending) | Payment Date |
               Description | Deposit ID | Card | Cek/Giro ID |
               Amount (Cash) | Amount (Card)
    ═════════════════════════════════════════════════════════════ --}}
    <div id="panel-payment" style="display:none;">
        <div class="richbox richbox-fullwidth">
            <div class="richbox-header">
                <i class="bi bi-cash-stack me-1"></i> Payment Records
                <span class="ms-2 badge bg-secondary fw-normal" id="pyRowCount" style="font-size:10px;">0</span>
                <span class="ms-auto fst-italic fw-normal" id="pySelectedLabel"
                      style="font-size:11px; color:#e3b705; opacity:0;">
                    <i class="bi bi-check2-circle me-1"></i>
                    <span id="pySelectedId"></span> selected
                </span>
            </div>
            <div class="richbox-body" style="max-height:calc(100vh - 380px);">
                <table class="richbox-table" id="paymentTable">
                    <thead>
                        <tr>
                            <th style="width:38px;">#</th>
                            <th class="sortable" data-col="0" style="width:145px;">Invoice ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th style="width:120px;" class="text-end">Total Paid</th>
                            <th class="sortable" data-col="1" style="width:115px;">Payment Date <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="2">Description <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="3" style="width:110px;">Deposit ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="4" style="width:100px;">Card <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th class="sortable" data-col="5" style="width:120px;">Cek/Giro ID <i class="bi bi-arrow-down-up sort-icon"></i></th>
                            <th style="width:115px;" class="text-end">Amount (Cash)</th>
                            <th style="width:115px;" class="text-end">Amount (Card)</th>
                        </tr>
                    </thead>
                    <tbody id="paymentBody">
                        @forelse($payments as $i => $pay)
                        <tr class="py-row"
                            data-date="{{ $pay->Payment_Date ? \Carbon\Carbon::parse($pay->Payment_Date)->format('Y-m-d') : '' }}">
                            <td class="row-num text-muted">{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $pay->Invoice_ID }}</td>
                            <td class="text-end text-muted">—</td>
                            <td>{{ $pay->Payment_Date ? \Carbon\Carbon::parse($pay->Payment_Date)->format('Y-m-d') : '—' }}</td>
                            <td class="text-muted" style="white-space:normal; max-width:200px;">{{ $pay->Installment_Desc ?? '—' }}</td>
                            <td class="text-muted">{{ $pay->Deposit_ID ?? '—' }}</td>
                            <td class="text-muted">{{ $pay->Card_Nama ?? '—' }}</td>
                            <td class="text-muted">{{ $pay->Cek_ID ?? '—' }}</td>
                            <td class="text-end">{{ $pay->Amount_Cash ? 'Rp ' . number_format($pay->Amount_Cash, 0, ',', '.') : '—' }}</td>
                            <td class="text-end">{{ $pay->Amount_Card ? 'Rp ' . number_format($pay->Amount_Card, 0, ',', '.') : '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No payment records found.
                            </td>
                        </tr>
                        @endforelse
                        <tr id="pyEmptyRow" class="d-none">
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-1"></i> No payment records found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="richbox-footer">
                <small class="text-muted" id="pyPageInfo">
                    Showing {{ count($payments) }} record{{ count($payments) !== 1 ? 's' : '' }}
                </small>
            </div>
        </div>
    </div>
 
 
    {{-- ══ ACTION BUTTONS ═══════════════════════════════════════════ --}}
 
    <div id="actions-invoice" class="d-flex gap-2 mt-3">
        <button class="btn btn-sm btn-outline-warning inv-action-btn" id="btnPreview">
            <i class="bi bi-eye me-1"></i> Preview
        </button>
        <button class="btn btn-sm btn-outline-dark inv-action-btn" id="btnEditInvoice" disabled>
            <i class="bi bi-pencil me-1"></i> Edit Invoice
        </button>
        <button class="btn btn-sm btn-outline-danger inv-action-btn" id="btnCancel" disabled>
            <i class="bi bi-x-circle me-1"></i> Cancel
        </button>
        <button class="btn btn-sm btn-outline-secondary inv-action-btn" id="btnPrintInvoice" disabled>
            <i class="bi bi-printer me-1"></i> Print Invoice
        </button>
    </div>
 
    <div id="actions-quotation" class="d-flex gap-2 mt-3" style="display:none !important;">
        <button class="btn btn-sm btn-outline-warning" id="btnQtPreview" disabled>
            <i class="bi bi-eye me-1"></i> Preview
        </button>
        <button class="btn btn-sm btn-outline-dark" id="btnQtEdit" disabled>
            <i class="bi bi-pencil me-1"></i> Edit Quotation
        </button>
        <button class="btn btn-sm btn-outline-danger" id="btnQtCancel" disabled>
            <i class="bi bi-x-circle me-1"></i> Cancel
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="btnQtPrint" disabled>
            <i class="bi bi-printer me-1"></i> Print
        </button>
    </div>
 
    <div id="actions-payment" class="d-flex gap-2 mt-3" style="display:none !important;">
        <button class="btn btn-sm btn-outline-warning" id="btnPyPreview" disabled>
            <i class="bi bi-eye me-1"></i> Preview
        </button>
        <button class="btn btn-sm btn-outline-dark" id="btnPyEdit" disabled>
            <i class="bi bi-pencil me-1"></i> Edit Payment
        </button>
        <button class="btn btn-sm btn-outline-danger" id="btnPyVoid" disabled>
            <i class="bi bi-x-circle me-1"></i> Void
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="btnPyPrint" disabled>
            <i class="bi bi-printer me-1"></i> Print Receipt
        </button>
    </div>
 
</div>{{-- /dashboard-card --}}
 
 
{{-- ══ PREVIEW MODAL (invoice only, unchanged) ═════════════════════ --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:8px; overflow:hidden;">
 
            <div class="modal-header" style="background:#111; border-bottom:2px solid #e3b705; padding:10px 16px;">
                <div>
                    <h6 class="modal-title mb-0" id="previewModalLabel"
                        style="color:#e3b705; font-weight:700; letter-spacing:.04em; font-size:13px;">
                        <i class="bi bi-receipt me-1"></i> INVOICE PREVIEW
                    </h6>
                    <small id="previewModalSubtitle" style="color:#aaa; font-size:11px;">—</small>
                </div>
                <button type="button" class="btn-close btn-close-white btn-close-sm"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
 
            <div class="modal-body p-0">
                <div id="receiptArea" style="padding:28px 36px; background:#fff;">
 
                    <div class="text-center mb-4">
                        <div style="font-size:17px; font-weight:800; color:#111; letter-spacing:.02em;">
                            THE CHAMPIONS SCHOOL
                        </div>
                        <div style="font-size:11px; color:#666; margin-top:2px;">
                            Jl. Pendidikan No. 1, Boloni &nbsp;|&nbsp; Telp. (021) 000-0000
                        </div>
                        <hr style="border-top:2px solid #111; margin:10px 0 4px;">
                        <hr style="border-top:1px solid #111; margin:0 0 12px;">
                        <div style="font-size:13px; font-weight:700; letter-spacing:.08em; color:#7e1e0d;">
                            PAYMENT RECEIPT
                        </div>
                    </div>
 
                    <div class="row g-0 mb-3" style="font-size:13px;">
                        <div class="col-6">
                            <table style="width:100%; border:none;">
                                <tr>
                                    <td style="width:110px; color:#555; padding:2px 0;">Invoice ID</td>
                                    <td style="color:#111;">: <strong id="prev_invoiceId">—</strong></td>
                                </tr>
                                <tr>
                                    <td style="color:#555; padding:2px 0;">Invoice Date</td>
                                    <td style="color:#111;">: <span id="prev_invoiceDate">—</span></td>
                                </tr>
                                <tr>
                                    <td style="color:#555; padding:2px 0;">Quotation ID</td>
                                    <td style="color:#111;">: <span id="prev_quotationId">—</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table style="width:100%; border:none;">
                                <tr>
                                    <td style="width:120px; color:#555; padding:2px 0;">Instalment</td>
                                    <td style="color:#111;">: <span id="prev_instalment">—</span></td>
                                </tr>
                                <tr>
                                    <td style="color:#555; padding:2px 0;">Quotation Date</td>
                                    <td style="color:#111;">: <span id="prev_qdate">—</span></td>
                                </tr>
                                <tr>
                                    <td style="color:#555; padding:2px 0;">Status</td>
                                    <td>: <span id="prev_status" class="status-badge status-paid">—</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
 
                    <hr style="border-top:1px dashed #bbb; margin:12px 0;">
 
                    <div style="font-size:12px; font-weight:700; text-transform:uppercase;
                                letter-spacing:.06em; color:#555; margin-bottom:8px;">
                        Payment Detail
                    </div>
                    <table style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead>
                            <tr style="background:#f4f4f4; border-bottom:2px solid #ddd;">
                                <th style="padding:5px 8px; text-align:left; font-size:12px; color:#444;">Description</th>
                                <th style="padding:5px 8px; text-align:right; font-size:12px; color:#444; width:130px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2" style="padding:10px 8px; text-align:center; color:#aaa; font-size:12px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Detailed payment breakdown will be loaded here.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="border-top:2px solid #ddd;">
                                <td style="padding:6px 8px; font-weight:700; font-size:13px;">Total Paid</td>
                                <td style="padding:6px 8px; text-align:right; font-weight:700;
                                           font-size:14px; color:#7e1e0d;" id="prev_total">Rp —</td>
                            </tr>
                        </tfoot>
                    </table>
 
                    <hr style="border-top:1px dashed #bbb; margin:16px 0 12px;">
 
                    <div class="row g-0" style="font-size:12px; color:#555;">
                        <div class="col-6 text-center">
                            <div>Prepared by,</div>
                            <div style="height:48px;"></div>
                            <div style="border-top:1px solid #999; width:130px; margin:0 auto; padding-top:4px;">
                                Finance Staff
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div>Received by,</div>
                            <div style="height:48px;"></div>
                            <div style="border-top:1px solid #999; width:130px; margin:0 auto; padding-top:4px;">
                                Parent / Guardian
                            </div>
                        </div>
                    </div>
 
                    <div class="text-center mt-4" style="font-size:10px; color:#aaa;">
                        This receipt is computer generated and is valid without signature when printed.
                    </div>
 
                </div>
            </div>
 
            <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid #e0e0e0; padding:8px 16px;">
                <small class="text-muted me-auto" style="font-size:11px;">
                    <i class="bi bi-info-circle me-1"></i> Payment breakdown detail will be available once DB is connected.
                </small>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-dark" id="btnPrintFromModal">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
 
        </div>
    </div>
</div>
 
@endsection