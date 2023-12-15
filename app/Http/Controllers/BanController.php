<?php

namespace App\Http\Controllers;

use App\Mail\BannedEmail;
use App\Mail\UnbannedEmail;
use App\Models\Ban;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BanController extends Controller
{
    public function list()
    {

        try {
            $this->authorize('view_bans_list');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $bans = Ban::all();
        return response()->json($bans);
    }

    public function retrieve(Ban $ban)
    {
        try {
            $this->authorize('view_ban');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        return response()->json($ban);
    }

    public function create(Request $request)
    {
        try {
            $this->authorize('ban_user');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int',
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }


        $user = User::find($request->user_id);

        Mail::to($user->email)->send(new BannedEmail(
                reason: $request->reason,
                start_date: $request->start_date,
                end_date: $request->end_date)
        );

        $ban = new Ban($request->all());
        $ban->admin_id = Auth::id();
        $ban->save();

        return response()->json($ban, 201);
    }

    public function destroy(Request $request, Ban $ban)
    {
        try {
            $this->authorize('unban_user');
        } catch (AuthorizationException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }

        $user = User::find($request->user_id);
        Mail::to($user->email)->send(new UnbannedEmail());

        $ban->end_date = Carbon::now();
        $ban->save();
        return response()->json($ban, 200);
    }
}
