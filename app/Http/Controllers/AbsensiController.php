<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function show()
    {
        return view('pages.absensi');
    }

    public function generateQRCode()
    {
        // Mendapatkan waktu saat ini
        $currentTime = Carbon::now();

        // Menambahkan 5 menit ke waktu saat ini
        $expiryTime = $currentTime->addMinutes(5);

        // Membuat data unik berdasarkan waktu untuk QR code
        $qrData = "Absensi-" . $currentTime->format('YmdHis');

        // Membuat QR code dengan data unik
        $qrCode = QrCode::size(300)->generate($qrData);

        // Menyimpan data unik dan waktu kedaluwarsa pada sesi
        session(['qr_data' => $qrData, 'expiry_time' => $expiryTime, 'qr_code' => $qrCode]);

        return view('pages.qrcode', compact('qrData', 'qrCode'));
    }


}