<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrcodeProcess;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ScanController extends Controller
{
    public function previewScan(Request $request)
    {
        try {
            $member = $request->user('member');

            if (!$member) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $validator = Validator::make($request->all(), [
                'qrcode' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Validasi qrcode di tabel branches
            $branch = Branch::where('qrcode', $request->qrcode)->first();

            if (!$branch) {
                return response()->json([
                    'message' => 'QR Code tidak valid untuk cabang yang dipilih.'
                ], 422);
            }

            return response()->json([
                'message' => 'Scan berhasil.',
                'data' => [
                    'branch' => $branch->branch_name,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'member_name' => $member->name
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses scan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function scan(Request $request)
    {
        try {
            $member = $request->user('member');

            if (!$member) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $validator = Validator::make($request->all(), [
                'qrcode' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Validasi qrcode di tabel branches
            $branch = Branch::where('qrcode', $request->qrcode)->first();

            if (!$branch) {
                return response()->json([
                    'message' => 'QR Code tidak valid untuk cabang yang dipilih.'
                ], 422);
            }

            // Simpan proses QR code
            $process = QrcodeProcess::create([
                'branch_id' => $branch->id,
                'member_id' => $member->id,
                'qrcode' => $request->qrcode,
                'status' => 'pending',
                'process_time' => now()->addMinutes(5)
            ]);

            return response()->json([
                'message' => 'Scan berhasil diproses.',
                'data' => $process
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses scan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function transaction(Request $request)
    {
        try {
            // Validasi input dari request
            $validator = Validator::make($request->all(), [
                'member_id' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0',
                'service_type' => 'required|string|max:255',
                'payment_method' => 'required|string|max:50',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Ambil satu proses QR code yang statusnya pending
            $qrcodeProcess = QrcodeProcess::where('member_id', $request->member_id)
                ->where('status', 'pending')
                ->first();

            if (!$qrcodeProcess) {
                return response()->json(['message' => 'No pending QR code process found.'], 404);
            }

            // Ambil data dari request
            $price = $request->price;  // Harga layanan dari request
            $discount = $request->discount;  // Diskon dari request
            $finalPrice = $price - $discount;  // Hitung harga final
            $pointsEarned = $finalPrice * 0.10; // 10% dari harga final

            // Simpan transaksi ke tabel transactions
            $transaction = Transaction::create([
                'member_id' => $request->member_id,
                'branch_id' => $qrcodeProcess->branch_id,
                'transaction_date' => now(),
                'service_type' => $request->service_type,  // Tipe layanan dari request
                'price' => $price,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'points_earned' => $pointsEarned,
                'payment_method' => $request->payment_method,  // Metode pembayaran dari request
                'notes' => $request->notes,  // Catatan transaksi dari request (optional)
            ]);

            // Update status QR Code menjadi 'processed'
            $qrcodeProcess->update(['status' => 'processed']);

            return response()->json([
                'message' => 'Transaksi berhasil diproses.',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
