<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Bahan\BahanService;
use Illuminate\Http\Request;

class AdminBahanController extends Controller
{
    private $bahanService;

    public function __construct(BahanService $bahanService)
    {
        $this->bahanService = $bahanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bahan.index', [
            'title' => 'Data Bahan'
        ]);
    }

    public function data1()
    {
        $result = $this->bahanService->getData();

        return datatables($result)
            ->addIndexColumn()
            ->editColumn('aksi', fn($q) => $this->renderActionButtons($q))
            ->escapeColumns([])
            ->make(true);
    }

    public function data()
    {
        $result = $this->bahanService->getData();

        return datatables($result)
            ->addIndexColumn()
            ->addColumn('variants', function ($row) {
                if ($row->variants->isEmpty()) {
                    return '<span class="badge bg-secondary">Tidak ada varian</span>';
                }

                $table = '<table class="table table-sm table-bordered mb-0" style="font-size: 12px;">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">Size</th>
                        <th class="text-center">Harga</th>
                        <th width="20%" class="text-center">Min Qty Diskon</th>
                        <th class="text-center">Diskon (%)</th>
                        <th class="text-center">Harga Diskon</th>
                    </tr>
                </thead>
                <tbody>';

                foreach ($row->variants as $variant) {
                    $price = $variant->price;
                    $discount = $variant->discount_percent;
                    $priceAfterDiscount = $price - ($price * $discount / 100);

                    $table .= '<tr>
                    <td class="text-center">' . e($variant->size) . '</td>
                    <td class="text-right">' . format_uang($price) . '</td>
                    <td class="text-center">' . e($variant->min_quantity_discount) . '</td>
                    <td class="text-center">' . $discount . '%</td>
                    <td class="text-right">' . format_uang($priceAfterDiscount) . '</td>
                  </tr>';
                }

                $table .= '</tbody></table>';

                return $table;
            })


            ->editColumn('aksi', fn($q) => $this->renderActionButtons($q))
            ->escapeColumns([]) // agar HTML tidak di-escape
            ->make(true);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $result = $this->bahanService->store($request->all());

        if ($result['status'] === 'success') {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'errors'  => $result['errors'],
            'message' => $result['message'],
        ], 422);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $result = $this->bahanService->show($id);
        return response()->json(['data' => $result]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $result = $this->bahanService->update($request->all(), $id);

        if ($result['status'] === 'success') {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
            ], 200);
        }

        return response()->json([
            'success' => false,
            'errors'  => $result['errors'],
            'message' => $result['message'],
        ], 422);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $result = $this->bahanService->destroy($id);

        return response()->json([
            'message' => $result['message'],
        ]);
    }

    /**
     * Render aksi buttons
     */
    protected function renderActionButtons($q)
    {
        return '
                <button onclick="editForm(`' . route('admin.bahan.show', $q->id) . '`)" class="btn btn-xs btn-primary mr-1"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('admin.bahan.destroy', $q->id) . '`, `' . $q->nama_bahan . '`)" class="btn btn-xs btn-danger mr-1"><i class="fas fa-trash-alt"></i></button>
            ';
    }
}
