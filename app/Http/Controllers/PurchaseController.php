<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by supplier
        if ($request->has('supplier_id') && !empty($request->supplier_id)) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Search by PO number
        if ($request->has('search') && !empty($request->search)) {
            $query->where('purchase_order_number', 'like', '%' . $request->search . '%');
        }

        $purchases = $query->orderBy('created_at', 'desc')->paginate(15);
        $suppliers = Supplier::where('is_active', true)->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::where('track_inventory', true)->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($validated) {
            $totalAmount = 0;

            // Calculate total amount
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity_ordered'] * $item['unit_cost'];
            }

            // Create purchase order
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
                'status' => 'ordered'
            ]);

            // Generate PO number
            $purchase->update([
                'purchase_order_number' => $purchase->generatePurchaseOrderNumber()
            ]);

            // Create purchase items
            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity_ordered'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $item['quantity_ordered'] * $item['unit_cost']
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase order created successfully.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'purchaseItems.product']);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        if ($purchase->status !== 'ordered') {
            return redirect()->route('purchases.index')->with('error', 'Only ordered purchases can be edited.');
        }

        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::where('track_inventory', true)->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->status !== 'ordered') {
            return redirect()->route('purchases.index')->with('error', 'Only ordered purchases can be updated.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($validated, $purchase) {
            $totalAmount = 0;

            // Calculate total amount
            foreach ($validated['items'] as $item) {
                $totalAmount += $item['quantity_ordered'] * $item['unit_cost'];
            }

            // Update purchase
            $purchase->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null
            ]);

            // Delete existing items and create new ones
            $purchase->purchaseItems()->delete();

            foreach ($validated['items'] as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity_ordered'],
                    'unit_cost' => $item['unit_cost'],
                    'line_total' => $item['quantity_ordered'] * $item['unit_cost']
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->status !== 'ordered') {
            return redirect()->route('purchases.index')->with('error', 'Only ordered purchases can be deleted.');
        }

        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase order deleted successfully.');
    }

    public function receive(Purchase $purchase)
    {
        if ($purchase->status === 'received') {
            return redirect()->route('purchases.show', $purchase)->with('error', 'Purchase is already received.');
        }

        $purchase->load('purchaseItems.product');

        return view('purchases.receive', compact('purchase'));
    }

    public function processReceive(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'actual_delivery_date' => 'required|date',
            'items' => 'required|array',
            'items.*.quantity_received' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($validated, $purchase) {
            $allReceived = true;

            foreach ($validated['items'] as $itemId => $itemData) {
                $purchaseItem = $purchase->purchaseItems()->find($itemId);
                if ($purchaseItem) {
                    $purchaseItem->update([
                        'quantity_received' => $itemData['quantity_received']
                    ]);

                    // Add stock to product
                    if ($itemData['quantity_received'] > 0) {
                        $purchaseItem->product->addStock($itemData['quantity_received']);
                    }

                    if ($itemData['quantity_received'] < $purchaseItem->quantity_ordered) {
                        $allReceived = false;
                    }
                }
            }

            $purchase->update([
                'actual_delivery_date' => $validated['actual_delivery_date'],
                'status' => $allReceived ? 'received' : 'partially_received'
            ]);
        });

        return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase received successfully.');
    }
}
