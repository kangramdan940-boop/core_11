<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterCustomer;
use Illuminate\Http\Request;

class MasterCustomerController extends Controller
{
    public function index()
    {
        $customers = MasterCustomer::orderByDesc('id')->paginate(20);

        return view('admin.master_customer.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.master_customer.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150', 'unique:master_customer,email'],
            'phone_wa'    => ['required', 'string', 'max:50'],
            'nik'         => ['nullable', 'string', 'max:30'],
            'no_kk'       => ['nullable', 'string', 'max:30'],
            'birth_date'  => ['nullable', 'date'],
            'address_line'=> ['nullable', 'string', 'max:255'],
            'kelurahan'   => ['nullable', 'string', 'max:100'],
            'kecamatan'   => ['nullable', 'string', 'max:100'],
            'kota'        => ['nullable', 'string', 'max:100'],
            'provinsi'    => ['nullable', 'string', 'max:100'],
            'kode_pos'    => ['nullable', 'string', 'max:10'],
            'is_active'   => ['sometimes', 'accepted'],
        ]);
        $data['is_active'] = $request->has('is_active');

        MasterCustomer::create($data);

        return redirect()
            ->route('admin.master.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit(MasterCustomer $customer)
    {
        return view('admin.master_customer.edit', compact('customer'));
    }

    public function update(Request $request, MasterCustomer $customer)
    {


        $data = $request->validate([
            'full_name'   => ['required', 'string', 'max:150'],
            'email'       => ['required', 'email', 'max:150', 'unique:master_customer,email,' . $customer->id],
            'phone_wa'    => ['required', 'string', 'max:50'],
            'nik'         => ['nullable', 'string', 'max:30'],
            'no_kk'       => ['nullable', 'string', 'max:30'],
            'birth_date'  => ['nullable', 'date'],
            'address_line'=> ['nullable', 'string', 'max:255'],
            'kelurahan'   => ['nullable', 'string', 'max:100'],
            'kecamatan'   => ['nullable', 'string', 'max:100'],
            'kota'        => ['nullable', 'string', 'max:100'],
            'provinsi'    => ['nullable', 'string', 'max:100'],
            'kode_pos'    => ['nullable', 'string', 'max:10'],
            'is_active'   => ['sometimes', 'accepted'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $customer->update($data);

        return redirect()
            ->route('admin.master.customers.index')
            ->with('success', 'Customer berhasil diupdate.');
    }

    public function destroy(MasterCustomer $customer)
    {
        if ($customer->user) {
            return redirect()
                ->route('admin.master.customers.index')
                ->withErrors(['delete' => 'Tidak dapat menghapus customer yang terhubung ke sys_user. Nonaktifkan akun (is_active = false) sebagai gantinya.']);
        }

        $customer->delete();

        return redirect()
            ->route('admin.master.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
