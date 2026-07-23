@extends('layouts.app')

@section('title', 'Finance')

@section('content')

<div class="dashboard-card">

    <h3>Finance Module</h3>

    <hr>

    {{-- ── Top bar: title + New Payment ──────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="mb-0 fw-bold">Paid Invoices</h5>
            <small class="text-muted">All settled invoice records</small>
        </div>
        <a href="{{ route('finance.create') }}" class="btn btn-sm btn-dark">
            <i class="bi bi-plus-lg me-1"></i> New Payment
        </a>
    </div>
    
    {{-- ── Search & Filter Bar ────────────────────────────────────── --}}
    <div class="d-flex gap-2 mb-2">
        <div class="input-group input-group-sm" style="max-width:320px;">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="searchInput" class="form-control border-start-0"
                placeholder="Search invoice, quotation, instalment...">
        </div>
        <input type="date" id="filterFrom" class="form-control form-control-sm"
            style="max-width:145px;" title="Invoice date from">
        <input type="date" id="filterTo"   class="form-control form-control-sm"
            style="max-width:145px;" title="Invoice date to">
        <button class="btn btn-sm btn-outline-secondary" id="clearFilter" title="Clear filters">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    
    {{-- ══════════════════════════════════════════════════════════════
        RICHBOX — Invoice Records
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="richbox richbox-fullwidth" id="invoiceBox">
    
        <div class="richbox-header">
            <i class="bi bi-receipt me-1"></i> Invoice Records
            <span class="ms-2 badge bg-secondary fw-normal" id="rowCount" style="font-size:10px;">0</span>
        </div>
    
        <div class="richbox-body" style="max-height: calc(100vh - 310px);">
            <table class="richbox-table" id="invoiceTable">
                <thead>
                    <tr>
                        <th style="width:38px;">#</th>
                        <th class="sortable" data-col="0" style="width:145px;">
                            Invoice ID <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th class="sortable" data-col="1" style="width:110px;">
                            Invoice Date <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th class="sortable" data-col="2" style="width:145px;">
                            Quotation ID <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th style="width:110px;" class="text-end">Total Paid</th>
                        <th class="sortable" data-col="3">
                            Instalment Name <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th class="sortable" data-col="4" style="width:110px;">
                            Quotation Date <i class="bi bi-arrow-down-up sort-icon"></i>
                        </th>
                        <th style="width:90px;" class="text-center">Paid Status</th>
                    </tr>
                </thead>
                <tbody id="invoiceBody">
    
                    {{-- ── Live data: uncomment @foreach once DB is wired ──
                    @foreach($invoices as $i => $inv)
                    <tr class="inv-row" data-id="{{ $inv->Invoice_ID }}">
                        <td class="row-num text-muted">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $inv->Invoice_ID }}</td>
                        <td>{{ \Carbon\Carbon::parse($inv->Invoice_Date)->format('Y-m-d') }}</td>
                        <td class="text-muted">{{ $inv->Quotation_ID }}</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($inv->Total_Paid, 0, ',', '.') }}</td>
                        <td>{{ $inv->Installment_Name }}</td>
                        <td>{{ \Carbon\Carbon::parse($inv->Quotation_Date)->format('Y-m-d') }}</td>
                        <td class="text-center">
                            @if($inv->Paid_Status)
                                <span class="status-badge status-paid">Paid</span>
                            @else
                                <span class="status-badge status-unpaid">Unpaid</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    ── --}}
    
                    {{-- ── Dummy rows (remove once DB is wired up) ── --}}
                    <tr class="inv-row" data-id="INV-2024-0001">
                        <td class="row-num text-muted">1</td>
                        <td class="fw-semibold">INV-2024-0001</td>
                        <td>2024-01-15</td>
                        <td class="text-muted">QT-2024-001</td>
                        <td class="text-end fw-semibold">Rp 1.500.000</td>
                        <td>SPP Semester 1</td>
                        <td>2024-01-10</td>
                        <td class="text-center"><span class="status-badge status-paid">Paid</span></td>
                    </tr>
                    <tr class="inv-row" data-id="INV-2024-0002">
                        <td class="row-num text-muted">2</td>
                        <td class="fw-semibold">INV-2024-0002</td>
                        <td>2024-02-15</td>
                        <td class="text-muted">QT-2024-001</td>
                        <td class="text-end fw-semibold">Rp 300.000</td>
                        <td>SPP Semester 1</td>
                        <td>2024-01-10</td>
                        <td class="text-center"><span class="status-badge status-paid">Paid</span></td>
                    </tr>
                    <tr class="inv-row" data-id="INV-2024-0003">
                        <td class="row-num text-muted">3</td>
                        <td class="fw-semibold">INV-2024-0003</td>
                        <td>2024-03-01</td>
                        <td class="text-muted">QT-2024-002</td>
                        <td class="text-end fw-semibold">Rp 450.000</td>
                        <td>Biaya Buku Paket</td>
                        <td>2024-02-20</td>
                        <td class="text-center"><span class="status-badge status-paid">Paid</span></td>
                    </tr>
                    <tr class="inv-row" data-id="INV-2024-0004">
                        <td class="row-num text-muted">4</td>
                        <td class="fw-semibold">INV-2024-0004</td>
                        <td>2024-04-10</td>
                        <td class="text-muted">QT-2024-003</td>
                        <td class="text-end fw-semibold">Rp 750.000</td>
                        <td>UTS Semester 1</td>
                        <td>2024-03-25</td>
                        <td class="text-center"><span class="status-badge status-paid">Paid</span></td>
                    </tr>
                    <tr class="inv-row" data-id="INV-2024-0005">
                        <td class="row-num text-muted">5</td>
                        <td class="fw-semibold">INV-2024-0005</td>
                        <td>2024-05-05</td>
                        <td class="text-muted">QT-2024-004</td>
                        <td class="text-end fw-semibold">Rp 600.000</td>
                        <td>Biaya Seragam</td>
                        <td>2024-04-28</td>
                        <td class="text-center"><span class="status-badge status-paid">Paid</span></td>
                    </tr>
    
                    {{-- Empty state --}}
                    <tr id="emptyRow" class="d-none">
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox me-1"></i> No paid invoices found.
                        </td>
                    </tr>
    
                </tbody>
            </table>
        </div>
    
        {{-- ── Richbox footer: row info + pagination ──────────────── --}}
        <div class="richbox-footer">
            <small class="text-muted" id="paginationInfo">Showing all records</small>
            <div>
                {{-- {{ $invoices->links() }} --}}
            </div>
        </div>
    
    </div>
    
    {{-- ══════════════════════════════════════════════════════════════
        Action Buttons (below richbox)
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="d-flex gap-2 mt-3">
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
</div>

@endsection