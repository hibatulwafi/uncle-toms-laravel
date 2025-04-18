<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str; // Import Str class
use Illuminate\Support\Facades\Storage; // Import Storage facade
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\Output\QROutputAbstract;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QROptions; // Import QROptions

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $branches = Branch::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('branch_name', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString()
            ->through(function ($branch) {

                $options = new QROptions([
                    'version' => 1, // Pilih versi otomatis berdasarkan panjang data
                    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel' => QRCode::ECC_L,
                ]);
                // Generate QR code image using chillerlan/php-qrcode
                $qrcode = new QRCode($options); // Pass options to QRCode constructor
                $image = $qrcode->render($branch->qrcode);

                $qrcodeImage = 'data:image/png;base64,' . base64_encode($image);

                return [
                    'id' => $branch->id,
                    'branch_name' => $branch->branch_name,
                    'address' => $branch->address,
                    'phone' => $branch->phone,
                    'photo' => $branch->photo,
                    'qrcode' => $branch->qrcode,
                    'qrcode_image' => $image,
                ];
            });

        return Inertia::render('Admin/Branch/Index', [
            'branches' => $branches,
            'filters' => $request->only(['search']),
            'can' => [
                'create' => Auth::user()->can('media create'),
                'edit' => Auth::user()->can('media edit'),
                'delete' => Auth::user()->can('media delete'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Branch/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file photo
            'gmap_link' => 'nullable', // Tambahkan validasi gmap_link
        ]);

        // Generate qrcode (random string 10 characters)
        $qrcode = Str::random(10);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('branch_photos', 'public');
        }

        Branch::create([
            'branch_name' => $request->input('branch_name'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'photo' => $photoPath, // Simpan path foto
            'gmap_link' => $request->input('gmap_link'), // Tambahkan gmap_link
            'qrcode' => $qrcode, // Tambahkan qrcode
        ]);

        return redirect()->route('admin.branches.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        return Inertia::render('Admin/Branch/Edit', [
            'branch' => $branch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'branch_name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gmap_link' => 'nullable',
        ]);

        $data = [
            'branch_name' => $request->input('branch_name'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'gmap_link' => $request->input('gmap_link'),
        ];

        // Handle photo upload if a new photo is provided
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($branch->photo) {
                Storage::disk('public')->delete($branch->photo);
            }

            // Store the new photo
            $photoPath = $request->file('photo')->store('branch_photos', 'public');
            $data['photo'] = $photoPath;
        }

        // Update the branch data
        $branch->update($data);

        return redirect()->route('admin.branches.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('admin.branches.index');
    }
}
