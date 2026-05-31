<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['address' => null, 'phone' => null, 'birth_date' => null, 'gender' => null]
        );

        return view('account.profile', compact('user', 'profile'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
        ]);

        $user = Auth::user();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
        $profile->fill([
            'phone' => $validated['phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ]);
        $profile->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function address()
    {
        $user = Auth::user();
        $profile = UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['address' => null, 'phone' => null, 'birth_date' => null, 'gender' => null]
        );
        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();

        return view('account.address', compact('user', 'profile', 'addresses'));
    }

    public function updateAddress(Request $request)
    {
        $validated = $request->validate([
            'address' => ['required', 'string', 'max:1000'],
        ]);

        $profile = UserProfile::firstOrCreate([
            'user_id' => Auth::id(),
        ], [
            'phone' => null,
            'birth_date' => null,
            'gender' => null,
        ]);

        $profile->address = $validated['address'];
        $profile->save();

        return back()->with('success', 'Alamat profil utama berhasil diperbarui.');
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $user = Auth::user();
        $isDefault = (bool) ($validated['is_default'] ?? false);
        $hasAnyAddress = $user->addresses()->exists();

        if (! $hasAnyAddress) {
            $isDefault = true;
        }

        $address = $user->addresses()->create([
            'label' => $validated['label'],
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_default' => $isDefault,
        ]);

        if ($isDefault) {
            $user->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);

            $this->syncLegacyAddress($address->address);
        }

        return back()->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    public function setDefaultAddress(UserAddress $address)
    {
        abort_if($address->user_id !== Auth::id(), 403, 'Unauthorized');

        $user = Auth::user();

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        $this->syncLegacyAddress($address->address);

        return back()->with('success', 'Alamat utama berhasil diubah.');
    }

    public function deleteAddress(UserAddress $address)
    {
        abort_if($address->user_id !== Auth::id(), 403, 'Unauthorized');

        $wasDefault = (bool) $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $nextAddress = Auth::user()->addresses()->orderByDesc('id')->first();

            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
                $this->syncLegacyAddress($nextAddress->address);
            }
        }

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function orders()
    {
        $orders = Order::query()
            ->where('orders.user_id', Auth::id())
            ->with(['items.bread', 'checkoutMeta'])
            ->orderByDesc('orders.created_at')
            ->get();

        return view('account.orders', compact('orders'));
    }

    private function syncLegacyAddress(string $address): void
    {
        $profile = UserProfile::firstOrCreate(['user_id' => Auth::id()]);
        $profile->address = $address;
        $profile->save();
    }
}
