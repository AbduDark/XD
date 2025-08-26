<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $query = Repair::with('user');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%')
                  ->orWhere('device_type', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $repairs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('repairs.index', compact('repairs'));
    }

    public function create()
    {
        return view('repairs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'device_type' => 'required|string|max:255',
            'problem_description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'estimated_delivery' => 'required|date',
        ]);

        Repair::create([
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'device_type' => $request->device_type,
            'problem_description' => $request->problem_description,
            'estimated_cost' => $request->estimated_cost,
            'estimated_delivery' => $request->estimated_delivery,
            'status' => 'pending',
        ]);

        return redirect()->route('repairs.index')->with('success', 'تم إضافة عملية الصيانة بنجاح');
    }

    public function show(Repair $repair)
    {
        return view('repairs.show', compact('repair'));
    }

    public function edit(Repair $repair)
    {
        return view('repairs.edit', compact('repair'));
    }

    public function update(Request $request, Repair $repair)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'device_type' => 'required|string|max:255',
            'problem_description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'estimated_delivery' => 'required|date',
            'actual_delivery' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,delivered',
            'notes' => 'nullable|string',
        ]);

        $repair->update($request->all());

        return redirect()->route('repairs.index')->with('success', 'تم تحديث عملية الصيانة بنجاح');
    }

    public function destroy(Repair $repair)
    {
        $repair->delete();
        return redirect()->route('repairs.index')->with('success', 'تم حذف عملية الصيانة بنجاح');
    }
}
