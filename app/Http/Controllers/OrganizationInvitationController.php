<?php

namespace App\Http\Controllers;

use App\Models\OrganizationInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationInvitationController extends Controller
{
    /**
     * Show the invitation acceptance page
     */
    public function show(string $token)
    {
        $invitation = OrganizationInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $invitation->load('organization');

        return view('organization.invitation.show', [
            'invitation' => $invitation,
        ]);
    }

    /**
     * Accept an invitation
     */
    public function accept(Request $request, string $token)
    {
        $invitation = OrganizationInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        DB::beginTransaction();

        try {
            // Get or create user
            $user = Auth::user();

            if (!$user) {
                // If user is not logged in, check if user with email exists
                $user = User::where('email', $invitation->email)->first();

                if (!$user) {
                    // Redirect to register with invitation token
                    return redirect()->route('register', ['invitation' => $token])
                        ->with('invitation_email', $invitation->email);
                }

                // User exists but not logged in - redirect to login
                return redirect()->route('login')
                    ->with('invitation_token', $token)
                    ->with('message', 'Please log in to accept the invitation.');
            }

            // Verify email matches
            if ($user->email !== $invitation->email) {
                return back()->with('error', 'This invitation was sent to a different email address.');
            }

            // Add user to organization
            $invitation->organization->members()->attach($user->id, [
                'role' => $invitation->role,
                'invited_by' => $invitation->invited_by,
                'invited_at' => $invitation->created_at,
                'accepted_at' => now(),
                'is_active' => true,
            ]);

            // Mark invitation as accepted
            $invitation->update([
                'accepted_at' => now(),
            ]);

            // Set as default organization if user doesn't have one
            if (!$user->default_organization_id) {
                User::where('id', $user->id)->update([
                    'default_organization_id' => $invitation->organization_id,
                ]);
            }

            DB::commit();

            return redirect()->route('organizations.index')
                ->with('success', "You have successfully joined {$invitation->organization->name}!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to accept invitation. Please try again.');
        }
    }

    /**
     * Decline an invitation
     */
    public function decline(string $token)
    {
        $invitation = OrganizationInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $invitation->delete();

        return redirect()->route('home')
            ->with('success', 'Invitation declined.');
    }
}
