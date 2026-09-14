<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class FinanceController extends Controller
{
    public function index()
    {
        // ── QUOTATION ─────────────────────────────────────────────────
        $quotations = DB::table('Subject_Fee as sf')
            ->join('Student as s',
                's.Student_ID', '=', 'sf.Student_Student_ID')
            ->join('Installment_Offer as io',
                'io.Installment_ID', '=', 'sf.Installment_Offer_Installment_ID')
            ->leftJoin('Class as cl', function ($join) {
                $join->on('cl.Student_ID',           '=', 'sf.Student_Student_ID')
                     ->on('cl.Academic_Year_idYear',  '=', 'io.Class_Acc_Year')
                     ->on('cl.Grade',                 '=', 'io.Class_Grade');
            })
            ->leftJoin('Grade as g',
                'g.Grade_ID', '=', 'cl.Grade')
            ->select([
                'sf.Quotation_ID',
                'sf.Quotation_Date',
                's.Student_ID',
                's.Student_Name',
                'g.Grade_Name',
                'io.Installment_Desc',
            ])
            ->orderByDesc('sf.Quotation_Date')
            ->distinct()
            ->get();
 
        // ── INVOICE ───────────────────────────────────────────────────
        $invoices = DB::table('Invoice as inv')
            ->join('Subject_Fee as sf',
                'sf.Quotation_ID', '=', DB::raw('`inv`.`Subject_Fee_Quotation_ID`'))
            ->join('Student as s',
                's.Student_ID', '=', 'sf.Student_Student_ID')
            ->join('Installment_Offer as io',
                'io.Installment_ID', '=', 'sf.Installment_Offer_Installment_ID')
            ->leftJoin('Class as cl',
                'cl.Student_ID', '=', 's.Student_ID')
            ->leftJoin('Academic_Year as ay',
                'ay.idYear', '=', 'cl.Academic_Year_idYear')
            ->select([
                'inv.Invoice_ID',
                'inv.Invoice_Date',
                'inv.paid_status',
                'inv.total_paid',
                DB::raw('`inv`.`Subject_Fee_Quotation_ID` as Quotation_ID'),
                's.Student_Name',
                'io.Installment_Desc',
                DB::raw("GROUP_CONCAT(DISTINCT ay.Acd_Year ORDER BY ay.Acd_Year SEPARATOR ', ') as Acd_Year"),
            ])
            ->groupBy([
                'inv.Invoice_ID',
                'inv.Invoice_Date',
                'inv.paid_status',
                'inv.total_paid',
                DB::raw('`inv`.`Subject_Fee_Quotation_ID`'),
                's.Student_Name',
                'io.Installment_Desc',
            ])
            ->orderByDesc('inv.Invoice_Date')
            ->get();
 
        // ── PAYMENT ───────────────────────────────────────────────────
        $payments = DB::table('Payment as pay')
            ->join('Invoice as inv',
                'inv.Invoice_ID', '=', 'pay.Invoice_ID')
            ->join('Subject_Fee as sf',
                'sf.Quotation_ID', '=', DB::raw('`inv`.`Subject_Fee_Quotation_ID`'))
            ->join('Installment_Offer as io',
                'io.Installment_ID', '=', 'sf.Installment_Offer_Installment_ID')
            ->leftJoin('Deposit as dep',
                'dep.Deposit_ID', '=', 'pay.Deposit_ID')
            ->leftJoin('Card as card',
                'card.Card_ID', '=', 'pay.Card_ID')
            ->leftJoin('Cek_Giro as cg',
                'cg.Cek_ID', '=', 'pay.Cek_Giro_Cek_ID')
            ->select([
                'pay.Invoice_ID',
                'pay.Payment_Date',
                'pay.Amount_Cash',
                'pay.Amount_Card',
                'pay.Deposit_ID',
                'pay.Card_ID',
                'pay.Cek_Giro_Cek_ID',
                'io.Installment_Desc',
                'card.Card_Nama',
                'cg.Cek_ID',
            ])
            ->orderByDesc('pay.Payment_Date')
            ->get();
 
        return view('finance.index', compact('quotations', 'invoices', 'payments'));
    }
 
    public function cash()
    {
        return view('finance.create', [
            'module' => 'finance',
        ]);
    }
 
    public function payment()
    {
        return view('finance.payment', [
            'module' => 'finance',
        ]);
    }
}