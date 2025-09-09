<?php

namespace App\Http\Controllers;

use App\Models\WhatsappNumber;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    /**
     * Display WhatsApp management dashboard
     */
    public function index()
    {
        $whatsappNumbers = WhatsappNumber::orderBy('created_at', 'desc')->get();

        return view('whatsapp.index', compact('whatsappNumbers'));
    }

    /**
     * Add new WhatsApp number
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|unique:whatsapp_numbers,number',
        ]);

        $whatsappNumber = WhatsappNumber::create([
            'number' => $validated['number'],
            'status' => 'scanning',
            'qr_code' => WhatsappService::generateQRCode($validated['number']),
        ]);

        return redirect()->back()->with('success', 'Nombor WhatsApp berjaya ditambah. Sila scan QR code untuk sambung.');
    }

    /**
     * Update WhatsApp number status
     */
    public function updateStatus(Request $request, WhatsappNumber $whatsappNumber)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,scanning',
        ]);

        $whatsappNumber->update([
            'status' => $validated['status'],
            'last_connected_at' => $validated['status'] === 'active' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Status WhatsApp berjaya dikemaskini.');
    }

    /**
     * Remove WhatsApp number
     */
    public function destroy(WhatsappNumber $whatsappNumber)
    {
        $whatsappNumber->delete();

        return redirect()->back()->with('success', 'Nombor WhatsApp berjaya dipadam.');
    }

    /**
     * Generate new QR code for scanning
     */
    public function generateQR(WhatsappNumber $whatsappNumber)
    {
        $qrCode = WhatsappService::generateQRCode($whatsappNumber->number);
        $whatsappNumber->update([
            'qr_code' => $qrCode,
            'status' => 'scanning',
        ]);

        return redirect()->back()->with('success', 'QR code baru berjaya dijana.');
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(WhatsappNumber $whatsappNumber)
    {
        $testMessage = '🧪 Test message dari sistem Aduan Kerosakan Sekolah pada '.now()->format('d/m/Y H:i:s');

        $success = WhatsappService::sendMessage($whatsappNumber->number, $testMessage);

        if ($success) {
            $whatsappNumber->update([
                'status' => 'active',
                'last_connected_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Test WhatsApp berjaya dihantar!');
        } else {
            return redirect()->back()->with('error', 'Gagal hantar test message. Sila semak sambungan.');
        }
    }

    /**
     * Check WhatsApp gateway health
     */
    public function health()
    {
        $url = rtrim(config('whatsapp.gateway_url', ''), '/');
        $token = config('whatsapp.gateway_token', '');
        $timeout = (int) config('whatsapp.timeout', 10);

        if (! $url || ! $token) {
            return redirect()->back()->with('error', 'Gateway belum dikonfigurasi. Sila isi WA_GATEWAY_URL dan WA_GATEWAY_TOKEN dalam .env');
        }

        try {
            $resp = Http::withToken($token)->timeout($timeout)->get($url.'/health');
            if ($resp->successful()) {
                return redirect()->back()->with('success', 'Gateway OK: '.substr($resp->body(), 0, 200));
            }

            return redirect()->back()->with('error', 'Gateway tidak OK: HTTP '.$resp->status());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Ralat sambungan gateway: '.$e->getMessage());
        }
    }
}
