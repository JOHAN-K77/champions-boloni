<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index()
    {
        // ── Fetch all paid invoices, joined to Subject_Fee and Installment_Offer ──
        // Uncomment once the DB tables are migrated and seeded:
        //
        // $invoices = DB::table('Invoice as inv')
        //     ->join('Subject_Fee as sf',  'inv.Subject_Fee_Quotation_ID', '=', 'sf.Quotation_ID')
        //     ->join('Installment_Offer as io', 'sf.Installment_Offer_Installment_ID', '=', 'io.Installment_ID')
        //     ->where('inv.Paid_Status', 1)
        //     ->select([
        //         'inv.Invoice_ID',
        //         'inv.Invoice_Date',
        //         'inv.Paid_Status',
        //         'sf.Quotation_ID',
        //         'sf.Quotation_Date',
        //         'io.Installment_Name',
        //     ])
        //     ->orderByDesc('inv.Invoice_Date')
        //     ->paginate(25);
        //
        // return view('finance.index', [
        //     'module'   => null,
        //     'invoices' => $invoices,
        // ]);
 
        // ── Placeholder until DB is ready ──
        return view('finance.index', [
            'module' => null
        ]);
    }

    public function cash()
    {
        return view('finance.create', [
            'module' => 'finance'
        ]);
    }

    public function payment()
    {
        return view('finance.payment', [
            'module' => 'finance'
        ]);
    }
}