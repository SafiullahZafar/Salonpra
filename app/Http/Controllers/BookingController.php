<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $categories = Category::with(['services', 'products'])->get();
        return view('booking.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.type' => 'required|in:service,product',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,qr',
        ]);

        $total = 0;
        $items = [];

        foreach ($request->items as $itemData) {
            if ($itemData['type'] === 'service') {
                $service = Service::findOrFail($itemData['id']);
                $subtotal = $service->price * $itemData['quantity'];
                $total += $subtotal;

                $items[] = [
                    'itemizable_type' => Service::class,
                    'itemizable_id' => $service->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $service->price,
                    'subtotal' => $subtotal,
                ];
            } else {
                $product = Product::findOrFail($itemData['id']);
                $subtotal = $product->price * $itemData['quantity'];
                $total += $subtotal;

                $items[] = [
                    'itemizable_type' => Product::class,
                    'itemizable_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }
        }

        $tax = $total * 0.05; // 5% tax
        $payableAmount = $total + $tax;

        // Create invoice
        $invoice = Invoice::create([
            'invoice_no' => 'INV-' . strtoupper(uniqid()),
            'user_id' => null, // No user for walk-in customers
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'subtotal' => $total,
            'tax' => $tax,
            'payable_amount' => $payableAmount,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
        ]);

        // Create invoice items
        foreach ($items as $item) {
            $item['invoice_id'] = $invoice->id;
            InvoiceItem::create($item);
        }

        return response()->json([
            'success' => true,
            'invoice' => $invoice,
            'message' => 'Booking completed successfully!'
        ]);
    }

    public function show($id)
    {
        $invoice = Invoice::with('items.itemizable')->findOrFail($id);
        return view('booking.receipt', compact('invoice'));
    }
}