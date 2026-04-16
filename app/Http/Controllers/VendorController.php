<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Vendor;
use App\Models\VendorTransaction;
use App\Models\VendorMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index()
    {
        return view('vendors.index');
    }

    public function data()
    {
        $query = Vendor::query()
            ->with('project')
            ->withSum(['transactions as total_credit' => function ($q) {
                $q->where('type', 'credit');
            }], 'amount')
            ->withSum(['transactions as total_debit' => function ($q) {
                $q->where('type', 'debit');
            }], 'amount')
            ->latest('id');

        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        return DataTables::eloquent($query)
            ->addColumn('total_credit', fn (Vendor $vendor) => number_format((float) ($vendor->total_credit ?? 0), 2))
            ->addColumn('total_debit', fn (Vendor $vendor) => number_format((float) ($vendor->total_debit ?? 0), 2))
            ->addColumn('balance', function (Vendor $vendor) {
                $credit = (float) ($vendor->total_credit ?? 0);
                $debit = (float) ($vendor->total_debit ?? 0);
                return number_format($credit - $debit, 2);
            })
            ->addColumn('action', function (Vendor $vendor) {
                $editUrl = route('vendors.edit', $vendor->id);
                $deleteUrl = route('vendors.destroy', $vendor->id);
                $txnUrl = route('vendors.transactions.create', $vendor->id);
                $materialUrl = route('vendors.materials.index', $vendor->id);
                $token = csrf_token();

                return '
                    <div class="d-inline-flex align-items-center flex-nowrap gap-1">
                        <a href="' . $materialUrl . '" class="btn btn-sm vendor-action-btn action-material" title="Materials">
                            <i class="bi bi-box-seam"></i>
                        </a>
                        <a href="' . $txnUrl . '" class="btn btn-sm vendor-action-btn action-transaction" title="Add Credit/Debit">
                            <i class="bi bi-cash-stack"></i>
                        </a>
                        <a href="' . $editUrl . '" class="btn btn-sm vendor-action-btn action-edit" title="Edit Vendor">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form method="POST" action="' . $deleteUrl . '" class="d-inline-block m-0 p-0">
                            <input type="hidden" name="_token" value="' . $token . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm vendor-action-btn action-delete" title="Delete Vendor" onclick="return confirm(\'Delete this vendor?\')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function createTransaction(int $id)
    {
        $vendor = Vendor::query()
            ->withSum(['transactions as total_credit' => function ($q) {
                $q->where('type', 'credit');
            }], 'amount')
            ->withSum(['transactions as total_debit' => function ($q) {
                $q->where('type', 'debit');
            }], 'amount')
            ->findOrFail($id);

        $this->authorizeVendor($vendor);
        $balance = (float) ($vendor->total_credit ?? 0) - (float) ($vendor->total_debit ?? 0);

        return view('vendors.transaction_create', compact('vendor', 'balance'));
    }

    public function storeTransaction(Request $request, int $id)
    {
        $vendor = Vendor::query()->findOrFail($id);
        $this->authorizeVendor($vendor);

        $validated = $request->validate([
            'type' => ['required', 'in:credit,debit'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'transaction_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        VendorTransaction::query()->create([
            'vendor_id' => $vendor->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'] ?? now()->toDateString(),
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('vendors.index')->with('message', 'Vendor transaction saved successfully.');
    }

    public function materialsIndex(int $id)
    {
        $vendor = Vendor::query()->findOrFail($id);
        $this->authorizeVendor($vendor);

        return view('vendors.materials.index', compact('vendor'));
    }

    public function materialsData(int $id)
    {
        $vendor = Vendor::query()->findOrFail($id);
        $this->authorizeVendor($vendor);

        $query = VendorMaterial::query()
            ->where('vendor_id', $vendor->id)
            ->latest('id');

        return DataTables::eloquent($query)
            ->addColumn('date_display', fn (VendorMaterial $material) => optional($material->date)->format('Y-m-d'))
            ->addColumn('qty_display', fn (VendorMaterial $material) => number_format((float) $material->qty, 2))
            ->addColumn('remark_display', fn (VendorMaterial $material) => $material->remark ?: '-')
            ->addColumn('challan_display', fn (VendorMaterial $material) => $material->challan_no ?: '-')
            ->addColumn('action', function (VendorMaterial $material) use ($vendor) {
                $editUrl = route('vendors.materials.edit', [$vendor->id, $material->id]);
                $deleteUrl = route('vendors.materials.destroy', [$vendor->id, $material->id]);
                $token = csrf_token();

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit Material">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Material" onclick="return confirm(\'Delete this material?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function materialsStore(Request $request, int $id)
    {
        $vendor = Vendor::query()->findOrFail($id);
        $this->authorizeVendor($vendor);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'challan_no' => ['nullable', 'string', 'max:100'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'material_name' => ['required', 'string', 'max:150'],
            'remark' => ['nullable', 'string'],
        ]);

        VendorMaterial::query()->create([
            'vendor_id' => $vendor->id,
            'date' => $validated['date'],
            'challan_no' => $validated['challan_no'] ?? null,
            'qty' => $validated['qty'],
            'material_name' => $validated['material_name'],
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()->route('vendors.materials.index', $vendor->id)->with('message', 'Material added successfully.');
    }

    public function materialsEdit(int $vendorId, int $materialId)
    {
        $vendor = Vendor::query()->findOrFail($vendorId);
        $this->authorizeVendor($vendor);
        $material = VendorMaterial::query()
            ->where('vendor_id', $vendor->id)
            ->findOrFail($materialId);

        return view('vendors.materials.edit', compact('vendor', 'material'));
    }

    public function materialsUpdate(Request $request, int $vendorId, int $materialId)
    {
        $vendor = Vendor::query()->findOrFail($vendorId);
        $this->authorizeVendor($vendor);
        $material = VendorMaterial::query()
            ->where('vendor_id', $vendor->id)
            ->findOrFail($materialId);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'challan_no' => ['nullable', 'string', 'max:100'],
            'qty' => ['required', 'numeric', 'min:0.01'],
            'material_name' => ['required', 'string', 'max:150'],
            'remark' => ['nullable', 'string'],
        ]);

        $material->update($validated);

        return redirect()->route('vendors.materials.index', $vendor->id)->with('message', 'Material updated successfully.');
    }

    public function materialsDestroy(int $vendorId, int $materialId)
    {
        $vendor = Vendor::query()->findOrFail($vendorId);
        $this->authorizeVendor($vendor);
        $material = VendorMaterial::query()
            ->where('vendor_id', $vendor->id)
            ->findOrFail($materialId);
        $material->delete();

        return redirect()->route('vendors.materials.index', $vendor->id)->with('message', 'Material deleted successfully.');
    }

    public function create()
    {
        $projects = $this->allowedProjects();
        return view('vendors.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'vendor_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['project_id'] = $this->resolveProjectId($validated['project_id'] ?? null);

        Vendor::query()->create($validated);

        return redirect()->route('vendors.index')->with('message', 'Vendor created successfully.');
    }

    public function edit(int $id)
    {
        $vendor = Vendor::query()->findOrFail($id);
        $this->authorizeVendor($vendor);
        $projects = $this->allowedProjects();

        return view('vendors.edit', compact('vendor', 'projects'));
    }

    public function update(Request $request, int $id)
    {
        $vendor = Vendor::query()->findOrFail($id);
        $this->authorizeVendor($vendor);

        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'vendor_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['project_id'] = $this->resolveProjectId($validated['project_id'] ?? null);

        $vendor->update($validated);

        return redirect()->route('vendors.index')->with('message', 'Vendor updated successfully.');
    }

    public function destroy(int $id)
    {
        $vendor = Vendor::query()->findOrFail($id);
        $this->authorizeVendor($vendor);
        $vendor->delete();

        return redirect()->route('vendors.index')->with('message', 'Vendor deleted successfully.');
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

    private function authorizeVendor(Vendor $vendor): void
    {
        $user = Auth::user();
        if (! empty($user->project_id) && (int) $vendor->project_id !== (int) $user->project_id) {
            abort(403, 'Not allowed.');
        }
    }
}
