<div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:8px; overflow:hidden;">
            <div class="modal-header" style="background:#111; border-bottom:2px solid #e3b705; padding:10px 16px;">
                <div>
                    <h6 class="modal-title mb-0" id="invoicePreviewModalLabel"
                        style="color:#e3b705; font-weight:700; letter-spacing:.04em; font-size:13px;">
                        <i class="bi bi-receipt me-1"></i> INVOICE PREVIEW
                    </h6>
                    <small id="inv_prev_subtitle" style="color:#aaa; font-size:11px;">—</small>
                </div>
                <button type="button" class="btn-close btn-close-white btn-close-sm"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="receiptArea" style="padding:28px 36px; background:#fff;">
                    <div class="text-center mb-4">
                        <div style="font-size:17px; font-weight:800; color:#111; letter-spacing:.02em;">THE CHAMPIONS SCHOOL</div>
                        <div style="font-size:11px; color:#666; margin-top:2px;">Jl. Pendidikan No. 1, Boloni &nbsp;|&nbsp; Telp. (021) 000-0000</div>
                        <hr style="border-top:2px solid #111; margin:10px 0 4px;">
                        <hr style="border-top:1px solid #111; margin:0 0 12px;">
                        <div style="font-size:13px; font-weight:700; letter-spacing:.08em; color:#7e1e0d;">PAYMENT RECEIPT</div>
                    </div>
                    <div class="row g-0 mb-3" style="font-size:13px;">
                        <div class="col-6">
                            <table style="width:100%; border:none;">
                                <tr><td style="width:110px; color:#555; padding:2px 0;">Invoice ID</td><td style="color:#111;">: <strong id="inv_prev_invoiceId">—</strong></td></tr>
                                <tr><td style="color:#555; padding:2px 0;">Invoice Date</td><td style="color:#111;">: <span id="inv_prev_invoiceDate">—</span></td></tr>
                                <tr><td style="color:#555; padding:2px 0;">Quotation ID</td><td style="color:#111;">: <span id="inv_prev_quotationId">—</span></td></tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table style="width:100%; border:none;">
                                <tr><td style="width:120px; color:#555; padding:2px 0;">Instalment</td><td style="color:#111;">: <span id="inv_prev_instalment">—</span></td></tr>
                                <tr><td style="color:#555; padding:2px 0;">Quotation Date</td><td style="color:#111;">: <span id="inv_prev_qdate">—</span></td></tr>
                                <tr><td style="color:#555; padding:2px 0;">Status</td><td>: <span id="inv_prev_status" class="status-badge status-paid">—</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <hr style="border-top:1px dashed #bbb; margin:12px 0;">
                    <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#555; margin-bottom:8px;">Payment Detail</div>
                    <table style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead><tr style="background:#f4f4f4; border-bottom:2px solid #ddd;">
                            <th style="padding:5px 8px; text-align:left; font-size:12px; color:#444;">Description</th>
                            <th style="padding:5px 8px; text-align:right; font-size:12px; color:#444; width:130px;">Amount</th>
                        </tr></thead>
                        <tbody><tr><td colspan="2" style="padding:10px 8px; text-align:center; color:#aaa; font-size:12px;"><i class="bi bi-info-circle me-1"></i>Detailed payment breakdown will be loaded here.</td></tr></tbody>
                        <tfoot><tr style="border-top:2px solid #ddd;">
                            <td style="padding:6px 8px; font-weight:700; font-size:13px;">Total Paid</td>
                            <td style="padding:6px 8px; text-align:right; font-weight:700; font-size:14px; color:#7e1e0d;" id="inv_prev_total">Rp —</td>
                        </tr></tfoot>
                    </table>
                    <hr style="border-top:1px dashed #bbb; margin:16px 0 12px;">
                    <div class="row g-0" style="font-size:12px; color:#555;">
                        <div class="col-6 text-center"><div>Prepared by,</div><div style="height:48px;"></div><div style="border-top:1px solid #999; width:130px; margin:0 auto; padding-top:4px;">Finance Staff</div></div>
                        <div class="col-6 text-center"><div>Received by,</div><div style="height:48px;"></div><div style="border-top:1px solid #999; width:130px; margin:0 auto; padding-top:4px;">Parent / Guardian</div></div>
                    </div>
                    <div class="text-center mt-4" style="font-size:10px; color:#aaa;">This receipt is computer generated and is valid without signature when printed.</div>
                </div>
            </div>
            <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid #e0e0e0; padding:8px 16px;">
                <small class="text-muted me-auto" style="font-size:11px;"><i class="bi bi-info-circle me-1"></i> Payment breakdown detail will be available once DB is connected.</small>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-dark" id="btnPrintFromModal"><i class="bi bi-printer me-1"></i> Print</button>
            </div>
        </div>
    </div>
</div>