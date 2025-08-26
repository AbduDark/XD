<?php

namespace App\Http\Controllers;

use App\Models\CashTransfer;
use Illuminate\Http\Request;

class CashTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = CashTransfer::with('user')->latest();

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transfers = $query->paginate(20);

        $totalIncoming = CashTransfer::where('type', 'incoming')->sum('amount');
        $totalOutgoing = CashTransfer::where('type', 'outgoing')->sum('amount');
        $balance = $totalIncoming - $totalOutgoing;

        return view('cash-transfers.index', compact('transfers', 'totalIncoming', 'totalOutgoing', 'balance'));
    }

    public function create()
    {
        return view('cash-transfers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:incoming,outgoing',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        CashTransfer::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'notes' => $request->notes,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('cash-transfers.index')->with('success', 'تم إضافة الحركة المالية بنجاح');
    }

    public function show(CashTransfer $cashTransfer)
    {
        $cashTransfer->load('user');
        return view('cash-transfers.show', compact('cashTransfer'));
    }

    public function destroy(CashTransfer $cashTransfer)
    {
        $cashTransfer->delete();
        return redirect()->route('cash-transfers.index')->with('success', 'تم حذف الحركة المالية بنجاح');
    }
}
