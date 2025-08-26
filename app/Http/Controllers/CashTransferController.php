<?php

namespace App\Http\Controllers;

use App\Models\CashTransfer;
use Illuminate\Http\Request;

class CashTransferController extends Controller
{
    public function index(Request $request)
    {
        $query = CashTransfer::with('user');

        if ($request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transfers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('cash-transfers.index', compact('transfers'));
    }

    public function create()
    {
        return view('cash-transfers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        CashTransfer::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('cash-transfers.index')->with('success', 'تم إضافة المعاملة النقدية بنجاح');
    }

    public function show(CashTransfer $cashTransfer)
    {
        return view('cash-transfers.show', compact('cashTransfer'));
    }

    public function edit(CashTransfer $cashTransfer)
    {
        return view('cash-transfers.edit', compact('cashTransfer'));
    }

    public function update(Request $request, CashTransfer $cashTransfer)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        $cashTransfer->update($request->all());

        return redirect()->route('cash-transfers.index')->with('success', 'تم تحديث المعاملة النقدية بنجاح');
    }

    public function destroy(CashTransfer $cashTransfer)
    {
        $cashTransfer->delete();
        return redirect()->route('cash-transfers.index')->with('success', 'تم حذف المعاملة النقدية بنجاح');
    }
}
