<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransPo;
use App\Models\TransReady;
use App\Models\TransCicilan;
use App\Models\TransPaymentLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function stats(Request $request)
    {
        $sinceStr = (string) $request->query('since', '');
        $since = null;
        if ($sinceStr !== '') {
            try { $since = Carbon::parse($sinceStr); } catch (\Throwable $e) { $since = null; }
        }

        $counts = [
            'po_pending'        => (int) TransPo::where('status', 'pending_payment')->count(),
            'ready_pending'     => (int) TransReady::where('status', 'pending_payment')->count(),
            'cicilan_aktif'     => (int) TransCicilan::whereNull('completed_at')->whereNull('cancelled_at')->count(),
            'payment_pending'   => (int) TransPaymentLog::where('status', 'pending')->count(),
        ];

        $latest = [];
        foreach (TransPo::orderByDesc('created_at')->limit(5)->get(['id','kode_po','status','created_at']) as $p) {
            $latest[] = [ 'type' => 'po', 'id' => $p->id, 'code' => $p->kode_po, 'status' => $p->status, 'created_at' => optional($p->created_at)->format('Y-m-d H:i') ];
        }
        foreach (TransReady::orderByDesc('created_at')->limit(5)->get(['id','kode_trans','status','created_at']) as $r) {
            $latest[] = [ 'type' => 'ready', 'id' => $r->id, 'code' => $r->kode_trans, 'status' => $r->status, 'created_at' => optional($r->created_at)->format('Y-m-d H:i') ];
        }
        foreach (TransCicilan::orderByDesc('created_at')->limit(5)->get(['id','kode_kontrak','status','created_at']) as $c) {
            $latest[] = [ 'type' => 'cicilan', 'id' => $c->id, 'code' => $c->kode_kontrak, 'status' => $c->status, 'created_at' => optional($c->created_at)->format('Y-m-d H:i') ];
        }
        usort($latest, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));

        $newSince = [];
        $newPaymentsSince = [];
        if ($since) {
            foreach (TransPo::where('created_at', '>', $since)->orderByDesc('created_at')->get(['id','kode_po','status','created_at']) as $p) {
                $newSince[] = [ 'type' => 'po', 'id' => $p->id, 'code' => $p->kode_po, 'status' => $p->status, 'created_at' => optional($p->created_at)->format('Y-m-d H:i') ];
            }
            foreach (TransReady::where('created_at', '>', $since)->orderByDesc('created_at')->get(['id','kode_trans','status','created_at']) as $r) {
                $newSince[] = [ 'type' => 'ready', 'id' => $r->id, 'code' => $r->kode_trans, 'status' => $r->status, 'created_at' => optional($r->created_at)->format('Y-m-d H:i') ];
            }
            foreach (TransCicilan::where('created_at', '>', $since)->orderByDesc('created_at')->get(['id','kode_kontrak','status','created_at']) as $c) {
                $newSince[] = [ 'type' => 'cicilan', 'id' => $c->id, 'code' => $c->kode_kontrak, 'status' => $c->status, 'created_at' => optional($c->created_at)->format('Y-m-d H:i') ];
            }
            foreach (TransPaymentLog::where('created_at', '>', $since)->orderByDesc('created_at')->get(['id','kode_payment','status','amount','currency','ref_type','created_at']) as $pl) {
                $newPaymentsSince[] = [
                    'id'         => $pl->id,
                    'code'       => $pl->kode_payment,
                    'status'     => $pl->status,
                    'amount'     => (string) $pl->amount,
                    'currency'   => $pl->currency,
                    'ref_type'   => $pl->ref_type,
                    'created_at' => optional($pl->created_at)->format('Y-m-d H:i'),
                ];
            }
            usort($newSince, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
            usort($newPaymentsSince, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
        }

        return response()->json([
            'counts'              => $counts,
            'latest'              => array_slice($latest, 0, 5),
            'new_since'           => $newSince,
            'payments_new_since'  => $newPaymentsSince,
        ]);
    }
}
