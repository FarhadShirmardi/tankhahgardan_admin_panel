<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 3/8/18
 * Time: 5:00 PM
 */

namespace App\Helpers;

use App\Constants\PremiumConstants;
use App\Constants\PremiumDuration;
use App\Constants\ProjectUserState;
use App\Constants\PurchaseType;
use App\Constants\UserPremiumState;
use App\Jobs\UserActivationSmsJob;
use App\Models\File;
use App\Models\Image;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\PromoCode;
use App\Models\SentImage;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\UserStatusLog;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Log;
use RangeException;
use Storage;

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
    ) {
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
    ) {
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
    ) {
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
        $projects = $user->projects()->where('is_owner', true)->get();
        $projectIds = $projects->pluck('id')->toArray();
        /** @var UserStatus $userStatus */
        $userStatus = $user->userStatuses()
            ->where('start_date', '<=', now()->toDateTimeString())
            ->where('end_date', '>=', now()->toDateTimeString())
            ->first();
        $limits = [
            'volume_size_limit' => PremiumConstants::FREE_VOLUME_SIZE,
            'user_count_limit' => PremiumConstants::FREE_USER_COUNT,
            'image_count_limit' => PremiumConstants::IMAGE_COUNT_LIMIT,
            'pdf_count_limit' => PremiumConstants::PDF_COUNT_LIMIT,
            'excel_count_limit' => 0,
        ];
        if ($userStatus) {
            $limits = [
                'volume_size_limit' => $userStatus->volume_size,
                'user_count_limit' => $userStatus->user_count,
                'image_count_limit' => 1000000,
                'activity_count_limit' => 1000000,
                'pdf_count_limit' => 1000000,
                'excel_count_limit' => 1,
            ];
        }

        $images = DB::query()
            ->selectRaw("CONCAT_WS('-',count(*),IFNULL(ROUND(sum(size) / 1024 / 1024),0))")
            ->fromSub(
                DB::query()->fromSub(
                    Image::query()
                        ->withoutTrashed()
                        ->whereIn('project_id', $projectIds)
                        ->select(['path', 'size'])
                        ->getQuery()
                        ->unionAll(
                            SentImage::query()
                                ->withoutTrashed()
                                ->whereIn('project_id', $projectIds)
                                ->select(['path', 'size'])->getQuery()
                        ),
                    'joined_images'
                )->groupBy('path'),
                'images'
            );
        $userCount = ProjectUser::query()
            ->withoutTrashed()
            ->whereIn('project_id', $projectIds)
            ->where('is_owner', false)
            ->whereNotIn('project_user.state', [ProjectUserState::INACTIVE, ProjectUserState::FORBIDDEN])
            ->selectRaw('count(*)')->getQuery();
        $pdfCount = File::query()
            ->whereIn('project_id', $projectIds)
            ->where('type', 'like', '%PDF')
            ->whereDate('created_at', now()->toDateString())
            ->selectRaw('count(*)')
            ->getQuery();
        $query = DB::query()
            ->selectSub($images, 'images')
            ->selectSub($userCount, 'user_count')
            ->selectSub($pdfCount, 'pdf_count');
        $results = $query->first();
        [$results->image_count, $results->volume_size] = explode('-', $results->images);
        unset($results->images);
        $counts = collect();
        $r = new \ReflectionClass(PremiumConstants::class);
        $results->excel_count = 0;
        foreach ($results as $key => $used) {
            $limit = $r->getConstant(strtoupper($key) . '_LIMIT');
            if (!$limit) {
                $counts = $counts->merge([
                    $key . '_remain' => $limits[$key . '_limit'] - $used,
                    $key . '_limit' => $limits[$key . '_limit'],
                ]);
            } else {
                $counts = $userStatus ? $counts->merge([
                    $key . '_remain' => $limits[$key . '_limit'] - $used,
                    $key . '_limit' => $limit,
                ]) : $counts->merge([
                    $key . '_remain' => $limit - $used,
                    $key . '_limit' => $limit,
                ]);
            }
        }
        \Log::info($counts);
        $counts['free_project_remain'] = 100;
        $counts['wallet'] = $user->wallet_amount;
        $counts['reference_charge_amount'] = PremiumConstants::REFERENCE_CHARGE_AMOUNT;
        return $counts;
    }

    public static function getProjectStatus(Project &$project)
    {
        $projectState = $project->projectStatus->first();
        if (!$projectState) {
            return UserPremiumState::FREE;
        } else {
            $carbon = new Carbon();
            $endDate = $carbon->parse($projectState->end_date);
            if ($endDate->lt(now())) {
                return UserPremiumState::EXPIRED_PREMIUM;
            } elseif ($endDate->diffInDays(now()) < PremiumConstants::NEAR_END_THRESHOLD) {
                return UserPremiumState::NEAR_ENDING_PREMIUM;
            } else {
                return UserPremiumState::PREMIUM;
            }
        }
    }

    public static function getProjectCounts(Project &$project)
    {
        /** @var UserStatus $projectStatus */
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
                'pdf_count_limit' => 1000000,
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
                    $key . '_limit' => $limits[$key . '_limit'],
                ]);
            } else {
                if ($projectStatus) {
                    $counts = $counts->merge([
                        $key . '_remain' => $limits[$key . '_limit'] - $used,
                        $key . '_limit' => $limit,
                    ]);
                } else {
                    $counts = $counts->merge([
                        $key . '_remain' => $limit - $used,
                        $key . '_limit' => $limit,
                    ]);
                }
            }
        }
        return $counts;
    }

    public static function calculatePayableAmount(UserStatusLog &$userStatusLog)
    {
        $totalAmount = $userStatusLog->total_amount;
        $addedValueAmount = $userStatusLog->added_value_amount;
        $discountAmount = $userStatusLog->discount_amount;
        $walletAmount = $userStatusLog->wallet_amount;

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

    public static function getUserStatus(User &$user)
    {
        $userState = $user->userStatuses->first();
        if (!$userState) {
            return UserPremiumState::FREE;
        } else {
            $carbon = new Carbon();
            $endDate = $carbon->parse($userState->end_date);
            if ($endDate->lt(now())) {
                return UserPremiumState::EXPIRED_PREMIUM;
            } elseif ($endDate->diffInDays(now()) < PremiumConstants::NEAR_END_THRESHOLD) {
                return UserPremiumState::NEAR_ENDING_PREMIUM;
            } else {
                return UserPremiumState::PREMIUM;
            }
        }
    }

    public static function getMonthName($month)
    {
        switch ((int)$month) {
            case 1:
                return 'فروردین';
            case 2:
                return 'اردیبهشت';
            case 3:
                return 'خرداد';
            case 4:
                return 'تیر';
            case 5:
                return 'مرداد';
            case 6:
                return 'شهریور';
            case 7:
                return 'مهر';
            case 8:
                return 'آبان';
            case 9:
                return 'آذر';
            case 10:
                return 'دی';
            case 11:
                return 'بهمن';
            case 12:
                return 'اسفند';
        }
        return '';
    }

    public static function calculatePercent(&$userStatus, $type)
    {
        $percent = 1;
        /** UserStatus $userStatus */
        if ($userStatus and $type == PurchaseType::UPGRADE) {
            $carbon = new Carbon();
            $startDate = $userStatus->start_date;
            $endDate = $userStatus->end_date;
            try {
                $startDate = Helpers::convertDateTimeToGregorian($userStatus->start_date);
                $endDate = Helpers::convertDateTimeToGregorian($userStatus->end_date);
            } catch (Exception $exception) {

            }
            $startDate = $carbon->parse($startDate);
            $endDate = $carbon->parse($endDate);
            if ($userStatus->price_id == PremiumDuration::SPECIAL) {
                $total = 30;
            } else {
                $total = $startDate->diffInDays($endDate);
            }
            $remain = $endDate->diffInDays(now());
            $percent = $remain / $total;
        }
        return $percent;
    }

    public static function normalizeDate($year, $month, $day, $str = '/'): string
    {
        return implode($str, [
            str_pad($year, 4, '0', STR_PAD_LEFT),
            str_pad($month, 2, '0', STR_PAD_LEFT),
            str_pad($day, 2, '0', STR_PAD_LEFT),
        ]);
    }

    public static function isLeapYear($year)
    {
        if ($year > 0) {
            $y = $year - 474;
        } else {
            $y = 473;
        }
        return ((((($y % 2820) + 474) + 38) * 682) % 2816) < 682;
    }

    public static function getDayCount($year, $month)
    {
        return $month <= 6 ? 31 : ($month < 12 ? 30 : (self::isLeapYear($year) ? 30 : 29));
    }
}
