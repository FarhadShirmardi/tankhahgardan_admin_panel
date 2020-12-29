<?php

namespace App\Http\Controllers\Dashboard;

use App\Exceptions\Web\User\SetUserCallDateTimeException;
use App\Helpers\Helpers;
use App\Constants\UserActivationConstant;
use App\Http\Controllers\Controller;
use App\User;
use App\UserActivationLog;
use App\UserActivationState;
use DB;
use Illuminate\Http\Request;

class UserActivationController extends Controller
{
    public function activationIndex($step, Request $request)
    {
        $q = DB::connection('mysql')
            ->table('panel_user_activation_states')
            ->select([
                'users.id',
                'users.name',
                'users.family',
                'users.phone_number',
                'users.created_at as user_created_at',
                'panel_user_activation_states.state',
                'panel_user_activation_states.created_at',
                'panel_user_activation_states.updated_at',
        ])->join(
            'users',
            'panel_user_activation_states.user_id',
            '=',
            'users.id'
        );

        if ($step == UserActivationConstant::STATE_FIRST_STEP_INACTIVE) {
            $q->where(
                'panel_user_activation_states.state',
                UserActivationConstant::STATE_FIRST_STEP_INACTIVE
            );
        } elseif ($step == UserActivationConstant::STATE_SECOND_STEP_INACTIVE) {
            $q->where(
                'panel_user_activation_states.state',
                UserActivationConstant::STATE_SECOND_STEP_INACTIVE
            );
        } elseif ($step == UserActivationConstant::STATE_THIRD_STEP_INACTIVE) {
            $q->where(
                'panel_user_activation_states.state',
                UserActivationConstant::STATE_THIRD_STEP_INACTIVE
            );
        }

        if ($request->has('search')) {
            $q->where(function ($q) use ($request) {
                $q->orWhere(
                    'name',
                    'like',
                    '%'.$request->search.'%'
                )->orWhere(
                    'family',
                    'like',
                    '%'.$request->search.'%'
                )->orWhere(
                    'phone_number',
                    'like',
                    '%'.$request->search.'%'
                );
            });
        }

        $data = $q->orderByDesc('updated_at')->paginate();

        return view('dashboard.user_activation.activation_list', [
            'data' => $data ?? [],
            'step' => $step,
        ]);
    }

    public function activationShow($userId)
    {
        $user = User::select([
            'users.id',
            'users.name',
            'users.family',
            'users.phone_number',
            'panel_user_activation_states.state',
        ])->join(
            'panel_user_activation_states',
            'users.id',
            '=',
            'panel_user_activation_states.user_id'
        )->where(
            'users.id',
            $userId
        )->orderByDesc('panel_user_activation_states.id')
        ->first();

        return view('dashboard.user_activation.activation_show', [
            'data' => $user
        ]);
    }

    public function activationCall(Request $request, $userId)
    {
        $userActivationState = UserActivationState::where('user_id', $userId)->first();

        $smsStateArray = [
            UserActivationConstant::STATE_FIRST_STEP_INACTIVE,
            UserActivationConstant::STATE_SECOND_STEP_INACTIVE,
            UserActivationConstant::STATE_THIRD_STEP_INACTIVE
        ];
        if (!in_array($userActivationState->state, $smsStateArray)) {
            throw new SetUserCallDateTimeException();
        }

        $step = 0;
        DB::transaction(function () use ($userActivationState, &$step, &$request) {
            if ($userActivationState->state == UserActivationConstant::STATE_FIRST_STEP_INACTIVE) {
                $notifyType = UserActivationConstant::STATE_FIRST_CALL;
                $step = UserActivationConstant::STATE_FIRST_STEP_INACTIVE;
            } elseif ($userActivationState->state == UserActivationConstant::STATE_SECOND_STEP_INACTIVE) {
                $notifyType = UserActivationConstant::STATE_SECOND_CALL;
                $step = UserActivationConstant::STATE_SECOND_STEP_INACTIVE;
            } elseif ($userActivationState->state == UserActivationConstant::STATE_THIRD_STEP_INACTIVE) {
                $notifyType = UserActivationConstant::STATE_THIRD_CALL;
                $step = UserActivationConstant::STATE_THIRD_STEP_INACTIVE;
            }

            if (isset($notifyType)) {
                $callDate = Helpers::getEnglishString($request->call_date_time);
                $callDateTimeArray = explode(' ', $callDate);
                $callJalaliDate = str_replace(
                    '/',
                    '-',
                    Helpers::jalaliDateStringToGregorian($callDateTimeArray[0])
                );

                $userActivationState->state = $notifyType;
                $userActivationState->updated_at = $callJalaliDate.' '.$callDateTimeArray[1];
                $userActivationState->save();

                $userActivationLog = new UserActivationLog();
                $userActivationLog->user_id = $userActivationState->user_id;
                $userActivationLog->notify_type = $notifyType;
                $userActivationLog->description = $request->description;
                $userActivationLog->save();
            }
        });

        return redirect()->route('dashboard.users.activation', ['step' => $step]);
    }
}
