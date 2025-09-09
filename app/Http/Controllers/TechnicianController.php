<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function dashboard()
    {
        $complaints = Complaint::with(['school', 'user'])
            ->where('assigned_to', auth()->id())
            ->latest()->paginate(10);

        return view('technician.dashboard', compact('complaints'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,selesai',
            'note' => 'nullable|string',
        ]);
        $complaint = Complaint::where('assigned_to', auth()->id())->findOrFail($id);
        $complaint->status = $request->status;
        if ($request->note) {
            // Simpan catatan ke progress update jika ada
            $complaint->progressUpdates()->create([
                'description' => $request->note,
                'user_id' => auth()->id(),
            ]);
        }
        $complaint->save();

        return redirect()->route('technician.dashboard')->with('success', 'Status aduan berjaya dikemaskini.');
    }
}
