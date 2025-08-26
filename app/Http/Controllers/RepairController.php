<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with('user')->orderBy('created_at', 'desc')->paginate(20);

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
            'repair_type' => 'required|in:hardware,software',
            'repair_cost' => 'required|numeric|min:0',
        ]);

        Repair::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'device_type' => $request->device_type,
            'problem_description' => $request->problem_description,
            'repair_type' => $request->repair_type,
            'repair_cost' => $request->repair_cost,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('repairs.index')->with('success', 'تم إضافة أمر الصيانة بنجاح');
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
            'repair_type' => 'required|in:hardware,software',
            'repair_cost' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,delivered',
        ]);

        $repair->update($request->all());

        return redirect()->route('repairs.index')->with('success', 'تم تحديث أمر الصيانة بنجاح');
    }

    public function destroy(Repair $repair)
    {
        $repair->delete();

        return redirect()->route('repairs.index')->with('success', 'تم حذف أمر الصيانة بنجاح');
    }
}
