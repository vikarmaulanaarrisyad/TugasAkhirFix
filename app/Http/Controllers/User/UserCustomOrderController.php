<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\JenisSablon;
use App\Models\CustomOrder;
use App\Models\Ongkir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserCustomOrderController extends Controller
{
    public function index()
    {
        return view('frontend.user.customorder.index',[
            'title' => 'Custom Kaos'
        ]);
    }

    public function store(Request $request)
    {

        // Validasi data
        $rules = [
            'name' => 'required|string|max:255',
            'file_design' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'design_description' => 'nullable|string',
            'fabric_type' => 'nullable|string',
            'size' => 'nullable|string',
            'qty' => 'required|integer|min:1',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => 'Data gagal disimpan']);
        }

        // Ambil semua data kecuali file desain
        $data = $request->except('file_design');

        // Upload file desain jika ada
        if ($request->hasFile('file_design')) {
            $data['file_design'] = upload('customorder', $request->file('file_design'), 'file_design');
        } else {
            $data['file_design'] = 'design.jpg'; // Default jika tidak ada file yang diupload
        }

        // Tambahkan user_id ke dalam data
        $data['user_id'] = auth()->id();

        // Ambil price bahan
        $bahan = Bahan::where('nama_bahan', $request->fabric_type)->first();
        $jenissablon = JenisSablon::where('nama_sablon', $request->jenis_sablon)->first();

        // dd($request->all());

        // Hitung harga total berdasarkan jumlah dan harga per item
        // $pricePerItem = $request->qty * ;  //50000; // Contoh harga per item/bahan
        // $totalPrice = $bahan->price * $request->qty; //$request->qty * $pricePerItem;
        $hargaBahan = $bahan ? $bahan->price : 0;
        $hargaSablon = $jenissablon ? $jenissablon->harga : 0;

        $totalPrice = ($hargaBahan + $hargaSablon) * $request->qty;

        // Hitung pembayaran awal (DP) dan sisa pembayaran
        $dpPaid = 0; //$totalPrice * 0.5; // 50% dari total harga


        // Tambahkan data perhitungan ke dalam array data
        $data['price'] = $hargaBahan;
        $data['sablon_price'] = $hargaSablon;
        $data['jenis_sablon'] = $jenissablon->nama_sablon;
        $data['total_price'] = $totalPrice;
        $data['dp_paid'] = $dpPaid;
        $data['remaining_payment'] = $totalPrice + $request->courier;
        $data['status'] = 'Pending';
        $data['address'] = $request->address;
        $data['province_id'] = $request->province_id;
        $data['regency_id'] = 1;
        $data['district_id'] = 1;
        $data['village_id'] = 1;
        $data['ongkir'] = $request->courier;
        $data['courir'] = $request->courier_service;
        $data['position'] = $request->position;
        $data['order_date'] = now();
        $data['completion_date'] = now()->addWeeks(1); // Estimasi 2 minggu selesai

        // Simpan data ke database
        CustomOrder::create($data);

        // Redirect dengan pesan sukses
        return response()->json(['success' => 'Data Berhasil Disubmit']);
    }

    public function history()
    {
        
        $userId = Auth::user()->id;
        $title = 'History Custom Order';
        $customOrders = CustomOrder::where('user_id', $userId)->orderBy('id', 'DESC')->get();

        return view('frontend.user.customorder.history', compact('customOrders', 'title'));
    }

    public function detail($id)
    {

        $title = 'Detail Custom Order';
        $customOrder = CustomOrder::findOrfail($id);

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $customOrder->remaining_payment
            ),

            'customer_details' => array(
                'first_name' => $customOrder->name,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('frontend.user.customorder.detail', compact('customOrder', 'snapToken', 'title'));
    }

    public function customeOrderStore(Request $request)
    {
        $customId = $request->custom_order_id;
        $data = json_decode($request->get('json'));

        CustomOrder::findOrfail($customId)->update([
            'status' => 'Success',
            'payment_type' => $data->payment_type,
            'transaction_id' => $data->transaction_id
        ]);

        $notification = [
            'message' => 'Pembayaran Success',
            'alert-type' => 'success',
        ];

        return redirect()->route('user.customorder.history')->with($notification);
    }
}
