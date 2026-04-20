<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DiscountRule;
use App\Models\Coupon;
use App\Models\GiftCard;
use App\Models\PackageSession;
use App\Models\MembershipAlert;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    // Discount Rules
    public function discountRules()
    {
        $discountRules = DiscountRule::latest()->paginate(15);
        return view('promotions.discount-rules.index', compact('discountRules'));
    }

    public function createDiscountRule()
    {
        return view('promotions.discount-rules.create');
    }

    public function storeDiscountRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,buy_x_get_y,first_time_customer,loyalty_points',
            'value' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);

        DiscountRule::create($validated);

        return redirect()->route('promotions.discount-rules')->with('success', 'Discount rule created successfully.');
    }

    public function editDiscountRule(DiscountRule $discountRule)
    {
        return view('promotions.discount-rules.edit', compact('discountRule'));
    }

    public function updateDiscountRule(Request $request, DiscountRule $discountRule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount,buy_x_get_y,first_time_customer,loyalty_points',
            'value' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);

        $discountRule->update($validated);

        return redirect()->route('promotions.discount-rules')->with('success', 'Discount rule updated successfully.');
    }

    public function destroyDiscountRule(DiscountRule $discountRule)
    {
        $discountRule->delete();
        return redirect()->route('promotions.discount-rules')->with('success', 'Discount rule deleted successfully.');
    }

    // Coupons
    public function coupons()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('promotions.coupons.index', compact('coupons'));
    }

    public function createCoupon()
    {
        return view('promotions.coupons.create');
    }

    public function storeCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'minimum_purchase' => 'numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'applicable_services' => 'nullable|array',
            'applicable_products' => 'nullable|array',
        ]);

        Coupon::create($validated);

        return redirect()->route('promotions.coupons')->with('success', 'Coupon created successfully.');
    }

    public function editCoupon(Coupon $coupon)
    {
        return view('promotions.coupons.edit', compact('coupon'));
    }

    public function updateCoupon(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'minimum_purchase' => 'numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'applicable_services' => 'nullable|array',
            'applicable_products' => 'nullable|array',
        ]);

        $coupon->update($validated);

        return redirect()->route('promotions.coupons')->with('success', 'Coupon updated successfully.');
    }

    public function destroyCoupon(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('promotions.coupons')->with('success', 'Coupon deleted successfully.');
    }

    // Gift Cards
    public function giftCards()
    {
        $giftCards = GiftCard::with(['customer', 'issuer'])->latest()->paginate(15);
        return view('promotions.gift-cards.index', compact('giftCards'));
    }

    public function createGiftCard()
    {
        $customers = Customer::all();
        return view('promotions.gift-cards.create', compact('customers'));
    }

    public function storeGiftCard(Request $request)
    {
        $validated = $request->validate([
            'card_number' => 'required|string|unique:gift_cards,card_number',
            'pin' => 'nullable|string|min:4|max:6',
            'initial_balance' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'expiry_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $validated['issued_by'] = auth()->id();
        $validated['issued_date'] = now()->toDateString();
        $validated['current_balance'] = $validated['initial_balance'];

        GiftCard::create($validated);

        return redirect()->route('promotions.gift-cards')->with('success', 'Gift card created successfully.');
    }

    public function showGiftCard(GiftCard $giftCard)
    {
        return view('promotions.gift-cards.show', compact('giftCard'));
    }

    public function editGiftCard(GiftCard $giftCard)
    {
        $customers = Customer::all();
        return view('promotions.gift-cards.edit', compact('giftCard', 'customers'));
    }

    public function updateGiftCard(Request $request, GiftCard $giftCard)
    {
        $validated = $request->validate([
            'card_number' => 'required|string|unique:gift_cards,card_number,' . $giftCard->id,
            'pin' => 'nullable|string|min:4|max:6',
            'current_balance' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'expiry_date' => 'nullable|date',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $giftCard->update($validated);

        return redirect()->route('promotions.gift-cards')->with('success', 'Gift card updated successfully.');
    }

    public function destroyGiftCard(GiftCard $giftCard)
    {
        $giftCard->delete();
        return redirect()->route('promotions.gift-cards')->with('success', 'Gift card deleted successfully.');
    }

    // Package Sessions
    public function packageSessions()
    {
        $packageSessions = PackageSession::with(['customer', 'package'])->latest()->paginate(15);
        return view('promotions.package-sessions.index', compact('packageSessions'));
    }

    public function createPackageSession()
    {
        $customers = Customer::all();
        // Assuming ServicePackage model exists
        $packages = \App\Models\ServicePackage::all();
        return view('promotions.package-sessions.create', compact('customers', 'packages'));
    }

    public function storePackageSession(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:service_packages,id',
            'total_sessions' => 'required|integer|min:1',
            'expiry_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $validated['remaining_sessions'] = $validated['total_sessions'];
        $validated['purchase_date'] = now()->toDateString();

        PackageSession::create($validated);

        return redirect()->route('promotions.package-sessions')->with('success', 'Package session created successfully.');
    }

    public function showPackageSession(PackageSession $packageSession)
    {
        return view('promotions.package-sessions.show', compact('packageSession'));
    }

    public function editPackageSession(PackageSession $packageSession)
    {
        $customers = Customer::all();
        $packages = \App\Models\ServicePackage::all();
        return view('promotions.package-sessions.edit', compact('packageSession', 'customers', 'packages'));
    }

    public function updatePackageSession(Request $request, PackageSession $packageSession)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id' => 'required|exists:service_packages,id',
            'total_sessions' => 'required|integer|min:1',
            'used_sessions' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['remaining_sessions'] = $validated['total_sessions'] - $validated['used_sessions'];

        $packageSession->update($validated);

        return redirect()->route('promotions.package-sessions')->with('success', 'Package session updated successfully.');
    }

    public function destroyPackageSession(PackageSession $packageSession)
    {
        $packageSession->delete();
        return redirect()->route('promotions.package-sessions')->with('success', 'Package session deleted successfully.');
    }

    // Membership Alerts
    public function membershipAlerts()
    {
        $alerts = MembershipAlert::with('customer')->latest()->paginate(15);
        return view('promotions.membership-alerts.index', compact('alerts'));
    }

    public function createMembershipAlert()
    {
        $customers = Customer::whereNotNull('membership_expires')->get();
        return view('promotions.membership-alerts.create', compact('customers'));
    }

    public function storeMembershipAlert(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'alert_type' => 'required|in:expiry_warning,expired,renewal_reminder',
            'alert_date' => 'required|date',
            'membership_expiry_date' => 'required|date',
            'message' => 'nullable|string',
        ]);

        MembershipAlert::create($validated);

        return redirect()->route('promotions.membership-alerts')->with('success', 'Membership alert created successfully.');
    }

    public function showMembershipAlert(MembershipAlert $membershipAlert)
    {
        return view('promotions.membership-alerts.show', compact('membershipAlert'));
    }

    public function editMembershipAlert(MembershipAlert $membershipAlert)
    {
        $customers = Customer::whereNotNull('membership_expires')->get();
        return view('promotions.membership-alerts.edit', compact('membershipAlert', 'customers'));
    }

    public function updateMembershipAlert(Request $request, MembershipAlert $membershipAlert)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'alert_type' => 'required|in:expiry_warning,expired,renewal_reminder',
            'alert_date' => 'required|date',
            'membership_expiry_date' => 'required|date',
            'is_sent' => 'boolean',
            'message' => 'nullable|string',
        ]);

        $membershipAlert->update($validated);

        return redirect()->route('promotions.membership-alerts')->with('success', 'Membership alert updated successfully.');
    }

    public function destroyMembershipAlert(MembershipAlert $membershipAlert)
    {
        $membershipAlert->delete();
        return redirect()->route('promotions.membership-alerts')->with('success', 'Membership alert deleted successfully.');
    }
}
