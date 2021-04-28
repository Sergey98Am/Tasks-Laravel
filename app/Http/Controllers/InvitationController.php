<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Models\Board;
use App\Models\User;
use App\Notifications\InviteMemberNotification;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function inviteMember(InvitationRequest $request, $boardId)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user && !$user->email_verified_at) {
                throw new \Exception('There is no such user on this site');
            }

            $url = env('FRONT_APP') . '/users/' . $user->id . '/boards/' . $boardId . '?signature=' . sha1($request->email);
//            $url = env('FRONT_APP') . '/boards/' . $boardId;
            $user->notify(new InviteMemberNotification($url));

            return response()->json([
                'message' => 'Invite sent successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function confirmInvitation(Request $request, $userId, $boardId)
    {
        try {
            $user = User::find($userId);
            $board = Board::find($boardId);

            if (!$user || !$board) {
                throw new \Exception('Something went wrong');
            }

            if (!hash_equals((string)$request->signature, sha1($user->email))) {
                throw new \Exception('Unauthorized');
            }

            if ($board->users()->get()->contains($userId)) {
                throw new \Exception('This user already has this board');
            }

            $board->users()->attach($user->id);

            return response()->json([
                'message' => 'You are member of dashboard',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
