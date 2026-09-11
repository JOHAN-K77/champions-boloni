<div class="modal fade" id="quotationPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:8px; overflow:hidden;">

            <div class="modal-header" style="background:#111; border-bottom:2px solid #e3b705; padding:10px 16px;">
                <div>
                    <h6 class="modal-title mb-0"
                        style="color:#e3b705; font-weight:700; letter-spacing:.04em; font-size:13px;">
                        <i class="bi bi-file-text me-1"></i> QUOTATION PREVIEW
                    </h6>
                    <small id="qt_prev_subtitle" style="color:#aaa; font-size:11px;">—</small>
                </div>
                <button type="button" class="btn-close btn-close-white btn-close-sm"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div id="qt_receiptArea" style="padding:28px 36px; background:#fff;">

                    {{-- School letterhead --}}
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
                            SUBJECT FEE QUOTATION
                        </div>
                    </div>

                    {{-- Header fields: two rows, two cols each --}}
                    <div style="font-size:13px; margin-bottom:16px;">
                        <div class="row g-0 mb-1">
                            <div class="col-6">
                                <table style="width:100%; border:none;">
                                    <tr>
                                        <td style="width:120px; color:#555; padding:2px 0;">Quotation ID</td>
                                        <td style="color:#111;">: <strong id="qt_prev_id">—</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="color:#555; padding:2px 0;">Installment ID</td>
                                        <td style="color:#111;">: <span id="qt_prev_installment_id">—</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-6">
                                <table style="width:100%; border:none;">
                                    <tr>
                                        <td style="width:120px; color:#555; padding:2px 0;">Quotation Date</td>
                                        <td style="color:#111;">: <span id="qt_prev_date">—</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-6">
                                <table style="width:100%; border:none;">
                                    <tr>
                                        <td style="width:120px; color:#555; padding:2px 0;">Student Name</td>
                                        <td style="color:#111;">: <span id="qt_prev_student">—</span></td>
                                    </tr>
                                    <tr>
                                        <td style="color:#555; padding:2px 0;">Student ID</td>
                                        <td style="color:#111;">: <span id="qt_prev_student_id">—</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Subject Fee table --}}
                    <div style="font-size:12px; font-weight:700; text-transform:uppercase;
                                letter-spacing:.06em; color:#555; margin-bottom:8px;">
                        Subject Fee
                    </div>
                    <table style="width:100%; border-collapse:collapse; font-size:13px;"
                           id="qt_prev_table">
                        <thead>
                            <tr style="background:#f4f4f4; border-bottom:2px solid #ddd;">
                                <th style="padding:6px 10px; text-align:left; font-size:12px; color:#444;">Subject</th>
                                <th style="padding:6px 10px; text-align:right; font-size:12px; color:#444; width:140px;">Fee</th>
                                <th style="padding:6px 10px; text-align:left; font-size:12px; color:#444; width:130px;">Installment</th>
                                <th style="padding:6px 10px; text-align:right; font-size:12px; color:#444; width:130px;">Pay</th>
                            </tr>
                        </thead>
                        <tbody id="qt_prev_tbody">
                            <tr id="qt_prev_empty">
                                <td colspan="4" style="padding:12px; text-align:center; color:#aaa; font-size:12px;">
                                    <i class="bi bi-info-circle me-1"></i>
                                    No purchase items linked to this quotation.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot id="qt_prev_tfoot" style="display:none;">
                            <tr style="border-top:2px solid #ddd; background:#fafafa;">
                                <td colspan="3" style="padding:7px 10px; font-weight:700; font-size:13px;">Grand Total</td>
                                <td style="padding:7px 10px; text-align:right; font-weight:700;
                                           font-size:14px; color:#7e1e0d;" id="qt_prev_grand_total">—</td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Note --}}
                    <div style="margin-top:18px; font-size:13px;">
                        <span style="color:#555; font-weight:600;">Note:</span>
                        <span id="qt_prev_note" style="color:#444; margin-left:6px;">—</span>
                    </div>

                    {{-- Signatures --}}
                    <hr style="border-top:1px dashed #bbb; margin:20px 0 14px;">
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
                        This document is computer generated and valid without signature when printed.
                    </div>

                </div>{{-- /qt_receiptArea --}}
            </div>

            <div class="modal-footer" style="background:#f8f9fa; border-top:1px solid #e0e0e0; padding:8px 16px;">
                <small class="text-muted me-auto" style="font-size:11px;">
                    <i class="bi bi-info-circle me-1"></i>
                    <span id="qt_prev_footer_note">—</span>
                </small>
                <button type="button" class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-dark" id="btnQtPrintFromModal">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>

        </div>
    </div>
</div>