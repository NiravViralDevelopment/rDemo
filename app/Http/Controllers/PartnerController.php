<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerPayment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PartnerController extends Controller
{
    public function index()
    {
        $query = Partner::query();
        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        $totalBalance = (float) (clone $query)->sum('total_balance');
        $partnerIds = (clone $query)->pluck('id');
        $totalPaid = (float) PartnerPayment::query()
            ->whereIn('partner_id', $partnerIds)
            ->sum('amount');
        $pendingAmount = max($totalBalance - $totalPaid, 0);

        return view('partners.index', compact('totalBalance', 'totalPaid', 'pendingAmount'));
    }

    public function data()
    {
        $query = Partner::query()
            ->with('project')
            ->withSum('payments', 'amount')
            ->latest('id');

        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        return DataTables::eloquent($query)
            ->addColumn('project_name', fn (Partner $partner) => $partner->project?->name ?? 'GLOBAL')
            ->addColumn('total_paid', function (Partner $partner) {
                return number_format((float) ($partner->payments_sum_amount ?? 0), 2);
            })
            ->addColumn('pending_amount', function (Partner $partner) {
                $paid = (float) ($partner->payments_sum_amount ?? 0);
                $pending = max((float) $partner->total_balance - $paid, 0);
                return number_format($pending, 2);
            })
            ->addColumn('action', function (Partner $partner) {
                $editUrl = route('partners.edit', $partner->id);
                $deleteUrl = route('partners.destroy', $partner->id);
                $paymentUrl = route('partners.payments.create', $partner->id);
                $token = csrf_token();

                return '
                    <div class="action-group">
                        <a href="' . $paymentUrl . '" class="btn btn-action partner-action-btn action-payment" title="Add Payment">
                            <i class="bi bi-cash-coin"></i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-action partner-action-btn action-edit" title="Edit Partner">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form method="POST" action="' . $deleteUrl . '" class="d-inline-block m-0 p-0">
                            <input type="hidden" name="_token" value="' . $token . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-action partner-action-btn action-delete" title="Delete Partner" onclick="return confirm(\'Delete this partner?\')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function createPayment(int $id)
    {
        $partner = Partner::query()->withSum('payments', 'amount')->findOrFail($id);
        $this->authorizePartner($partner);

        $paid = (float) ($partner->payments_sum_amount ?? 0);
        $pendingAmount = max((float) $partner->total_balance - $paid, 0);

        return view('partners.payment_create', compact('partner', 'paid', 'pendingAmount'));
    }

    public function storePayment(Request $request, int $id)
    {
        $partner = Partner::query()->withSum('payments', 'amount')->findOrFail($id);
        $this->authorizePartner($partner);

        $paid = (float) ($partner->payments_sum_amount ?? 0);
        $pendingAmount = max((float) $partner->total_balance - $paid, 0);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        if ((float) $validated['amount'] > $pendingAmount) {
            return redirect()->back()
                ->withErrors(['amount' => 'Payment amount cannot exceed pending amount.'])
                ->withInput();
        }

        PartnerPayment::query()->create([
            'partner_id' => $partner->id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'] ?? now()->toDateString(),
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('partners.index')->with('message', 'Payment saved successfully.');
    }

    public function create()
    {
        $projects = $this->allowedProjects();
        return view('partners.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'total_balance' => ['nullable', 'numeric', 'min:0'],
            'percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'details' => ['nullable', 'string'],
        ]);

        $validated['project_id'] = $this->resolveProjectId($validated['project_id'] ?? null);
        $validated['total_balance'] = $validated['total_balance'] ?? 0;
        $validated['percentage'] = $validated['percentage'] ?? 0;

        Partner::query()->create($validated);

        return redirect()->route('partners.index')->with('message', 'Partner created successfully.');
    }

    public function edit(int $id)
    {
        $partner = Partner::query()->findOrFail($id);
        $this->authorizePartner($partner);
        $projects = $this->allowedProjects();

        return view('partners.edit', compact('partner', 'projects'));
    }

    public function update(Request $request, int $id)
    {
        $partner = Partner::query()->findOrFail($id);
        $this->authorizePartner($partner);

        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'total_balance' => ['nullable', 'numeric', 'min:0'],
            'percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'details' => ['nullable', 'string'],
        ]);

        $validated['project_id'] = $this->resolveProjectId($validated['project_id'] ?? null);
        $validated['total_balance'] = $validated['total_balance'] ?? 0;
        $validated['percentage'] = $validated['percentage'] ?? 0;

        $partner->update($validated);

        return redirect()->route('partners.index')->with('message', 'Partner updated successfully.');
    }

    public function destroy(int $id)
    {
        $partner = Partner::query()->findOrFail($id);
        $this->authorizePartner($partner);
        $partner->delete();

        return redirect()->route('partners.index')->with('message', 'Partner deleted successfully.');
    }

    private function allowedProjects()
    {
        $user = Auth::user();
        return Project::query()
            ->when(! empty($user->project_id), fn ($q) => $q->where('id', $user->project_id))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function resolveProjectId(?int $projectId): ?int
    {
        $user = Auth::user();
        if (! empty($user->project_id)) {
            return (int) $user->project_id;
        }
        return $projectId;
    }

    private function authorizePartner(Partner $partner): void
    {
        $user = Auth::user();
        if (! empty($user->project_id) && (int) $partner->project_id !== (int) $user->project_id) {
            abort(403, 'Not allowed.');
        }
    }
}
