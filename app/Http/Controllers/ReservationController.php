<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\DiningTable;
use Illuminate\Http\Request;
use Carbon\Carbon; // Tarih işlemleri için gerekli

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('diningTable')
            ->orderBy('reservation_time', 'asc')
            ->get();

        $tables = DiningTable::orderBy('name', 'asc')->get();

        return view('reservations.index', compact('reservations', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'dining_table_id' => 'required|exists:dining_tables,id',
            'reservation_time' => 'required|date',
            'guests_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        // --- ÇAKIŞMA KONTROLÜ BAŞLANGIÇ ---
        if ($this->hasConflict($request->dining_table_id, $request->reservation_time)) {
            return back()
                ->withInput() // Form verilerini geri gönder
                ->withErrors(['reservation_time' => 'Seçilen saatte bu masa dolu! Lütfen en az 1 saat ara bırakın.']);
        }
        // --- ÇAKIŞMA KONTROLÜ BİTİŞ ---

        $reservation = new Reservation();
        $reservation->customer_name = $request->customer_name;
        $reservation->phone = $request->phone;
        $reservation->dining_table_id = $request->dining_table_id;
        $reservation->reservation_time = $request->reservation_time;
        $reservation->guests_count = $request->guests_count;
        $reservation->status = $request->status;
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Rezervasyon başarıyla oluşturuldu.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'dining_table_id' => 'required|exists:dining_tables,id',
            'reservation_time' => 'required|date',
            'guests_count' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        // --- ÇAKIŞMA KONTROLÜ (Kendi ID'si hariç) ---
        if ($this->hasConflict($request->dining_table_id, $request->reservation_time, $id)) {
            return back()
                ->withInput()
                ->withErrors(['reservation_time' => 'Seçilen saatte bu masa dolu! Lütfen en az 1 saat ara bırakın.']);
        }
        // ---------------------------------------------

        $reservation = Reservation::findOrFail($id);
        $reservation->customer_name = $request->customer_name;
        $reservation->phone = $request->phone;
        $reservation->dining_table_id = $request->dining_table_id;
        $reservation->reservation_time = $request->reservation_time;
        $reservation->guests_count = $request->guests_count;
        $reservation->status = $request->status;
        $reservation->save();

        return redirect()->route('reservations.index')->with('success', 'Rezervasyon güncellendi.');
    }

    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Rezervasyon silindi.');
    }

    /**
     * MASA DOLULUK KONTROLÜ YAPAN FONKSİYON
     * Varsayılan yemek süresi: 60 Dakika
     */
    private function hasConflict($tableId, $reservationTime, $ignoreId = null)
    {
        $newTime = Carbon::parse($reservationTime);
        $duration = 60; // Dakika cinsinden yemek süresi (İstersen burayı 90 yapabilirsin)

        // Çakışma Mantığı:
        // Veritabanındaki rezervasyon saati, Yeni saatin X dakika öncesi ile X dakika sonrası arasındaysa çakışır.
        // Örn: Yeni kayıt 12:00 ise, 11:01 ile 12:59 arasındaki herhangi bir kayıt çakışma yaratır.

        $query = Reservation::where('dining_table_id', $tableId)
            ->where('status', '!=', 'cancelled') // İptal edilenler çakışma yapmaz
            ->whereBetween('reservation_time', [
                $newTime->copy()->subMinutes($duration - 1),
                $newTime->copy()->addMinutes($duration - 1)
            ]);

        // Güncelleme yapıyorsak, kendisini kontrol etme
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
    public function getAvailability(Request $request, $tableId)
    {
        $ignoreId = $request->query('ignore_id');

        // İptal edilmemiş rezervasyonları getir
        $reservations = Reservation::where('dining_table_id', $tableId)
            ->where('status', '!=', 'cancelled')
            ->when($ignoreId, function ($query, $ignoreId) {
                return $query->where('id', '!=', $ignoreId);
            })
            ->get();

        $blockedRanges = [];

        foreach ($reservations as $res) {
            // Veritabanındaki kayıtlı saati al
            $existingStart = \Carbon\Carbon::parse($res->reservation_time);

            // MANTIK: Bir randevu 12:00'da ise, 11:30'a randevu alınamaz (çünkü 12:30'da biter, çakışır).
            // 12:30'a da alınamaz (çünkü 12:00'daki hala bitmemiştir).
            // Dolayısıyla 12:00'daki bir randevu için engellenecek aralık: 11:01 - 12:59 arasıdır.

            $blockedRanges[] = [
                'from' => $existingStart->copy()->subMinutes(59)->format('Y-m-d H:i'),
                'to'   => $existingStart->copy()->addMinutes(59)->format('Y-m-d H:i'),
            ];
        }

        return response()->json($blockedRanges);
    }
}
