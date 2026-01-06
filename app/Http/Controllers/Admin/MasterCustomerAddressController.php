<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterCustomerAddress;
use App\Models\User;

class MasterCustomerAddressController extends Controller
{
    public function index()
    {
        $addresses = MasterCustomerAddress::orderByDesc('id')->get();
        return view('admin.master_customer_address.index', compact('addresses'));
    }

    public function create()
    {
        return view('admin.master_customer_address.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sys_user_id'   => ['required', 'integer', 'exists:sys_user,id'],
            'name'          => ['required', 'string', 'max:150'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'lines_text'    => ['required', 'string'],
            'city'          => ['nullable', 'string', 'max:255'],
            'tag'           => ['nullable', 'string', 'max:50'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $lines = array_values(array_filter(array_map(function ($line) {
            return trim($line);
        }, preg_split('/\r?\n/', (string)($data['lines_text'] ?? ''), -1, PREG_SPLIT_NO_EMPTY))));
        if (count($lines) === 0) {
            $lines = [trim((string)($data['lines_text'] ?? ''))];
        }

        MasterCustomerAddress::create([
            'sys_user_id'   => (int)$data['sys_user_id'],
            'name'          => (string)$data['name'],
            'phone'         => $data['phone'] ?? null,
            'lines'         => $lines,
            'city'          => $data['city'] ?? null,
            'tag'           => $data['tag'] ?? null,
            'shipping_cost' => (float)($data['shipping_cost'] ?? 0),
        ]);

        return redirect()->route('admin.master.customer-addresses.index')
            ->with('success', 'Alamat customer berhasil ditambahkan.');
    }

    public function searchUsers(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $users = User::where('role', 'customer')
            ->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('username', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            })
            ->limit(10)
            ->get();

        $results = $users->map(function (User $u) {
            $c = $u->customer;
            return [
                'id'       => (int)$u->id,
                'name'     => (string)($u->name ?? ''),
                'username' => (string)($u->username ?? ''),
                'email'    => (string)($u->email ?? ''),
                'customer' => $c ? [
                    'full_name'    => (string)($c->full_name ?? ''),
                    'phone_wa'     => (string)($c->phone_wa ?? ''),
                    'address_line' => (string)($c->address_line ?? ''),
                    'kota'         => (string)($c->kota ?? ''),
                    'provinsi'     => (string)($c->provinsi ?? ''),
                    'kode_pos'     => (string)($c->kode_pos ?? ''),
                ] : null,
            ];
        });

        return response()->json($results);
    }

    public function edit(MasterCustomerAddress $address)
    {
        return view('admin.master_customer_address.edit', compact('address'));
    }

    public function update(Request $request, MasterCustomerAddress $address)
    {
        $data = $request->validate([
            'sys_user_id'   => ['required', 'integer', 'exists:sys_user,id'],
            'name'          => ['required', 'string', 'max:150'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'lines_text'    => ['required', 'string'],
            'city'          => ['nullable', 'string', 'max:255'],
            'tag'           => ['nullable', 'string', 'max:50'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $lines = array_values(array_filter(array_map(function ($line) {
            return trim($line);
        }, preg_split('/\r?\n/', (string)($data['lines_text'] ?? ''), -1, PREG_SPLIT_NO_EMPTY))));
        if (count($lines) === 0) {
            $lines = [trim((string)($data['lines_text'] ?? ''))];
        }

        $address->update([
            'sys_user_id'   => (int)$data['sys_user_id'],
            'name'          => (string)$data['name'],
            'phone'         => $data['phone'] ?? null,
            'lines'         => $lines,
            'city'          => $data['city'] ?? null,
            'tag'           => $data['tag'] ?? null,
            'shipping_cost' => (float)($data['shipping_cost'] ?? 0),
        ]);

        return redirect()->route('admin.master.customer-addresses.index')
            ->with('success', 'Alamat customer berhasil diupdate.');
    }

    public function destroy(MasterCustomerAddress $address)
    {
        $address->delete();
        return redirect()->route('admin.master.customer-addresses.index')
            ->with('success', 'Alamat customer berhasil dihapus.');
    }
}