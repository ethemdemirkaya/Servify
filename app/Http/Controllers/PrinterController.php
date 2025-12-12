<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    public function index()
    {
        $printers = Printer::latest()->get();
        return view('printers.index', compact('printers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|in:network,usb',
            'ip_address' => 'nullable|required_if:type,network|ip',
            'port' => 'nullable|required_if:type,network|integer',
        ]);

        Printer::create($request->all());

        return redirect()->back()->with('success', 'Yazıcı başarıyla eklendi.');
    }

    public function update(Request $request, $id)
    {
        $printer = Printer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'type' => 'required|in:network,usb',
            'ip_address' => 'nullable|required_if:type,network|ip',
            'port' => 'nullable|required_if:type,network|integer',
        ]);

        $printer->update($request->all());

        return redirect()->back()->with('success', 'Yazıcı bilgileri güncellendi.');
    }

    public function destroy($id)
    {
        Printer::destroy($id);
        return redirect()->back()->with('success', 'Yazıcı silindi.');
    }

    // Yazıcı Bağlantı Testi (Ping / Socket)
    public function testConnection($id)
    {
        $printer = Printer::findOrFail($id);

        if ($printer->type == 'usb') {
            return response()->json(['status' => 'success', 'message' => 'USB yazıcılar sunucu üzerinden test edilemez. Windows/Linux sürücülerini kontrol edin.']);
        }

        try {
            // Yazıcı IP ve Portuna 2 saniyelik bir bağlantı dene
            $fp = fsockopen($printer->ip_address, $printer->port, $errno, $errstr, 2);

            if ($fp) {
                fclose($fp);
                return response()->json(['status' => 'success', 'message' => 'Bağlantı Başarılı! Yazıcı yanıt veriyor.']);
            } else {
                return response()->json(['status' => 'error', 'message' => "Bağlantı Hatası: $errstr ($errno)"]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Yazıcıya ulaşılamadı. IP adresini ve fişi kontrol edin.']);
        }
    }
}
