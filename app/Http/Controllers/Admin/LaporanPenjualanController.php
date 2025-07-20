<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use DataTables;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view('admin.laporanpenjualan.index', [
            'title' => 'Laporan Penjualan',
        ]);
    }

    public function data(Request $request)
    {
        $query = OrderItem::with('product', 'order');

        $query->whereHas('order', function ($q) use ($request) {
            $dateFormat = '%d %M %Y';
            if ($request->filled('start_date')) {
                $q->whereRaw(DB::raw("STR_TO_DATE(order_date, '$dateFormat') >= ?"), [$request->start_date]);
            }
            if ($request->filled('end_date')) {
                $q->whereRaw(DB::raw("STR_TO_DATE(order_date, '$dateFormat') <= ?"), [$request->end_date]);
            }
        });

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('product_name', function ($item) {
                return optional($item->product)->product_name ?? '-';
            })
            ->editColumn('price', function ($item) {
                return 'Rp' . number_format($item->price, 0, ',', '.');
            })
            ->addColumn('total', function ($item) {
                return 'Rp' . number_format($item->price * $item->qty, 0, ',', '.');
            })
            // --- PENAMBAHAN KOLOM BARU ---
            ->addColumn('ongkir', function ($item) {
                $ongkir = optional($item->order)->ongkir ?? 0;
                return 'Rp' . number_format($ongkir, 0, ',', '.');
            })
            ->addColumn('total_after_shipping', function ($item) {
                $totalAmount = optional($item->order)->amount ?? 0;
                return 'Rp' . number_format($totalAmount, 0, ',', '.');
            })
            // -----------------------------
            ->editColumn('order_date', function ($item) {
                // Cek jika relasi dan tanggalnya ada, lalu format
                if (optional($item->order)->order_date) {
                    // Ambil objek Carbon dan format ke 'hari-bulan-tahun'
                    return $item->order->order_date->format('d-m-Y');
                }
                return '-';
            })
            // -----------------------
            ->rawColumns(['total'])
            ->make(true);
    }

    // Method export tidak berubah
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $fileName = 'laporan-penjualan-dari-' . $startDate . '-sampai-' . $endDate . '.xlsx';
        return Excel::download(new LaporanPenjualanExport($startDate, $endDate), $fileName);
    }
}