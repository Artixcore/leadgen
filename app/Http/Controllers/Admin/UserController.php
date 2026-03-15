<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Models\User;
use App\UserStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->with(['roles', 'statusChangedBy'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user (status).
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user's status.
     */
    public function update(UpdateUserStatusRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'status' => UserStatus::from($request->validated('status')),
            'status_changed_at' => now(),
            'status_changed_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('User status updated.'));
    }
}
