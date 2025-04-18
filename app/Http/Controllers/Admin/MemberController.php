<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $this->authorize('adminViewAny', Member::class);
        $members = (new Member)->newQuery();

        if (request()->has('search')) {
            $members->where('name', 'Like', '%' . request()->input('search') . '%')
                ->orWhere('email', 'Like', '%' . request()->input('search') . '%');
        }

        if (request()->query('sort')) {
            $attribute = request()->query('sort');
            $sort_order = 'ASC';
            if (strncmp($attribute, '-', 1) === 0) {
                $sort_order = 'DESC';
                $attribute = substr($attribute, 1);
            }
            $members->orderBy($attribute, $sort_order);
        } else {
            $members->latest();
        }

        $members = $members->paginate(config('admin.paginate.per_page'))
            ->onEachSide(config('admin.paginate.each_side'));

        return Inertia::render('Admin/Member/Index', [
            'items' => $members,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('member create'),
                'edit' => Auth::user()->can('member edit'),
                'delete' => Auth::user()->can('member delete'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $this->authorize('adminCreate', Member::class);
        return Inertia::render('Admin/Member/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('adminCreate', Member::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $member = new Member();
        $member->name = $request->name;
        $member->email = $request->email;
        $member->password = Hash::make($request->password);
        $member->phone = $request->phone;
        $member->birth_date = $request->birth_date;
        $member->gender = $request->gender;
        $member->address = $request->address;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('members', 'public');
            $member->photo = $photoPath;
        }

        $member->save();

        return redirect()->route('admin.members.index')
            ->with('message', __('Member created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $member = Member::findOrFail($id);
        $this->authorize('adminView', $member);

        return Inertia::render('Admin/Member/Show', [
            'member' => $member,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $this->authorize('adminUpdate', $member);

        return Inertia::render('Admin/Member/Edit', [
            'member' => $member,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $this->authorize('adminUpdate', $member);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members,email,' . $member->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $member->name = $request->name;
        $member->email = $request->email;
        if ($request->password) {
            $member->password = Hash::make($request->password);
        }
        $member->phone = $request->phone;
        $member->birth_date = $request->birth_date;
        $member->gender = $request->gender;
        $member->address = $request->address;

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $photoPath = $request->file('photo')->store('members', 'public');
            $member->photo = $photoPath;
        }

        $member->save();

        return redirect()->route('admin.members.index')
            ->with('message', __('Member updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $this->authorize('adminDelete', $member);

        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('message', __('Member deleted successfully.'));
    }
}