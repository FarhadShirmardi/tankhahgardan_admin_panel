<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 3/8/18
 * Time: 5:00 PM
 */

namespace App\Helpers;

use App;
use App\Constants\UserActivationConstant;
use App\Jobs\UserActivationSmsJob;
use App\Project;
use App\User;
use App\UserActivationState;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Validator;
use Log;
use RangeException;
use Storage;
use App\Constants\ProjectUserState;
use App\Constants\PremiumConstants;
use App\Constants\ProjectPremiumState;
use App\ProjectStatus;
use Illuminate\Support\Facades\DB;
use App\ProjectStatusLog;
use Illuminate\Support\Str;
use App\PromoCode;

class Helpers
{
    const RESPONSE_MESSAGE = 'message';
    const RESPONSE_STATUS = 'status';
    const RESPONSE_DATA = 'data';

    const EXCEPTION_INTEGRITY_CONSTRAINT = 'Integrity constraint violation';
    const EXCEPTION_NO_QUERY_RESULTS = 'No query results for model';

    const PAGE_SIZE = 100;

    public static function arrayRandomize($array, $number = null)
    {
        $requested = ($number === null) ? 1 : $number;
        $count = count($array);

        if ($requested > $count) {
            throw new RangeException(
                "You requested {$requested} items, but there are only {$count} items available."
            );
        }

        if ($number === null) {
            return $array[array_rand($array)];
        }

        if ((int)$number === 0) {
            return [];
        }

        $keys = (array)array_rand($array, $number);

        $results = [];
        foreach ($keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

    public static function cleanMobileNumber($countryCode, $mobile)
    {
        return $countryCode . substr($mobile, -10);
    }

    public static function createCollectionJson($data)
    {
        return [
            'data' => $data,
        ];
    }

    public static function generateResponse($message, $status = 200, &$data = null)
    {
        return [
            self::RESPONSE_MESSAGE => $message,
            self::RESPONSE_STATUS => $status,
            self::RESPONSE_DATA => $data,
        ];
    }

    /**
     * @param $logicResponse
     * @param JsonResource $jsonResource
     * @param array $metaData
     * @return JsonResponse|mixed
     */
    public static function generateApiException($logicResponse, $jsonResource = null, $metaData = [])
    {
        $metaData += [
            self::RESPONSE_MESSAGE => $logicResponse[self::RESPONSE_MESSAGE] == null
                ? $logicResponse[self::RESPONSE_MESSAGE]
                : [$logicResponse[self::RESPONSE_MESSAGE]],
        ];
        if ($logicResponse[self::RESPONSE_DATA] !== null and $jsonResource !== null) {
            $jsonResource->additional($metaData);
            return $jsonResource;
        } else {
            $metaData += [self::RESPONSE_DATA => $logicResponse[self::RESPONSE_DATA]];
        }
        return \response()->json($metaData, $logicResponse[self::RESPONSE_STATUS]);
    }

    /**
     * add by farhad
     * @param $message
     * @param int $status
     * @param null $data
     * @param string $jsonResourceClassName
     * @param array $metaData
     * @return JsonResponse|mixed
     */
    public static function generateApiResponseByMessage(
        $message,
        $status = 200,
        &$data = null,
        string $jsonResourceClassName = null,
        $metaData = []
    )
    {
        $metaData += [
            self::RESPONSE_MESSAGE => $message == null ? [] : [$message],
        ];
        if ($data !== null and $jsonResourceClassName !== null) {
            if ($data instanceof Collection or
                $data instanceof \Illuminate\Support\Collection or
                $data instanceof LengthAwarePaginator
            ) {
                /** @var JsonResource $jsonResource */
                $jsonResource = ($jsonResourceClassName)::collection($data);
            } else {
                $jsonResource = new $jsonResourceClassName($data);
            }
            $jsonResource->additional($metaData);
            return $jsonResource->response()->setStatusCode($status);
        } else {
            $metaData += [self::RESPONSE_DATA => $data == null ? [] : $data];
        }
        return \response()->json($metaData, $status);
    }

    public static function generateBladeResponseMessage($logicResponse)
    {
        if ($logicResponse['status'] >= 200 && $logicResponse['status'] < 300) {
            return [
                'success' => $logicResponse['message'],
            ];
        } else {
            return [
                'message' => $logicResponse['message'],
            ];
        }
    }

    public static function saveUploadedFile(
        Request &$request,
        Model &$data,
        $fileName = null,
        $path = 'public',
        $fileColumn = 'image'
    )
    {
        if ($request->hasFile($fileColumn)) {
            $fileExtension = $request->file($fileColumn)->getClientOriginalExtension();
            if ($fileName == null) {
                $path = $request->file($fileColumn)->store($path);
            } else {
                $path = $request->file($fileColumn)->storeAs($path, $fileName . $data->id . '.' . $fileExtension);
            }

            $data->fileColumn = str_replace("public/", "", $path);
            $data->save();
        }
    }

    public static function jalaliDateStringToGregorian($dateString, $delimiter = "/")
    {
        if ($dateString == null) {
            return null;
        }
        $gregorian2Jalali = new Gregorian2Jalali();
        $startDateArr = explode($delimiter, $dateString);
        return $gregorian2Jalali->jalaliToGregorian($startDateArr[0], $startDateArr[1], $startDateArr[2], '1');
    }

    public static function convertDateTimeToGregorian($dateTimeString)
    {
        if (!$dateTimeString) {
            return null;
        }
        $dateTimeString = explode(' ', $dateTimeString);
        $date = $dateTimeString[0];
        $time = $dateTimeString[1];
        $date = self::jalaliDateStringToGregorian($date);
        return $date . ' ' . $time;
    }

    public static function gregorianDateStringToJalali($dateString, $delimiter = '/')
    {
        if ($dateString == null) {
            return null;
        }
        $gregorian2Jalali = new Gregorian2Jalali();
        if (strpos($dateString, '-')) {
            $delimiter = '-';
        }
        $startDataArr = explode($delimiter, $dateString);
        return $gregorian2Jalali->gregorianToJalali($startDataArr[0], $startDataArr[1], $startDataArr[2], '1');
    }

    public static function saveToStorage($diskName, UploadedFile $file, $fileName, $hash = false)
    {
        if ($file->isValid()) {
            $fileName = $fileName . '__' . rand(10000, 99999);

            if ($hash) {
                $hash = md5($fileName . time());
            }

            $jpg_url = $fileName . $hash . '.' . $file->getClientOriginalExtension();
            Storage::disk($diskName)->put($jpg_url, file_get_contents($file));
        } else {
            return false;
        }

        return $jpg_url;
    }

    public static function getFullFilePath($diskName, $fileName)
    {
        $storagePath = Storage::disk($diskName)->getDriver()->getAdapter()->getPathPrefix();
        return $storagePath . $fileName;
    }

    public static function checkRePassword(Request &$request)
    {
        if ($request->input('password') === $request->input('re_password', null)) {
            return true;
        } else {
            return Helpers::generateResponse(
                'پسورد ها با هم مطابقت ندارند.آپدیت انجام نشد! دوباره تلاش کنید.',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public static function round($mFloat)
    {
        $mFloat = (float)number_format((float)$mFloat, 2, '.', '');
        $decimal = floor($mFloat);
        $afterPoint = $mFloat - $decimal;
        if ($afterPoint >= 0.50) {
            $mFloat += 1;
        } elseif ($afterPoint > 0 and $afterPoint < 0.50) {
            $mFloat -= 1;
        }

        return (int)$mFloat;
    }

    public static function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        while ($start_date->lte($end_date)) {
            $dates[] = $start_date->addDay()->copy();
        }

        return $dates;
    }

    public static function getPersianDay($dayOfWeek)
    {
        switch ($dayOfWeek) {
            case 1:
                return 'دوشنبه';
            case 2:
                return 'سه‌شنبه';
            case 3:
                return 'چهارشنبه';
            case 4:
                return 'پنج‌شنبه';
            case 5:
                return 'جمعه';
            case 6:
                return 'شنبه';
            case 7:
                return 'یکشنبه';
        }
        return ' - ';
    }

    public static function normalizePageSize(Request &$request)
    {
        $pageSize = (int)$request->input('per_page', self::PAGE_SIZE);
        return min($pageSize, self::PAGE_SIZE);
    }

    public static function exceptionHandler(Exception $exception)
    {
        if (App::environment() == 'local') {
            $response = self::generateResponse(
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return ($response);
        }

        Log::error(json_encode($exception, JSON_PRETTY_PRINT));
        if ($exception instanceof QueryException and
            (App::environment() == 'production' || App::environment() == 'testing')) {
            if (str_contains($exception->getMessage(), Helpers::EXCEPTION_INTEGRITY_CONSTRAINT)) {
                $message = trans('message.integrity_constraint_failed');
            } else {
                $message = trans('message.database_error');
            }
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        } elseif ($exception instanceof ModelNotFoundException) {
            $message = trans('message.no_results');
            $statusCode = Response::HTTP_NOT_FOUND;
        } elseif ($exception instanceof App\Repositories\V1\Exceptions\RepositoryException) {
            $message = $exception->getMessage();
            $statusCode = $exception->getCode();
        } else {
            $message = $exception->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response = self::generateResponse(
            $message,
            $statusCode
        );
        return ($response);
    }

    public static function paginateCollection($collection, $perPage = null, $pageName = 'page', $fragment = null)
    {
        $perPage = $perPage == null ? self::PAGE_SIZE : $perPage;
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $query,
                'fragment' => $fragment,
            ]
        );

        return $paginator;
    }

    public static function validateBase64String($string)
    {
        $data = explode(',', $string);
        if (count($data) >= 2) {
            $format = explode('/', $data[0]);
            if ($format[0] == 'data:image') {
                return true;
            }
        }
        return false;
    }

    public static function diffForHuman($startDate, $endDate)
    {
        Carbon::setLocale('fa');
        $diff = Carbon::createFromTimeString($startDate)->diffForHumans($endDate, true, true, 2);
        return self::getPersianString($diff);
    }

    public static function getPersianString($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);
        $convertedPersianNums = str_replace($num, $persian, $string);
        return $convertedPersianNums;
    }

    public static function getEnglishString($string)
    {
        $persianNumbers = ['۰', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $arabicNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);
        $string = str_replace($persianNumbers, $num, $string);
        $string = str_replace($arabicNumbers, $num, $string);
        return $string;
    }

    /**
     * @param $string
     * @param $query
     * @return bool
     */
    public static function strStartsWith($string, $query)
    {
        return substr($string, 0, strlen($query)) === $query;
    }

    /**
     * @param $phoneNumber
     * @return bool|string
     */
    public static function formatPhoneNumber($phoneNumber)
    {
        if (self::strStartsWith($phoneNumber, '0')) {
            $phoneNumber = (string)(int)$phoneNumber;
        } elseif (self::strStartsWith($phoneNumber, '+98') and strlen($phoneNumber) != 10) {
            $phoneNumber = substr($phoneNumber, 3);
        } elseif (self::strStartsWith($phoneNumber, '98') and strlen($phoneNumber) != 10) {
            $phoneNumber = substr($phoneNumber, 2);
        }
        return $phoneNumber;
    }

    public static function expireOtp(User &$user)
    {
        $user->vcode_generation_time =
            $user->vcode_generation_time->subMinutes(5);
        $user->save();
        return $user;
    }

    /**
     * @param $phoneNumber
     * @return bool|string
     */
    public static function reformatPhoneNumber($phoneNumber)
    {
        if (!self::strStartsWith($phoneNumber, '0')) {
            $phoneNumber = '0' . $phoneNumber;
        }
        return $phoneNumber;
    }

    public static function generateRandomToken(
        $length = 10,
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    )
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateValidationError(Validator $validator)
    {
        return response()->json(
            [
                'error' => $validator->errors(),
                'message' => $validator->errors()->all(),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function convertDateTimeToJalali($dateTimeString)
    {
        $dateTimeString = explode(' ', $dateTimeString);
        $date = $dateTimeString[0];
        $time = $dateTimeString[1];
        $date = self::gregorianDateStringToJalali($date);
        return $date . ' ' . $time;
    }

    public static function formatNumber($number)
    {
        if ($number < 0) {
            return '(' . number_format($number * -1) . ')';
        }
        return number_format($number);
    }

    public static function setUserStatus(&$userStates, $state, $addHours = null, $sendSms = false, $smsText = null)
    {
        if ($sendSms == true and $smsText == null) {
            throw new Exception('Sms text can not be null!');
        }

        foreach ($userStates as $userState) {
            $user = User::findOrFail($userState->user_id);
            $projects = $user->projects()->get();

            $dataCounter = 0;

            /** @var Project $project */
            foreach ($projects as $project) {

                if ($addHours === null) {
                    $time = $userState->updated_at->toDateTimeString();
                } else {
                    $time = $userState->updated_at->addHours($addHours)->toDateString();
                }

                $dataCounter += $project->notes()->where(
                    'notes.created_at',
                    '>',
                    $time
                )->count();
                $dataCounter += $project->payments()
                    ->where(
                        'payments.created_at',
                        '>',
                        $time
                    )->count();
                $dataCounter += $project->receives()
                    ->where(
                        'receives.created_at',
                        '>',
                        $time
                    )->count();
            }

            if ($dataCounter == 0) {
                //Update user activation state
                $userActivationState = UserActivationState::where(
                    'user_id',
                    $user->id
                )->first();
                $userActivationState->state = $state;
                $userActivationState->save();

                if ($sendSms == true) {
                    if (app()->environment() != 'production') {
                        $delayTime = now();
                    } else {
                        $delayTime = now()->addHours(12);
                    }

                    dispatch(new UserActivationSmsJob(
                        $user,
                        $smsText,
                        $state
                    ))->onQueue('activationSms')->delay($delayTime);
                }
            } else if ($state == UserActivationConstant::STATE_NPS_SMS or
                $state == UserActivationConstant::STATE_REFERRAL_SMS) {
                //Update user activation state
                $userActivationState = UserActivationState::where(
                    'user_id',
                    $user->id
                )->first();
                $userActivationState->state = $state;
                $userActivationState->save();

                if ($sendSms == true) {
                    if (app()->environment() != 'production') {
                        $delayTime = now();
                    } else {
                        $delayTime = now()->addHours(12);
                    }

                    dispatch(new UserActivationSmsJob(
                        $user,
                        $smsText,
                        $state
                    ))->onQueue('activationSms')->delay($delayTime);
                }

            }
        }
    }

    public static function getPremiumCounts(User &$user, Project &$project)
    {
        $userCounts = self::getUserCounts($user);
        $projectCounts = self::getProjectCounts($project);
        $counts = collect();
        $counts = $counts->merge($userCounts)->merge($projectCounts);
        return $counts;
    }

    public static function getUserCounts(User &$user)
    {
        /** @var Project $projects */
        $projects = $user->projects()->where('state', ProjectUserState::ACTIVE)->where('is_owner', true)->get();

        $userCount = [
            'free_project_used' => 0,
            'free_project_remain' => 0,
            'wallet' => $user->wallet_amount,
            'reference_charge_amount' => PremiumConstants::REFERENCE_CHARGE_AMOUNT
        ];
        $projects->each(function ($project, $key) use (&$userCount) {
            if (in_array($project->premium_state, [ProjectPremiumState::FREE, ProjectPremiumState::EXPIRED_PREMIUM])) {
                $userCount['free_project_used']++;
            }
        });
        $userCount['free_project_remain'] = PremiumConstants::FREE_PROJECT_LIMIT - $userCount['free_project_used'];
        unset($userCount['free_project_used']);
        return $userCount;
    }

    public static function getProjectStatus(Project &$project)
    {
        $projectState = $project->projectStatus->first();
        if (!$projectState) {
            return ProjectPremiumState::FREE;
        } else {
            $carbon = new Carbon();
            $endDate = $carbon->parse($projectState->end_date);
            if ($endDate->lt(now())) {
                return ProjectPremiumState::EXPIRED_PREMIUM;
            } elseif ($endDate->diffInDays(now()) < PremiumConstants::NEAR_END_THRESHOLD) {
                return ProjectPremiumState::NEAR_ENDING_PREMIUM;
            } else {
                return ProjectPremiumState::PREMIUM;
            }
        }
    }

    public static function getProjectCounts(Project &$project)
    {
        /** @var ProjectStatus $projectStatus */
        $projectStatus = $project->projectStatus
            ->where('start_date', '<=', now()->toDateTimeString())
            ->where('end_date', '>=', now()->toDateTimeString())
            ->first();
        $limits = [
            'volume_size_limit' => PremiumConstants::FREE_VOLUME_SIZE,
            'user_count_limit' => PremiumConstants::FREE_USER_COUNT,
            'image_count_limit' => PremiumConstants::IMAGE_COUNT_LIMIT,
            'pdf_count_limit' => PremiumConstants::PDF_COUNT_LIMIT,
            'activity_count_limit' => PremiumConstants::ACTIVITY_COUNT_LIMIT,
        ];
        if ($projectStatus) {
            $limits = [
                'volume_size_limit' => $projectStatus->volume_size,
                'user_count_limit' => $projectStatus->user_count,
                'image_count_limit' => 1000000,
                'activity_count_limit' => 1000000,
                'pdf_count_limit' => 1000000
            ];
        }
        $activity = $project->activities()->selectRaw('count(*)')->getQuery();
        $imagesSize = $project->images()->selectRaw('ROUND(sum(size)/1024/1024)')->getQuery();
        $imagesCount = $project->images()->selectRaw('count(*)')->getQuery();
        $userCount = $project->projectUser()->where('is_owner', false)
            ->where('project_user.state', '<>', ProjectUserState::INACTIVE)
            ->selectRaw('count(*)')->getQuery();
        $pdfCount = $project->files()
            ->where('type', 'like', '%PDF')
            ->whereDate('created_at', now()->toDateString())
            ->selectRaw('count(*)')
            ->getQuery();
        $query = DB::query()
            ->selectSub($imagesSize, 'volume_size')
            ->selectSub($imagesCount, 'image_count')
            ->selectSub($activity, 'activity_count')
            ->selectSub($userCount, 'user_count')
            ->selectSub($pdfCount, 'pdf_count');
        $results = $query->first();
        $counts = collect();
        $r = new ReflectionClass(PremiumConstants::class);
        foreach ($results as $key => $used) {
            $limit = $r->getConstant(strtoupper($key) . '_LIMIT');
            if (!$limit) {
                $counts = $counts->merge([
                    $key . '_remain' => $limits[$key . '_limit'] - $used,
                    $key . '_limit' => $limits[$key . '_limit']
                ]);
            } else {
                if ($projectStatus) {
                    $counts = $counts->merge([
                        $key . '_remain' => $limits[$key . '_limit'] - $used,
                        $key . '_limit' => $limit
                    ]);
                } else {
                    $counts = $counts->merge([
                        $key . '_remain' => $limit - $used,
                        $key . '_limit' => $limit
                    ]);
                }
            }
        }
        return $counts;
    }

    public static function calculatePayableAmount(ProjectStatusLog &$projectStatusLog)
    {
        $totalAmount = $projectStatusLog->total_amount;
        $addedValueAmount = $projectStatusLog->added_value_amount;
        $discountAmount = $projectStatusLog->discount_amount;
        $walletAmount = $projectStatusLog->wallet_amount;

        return self::getPayableAmount($totalAmount, $addedValueAmount, $discountAmount, $walletAmount);
    }

    public static function getPayableAmount($totalAmount, $addedValueAmount, $discountAmount, $walletAmount)
    {
        return 10 * floor(($totalAmount + $addedValueAmount - $discountAmount - $walletAmount) / 10);
    }

    public static function getTextWithCurrency(Project &$project, $text)
    {
        return $text . ' (' . $project->currency_text . ')';
    }

    public static function generatePromoCode()
    {
        do {
            $code = Str::random(7);
            $promoCode = PromoCode::where('code', $code)->exists();
        } while ($promoCode);

        return $code;
    }
}
