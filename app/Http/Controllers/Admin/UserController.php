<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GlobalHelperClass;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    use GlobalHelperClass;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bannedStatus = $request->query('bannedStatus');
        $creationDate = $request->query('date');
        $search = $request->query('search');
        $userIds = $request->query('userIds');
        $data = User::when(isset($search), function($q) use($search){
            $q->where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('email', 'LIKE', '%'.$search.'%');
        })->when(!is_null($bannedStatus), function($q) use($bannedStatus){
            if($bannedStatus == 0){
                $q->whereNull('banned_at');
            }
            if($bannedStatus == 1){
                $q->whereNotNull('banned_at');
            }
        })->when($userIds, function($q) use($userIds){
            $q->whereIn('users.id', $userIds);
        })->when($creationDate, function ($q) use ($creationDate) {
            [$covered_from, $covered_to, $creation_date] = $this->getFilterDate($creationDate);
            if (!empty($covered_from) && !empty($covered_to)) {
                // If both dates are provided, create a date range
                $q->whereBetween('users.created_at', [
                    Carbon::parse($covered_from)->startOfDay(),
                   Carbon::parse($covered_to)->endOfDay(),
                ]);
            } elseif (!empty($covered_from)) {
                // If only one date is provided, create a date range for that day
                $q->whereDate('users.created_at', '=', Carbon::parse($covered_from)->toDateString());
            }
        })->when(isset($search), function($q) use($search) {
            $q->limit(50);
        })
        ->select($this->selectedColumns($search))
        ->get();

        return response(['data' => $data]);
    }
  

    public function banUser(User $user, Request $request)
    {
        $bannedDays = $request->input('bannedDays');
        $bannedDescripton = $request->input('bannedDescripton');
        $bannedEndDate = $request->input('bannedEndDate');
        
        $extension = (int)$bannedDays > 1 ? 'days' : 'day'; 
        $column = ['banned_at' => now(), 'banned_days' => $bannedDays ?? null, 'banned_description' => $bannedDescripton ?? null, 'banned_end_at' => $bannedEndDate ];
        $status = 'banned';
        $message = "{$user->name} is now {$status} for {$bannedDays} {$extension}.";

        if($user->banned_at){
            $column = ['banned_at' => null, 'banned_days' => null,'banned_description' => null, 'banned_end_at' => null];
            $status = 'unbanned';
            $message = "{$user->name} is now {$status}.";
        }

        $user->update($column);
        return response(['message' => $message, 'data' => $user]);
    }
    
    private function selectedColumns($search){
        $columns = '*';
        if(isset($search)){
            $columns = ['name as label', 'id as value'];
        }
        return $columns;
    }

    public function getUsersReportDetails(Request $request){
        $activeUsers = User::whereNull('banned_at')->count();
        $bannedUsers = User::whereNotNull('banned_at')->count();
        
        return response()->json([
            'active_users' => $activeUsers,
            'banned_users' => $bannedUsers,
        ]);
    }
    
}
