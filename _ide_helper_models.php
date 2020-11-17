<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\AccountingCode
 *
 * @property int $id
 * @property int $type
 * @property string $code
 * @property int $level
 * @property int $accounting_code_id
 * @property string $accounting_code_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Model|Eloquent $hasAccountingCode
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|AccountingCodes onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|AccountingCodes whereAccountingCodeId($value)
 * @method static Builder|AccountingCodes whereAccountingCodeType($value)
 * @method static Builder|AccountingCodes whereCode($value)
 * @method static Builder|AccountingCodes whereCreatedAt($value)
 * @method static Builder|AccountingCodes whereDeletedAt($value)
 * @method static Builder|AccountingCodes whereId($value)
 * @method static Builder|AccountingCodes whereLevel($value)
 * @method static Builder|AccountingCodes whereType($value)
 * @method static Builder|AccountingCodes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AccountingCodes withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AccountingCodes withoutTrashed()
 * @mixin Eloquent
 * @property int $model_id
 * @property string $model_type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountingCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountingCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountingCode query()
 */
	class AccountingCode extends \Eloquent {}
}

namespace App{
/**
 * App\AccountingSoftware
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|AccountingSoftwares whereCreatedAt($value)
 * @method static Builder|AccountingSoftwares whereId($value)
 * @method static Builder|AccountingSoftwares whereName($value)
 * @method static Builder|AccountingSoftwares whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountingSoftware newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountingSoftware newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountingSoftware query()
 */
	class AccountingSoftware extends \Eloquent {}
}

namespace App{
/**
 * App\AccountTitle
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $project_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTitles whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|AccountingCode[] $accountingCodes
 * @method static bool|null forceDelete()
 * @method static Builder|AccountTitles onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|AccountTitles withTrashed()
 * @method static Builder|AccountTitles withoutTrashed()
 * @property-read int|null $accounting_codes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountTitle query()
 */
	class AccountTitle extends \Eloquent {}
}

namespace App{
/**
 * App\Advertisement
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertisement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertisement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Advertisement query()
 */
	class Advertisement extends \Eloquent {}
}

namespace App{
/**
 * App\ApplicationVersion
 *
 * @property mixed $release_date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApplicationVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApplicationVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApplicationVersion query()
 */
	class ApplicationVersion extends \Eloquent {}
}

namespace App{
/**
 * App\Bank
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Bank onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Bank query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Bank withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Bank withoutTrashed()
 */
	class Bank extends \Eloquent {}
}

namespace App{
/**
 * App\BankPattern
 *
 * @property-read \App\Bank $bank
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BankPattern newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BankPattern newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BankPattern query()
 */
	class BankPattern extends \Eloquent {}
}

namespace App{
/**
 * App\Campaign
 *
 * @property int $id
 * @property string $name
 * @property string $start_date
 * @property string|null $end_date
 * @property int $count
 * @property string $symbol
 * @property int|null $panel_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PromoCode[] $promoCodes
 * @property-read int|null $promo_codes_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign wherePanelUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Campaign whereUpdatedAt($value)
 */
	class Campaign extends \Eloquent {}
}

namespace App{
/**
 * App\City
 *
 * @property int $id
 * @property int $state_id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereStateId($value)
 */
	class City extends \Eloquent {}
}

namespace App{
/**
 * App\Comment
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $date
 * @property string|null $response_date
 * @property int $source
 * @property int|null $user_id
 * @property int|null $panel_user_id
 * @property int|null $feedback_title_id
 * @property string|null $phone_number
 * @property string $text
 * @property string|null $response
 * @property int $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereFeedbackTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment wherePanelUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereResponseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App{
/**
 * App\DefaultAccountTitle
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DefaultAccountTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DefaultAccountTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DefaultAccountTitle query()
 */
	class DefaultAccountTitle extends \Eloquent {}
}

namespace App{
/**
 * App\Device
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $serial
 * @property string|null $model
 * @property int|null $platform
 * @property string|null $os_version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereUserId($value)
 */
	class Device extends \Eloquent {}
}

namespace App{
/**
 * App\ExcelExport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExcelExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExcelExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ExcelExport query()
 */
	class ExcelExport extends \Eloquent {}
}

namespace App{
/**
 * App\Feedback
 *
 * @property int $id
 * @property int $user_id
 * @property int $feedback_title_id
 * @property string $text
 * @property int|null $feedback_response_id
 * @property int|null $device_id
 * @property string|null $application_version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $state
 * @property-read \App\Device $device
 * @property-read \App\FeedbackResponse $feedbackResponse
 * @property-read \App\FeedbackTitle $feedbackTitles
 * @property-read int|null $images_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereApplicationVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereFeedbackResponseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereFeedbackTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback whereUserId($value)
 */
	class Feedback extends \Eloquent {}
}

namespace App{
/**
 * App\FeedbackResponse
 *
 * @property int $id
 * @property int $panel_user_id
 * @property string $text
 * @property int $score
 * @property string|null $read_at
 * @property string|null $response_updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\PanelUser $panelUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse wherePanelUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse whereResponseUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse whereUpdatedAt($value)
 */
	class FeedbackResponse extends \Eloquent {}
}

namespace App{
/**
 * App\FeedbackTitle
 *
 * @property int $id
 * @property string $title
 * @property string|null $tag
 * @property string|null $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle whereUpdatedAt($value)
 */
	class FeedbackTitle extends \Eloquent {}
}

namespace App{
/**
 * App\File
 *
 * @property int $id
 * @property string|null $password
 * @property string $path
 * @property string $tag
 * @property string|null $properties
 * @property int $model_id
 * @property string|null $type
 * @property string $model_update_time
 * @property int $project_id
 * @property int $creator_user_id
 * @property int $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereModelUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUpdatedAt($value)
 */
	class File extends \Eloquent {}
}

namespace App{
/**
 * App\Image
 *
 * @property int $id
 * @property int $model_id
 * @property string $model_type
 * @property string $path
 * @property int $size
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Model|Eloquent $hasImage
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Images onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Images whereCreatedAt($value)
 * @method static Builder|Images whereDeletedAt($value)
 * @method static Builder|Images whereId($value)
 * @method static Builder|Images whereModelId($value)
 * @method static Builder|Images whereModelType($value)
 * @method static Builder|Images wherePath($value)
 * @method static Builder|Images whereSize($value)
 * @method static Builder|Images whereUpdatedAt($value)
 * @method static Builder|Images whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Images withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Images withoutTrashed()
 * @mixin Eloquent
 * @property int|null $project_id
 * @property bool $sync_flag
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereSyncFlag($value)
 */
	class Image extends \Eloquent {}
}

namespace App{
/**
 * App\Imprest
 *
 * @property int $id
 * @property int $imprest_number
 * @property int $state
 * @property string $start_date
 * @property string $end_date
 * @property string|null $description
 * @property int $project_id
 * @property int $creator_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \App\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receive[] $receives
 * @property-read int|null $receives_count
 * @property-read \App\SentImprest $sentImprest
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Imprest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereImprestNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Imprest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Imprest withoutTrashed()
 */
	class Imprest extends \Eloquent {}
}

namespace App{
/**
 * App\Memo
 *
 * @property mixed $date
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Memo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Memo newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Memo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Memo query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Memo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Memo withoutTrashed()
 */
	class Memo extends \Eloquent {}
}

namespace App{
/**
 * App\Note
 *
 * @property int $id
 * @property int $project_id
 * @property int $creator_user_id
 * @property string $text
 * @property string $date
 * @property bool $is_done
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $description
 * @property-read \App\Reminder $reminder
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Note onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereIsDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Note withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Note withoutTrashed()
 */
	class Note extends \Eloquent {}
}

namespace App{
/**
 * App\PanelUser
 *
 * @property int $id
 * @property string $name
 * @property string $phone_number
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $type
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser whereUpdatedAt($value)
 */
	class PanelUser extends \Eloquent {}
}

namespace App{
/**
 * App\PasswordReset
 *
 * @property int $id
 * @property string $phone_number
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builders|Password whereCreatedAt($value)
 * @method static Builders|Password whereId($value)
 * @method static Builders|Password wherePhoneNumber($value)
 * @method static Builders|Password whereToken($value)
 * @method static Builders|Password whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset query()
 */
	class PasswordReset extends \Eloquent {}
}

namespace App{
/**
 * App\Payment
 *
 * @property int $id
 * @property float $amount
 * @property string $description
 * @property string $date
 * @property string $payment_subject
 * @property int|null $imprest_id
 * @property int $project_id
 * @property int $creator_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\Imprest $imprest
 * @property-read \App\SentPayment $sentPayment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TurnoverDetail[] $turnoverDetails
 * @property-read int|null $turnover_details_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereImprestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment wherePaymentSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Payment withoutTrashed()
 */
	class Payment extends \Eloquent {}
}

namespace App{
/**
 * App\PendingInvitation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PendingInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PendingInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PendingInvitation query()
 */
	class PendingInvitation extends \Eloquent {}
}

namespace App{
/**
 * App\Poll
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Poll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Poll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Poll query()
 */
	class Poll extends \Eloquent {}
}

namespace App{
/**
 * App\Project
 *
 * @property int $id
 * @property string $name
 * @property int|null $accounting_software_id
 * @property int|null $state_id
 * @property int|null $city_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $currency
 * @property bool $is_archived
 * @property string|null $archive_time
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AccountTitle[] $accountTitles
 * @property-read int|null $account_titles_count
 * @property-read \App\AccountingSoftware|null $accountingSoftware
 * @property-read \App\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Imprest[] $imprests
 * @property-read int|null $imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Memo[] $memos
 * @property-read int|null $memos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProjectUser[] $projectUser
 * @property-read int|null $project_user_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receive[] $receives
 * @property-read int|null $receives_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Reminder[] $reminders
 * @property-read int|null $reminders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentImprest[] $sentImprests
 * @property-read int|null $sent_imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentPayment[] $sentPayments
 * @property-read int|null $sent_payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentReceive[] $sentReceives
 * @property-read int|null $sent_receives_count
 * @property-read \App\State $state
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereAccountingSoftwareId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereArchiveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Project withoutTrashed()
 */
	class Project extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectInvite
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectInvite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectInvite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectInvite query()
 */
	class ProjectInvite extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectInviteNotification
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectInviteNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectInviteNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectInviteNotification query()
 */
	class ProjectInviteNotification extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectReport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectReport query()
 */
	class ProjectReport extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectStatus
 *
 * @property int $id
 * @property int $project_id
 * @property string $start_date
 * @property string $end_date
 * @property int $volume_size
 * @property int $user_count
 * @property int $price_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus wherePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereUserCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatus whereVolumeSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectStatus withoutTrashed()
 */
	class ProjectStatus extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectStatusLog
 *
 * @property int $id
 * @property int $project_id
 * @property string $start_date
 * @property string $end_date
 * @property int $volume_size
 * @property int $user_count
 * @property int|null $transaction_id
 * @property int $wallet_amount
 * @property int $total_amount
 * @property int $type
 * @property int $price_id
 * @property int $status
 * @property int|null $campaign_id
 * @property int|null $promo_code_id
 * @property int $discount_amount
 * @property int $added_value_amount
 * @property string $trace_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $payable_amount
 * @property-read \App\Project $project
 * @property-read \App\PromoCode $promoCode
 * @property-read \App\Transaction $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereAddedValueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog wherePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog wherePromoCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereTraceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereUserCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereVolumeSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectStatusLog whereWalletAmount($value)
 */
	class ProjectStatusLog extends \Eloquent {}
}

namespace App{
/**
 * App\ProjectUser
 *
 * @property int $id
 * @property int $project_id
 * @property int $user_id
 * @property bool $is_owner
 * @property int $state
 * @property string|null $expired_date
 * @property string $added_date
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AccountingCode[] $accountingCodes
 * @property-read int|null $accounting_codes_count
 * @property-read \App\Project $project
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereAddedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereExpiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectUser withoutTrashed()
 */
	class ProjectUser extends \Eloquent {}
}

namespace App{
/**
 * App\PromoCode
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $code
 * @property int|null $user_id
 * @property int|null $discount_percent
 * @property int|null $max_discount
 * @property int $max_count
 * @property string|null $expire_at
 * @property string|null $text
 * @property int $reserve_count
 * @property int|null $panel_user_id
 * @property string $start_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Campaign $campaign
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereMaxCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereMaxDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode wherePanelUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereReserveCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PromoCode whereUserId($value)
 */
	class PromoCode extends \Eloquent {}
}

namespace App{
/**
 * App\Receive
 *
 * @property int $id
 * @property float $amount
 * @property string $description
 * @property string $date
 * @property string $receive_subject
 * @property int|null $imprest_id
 * @property int $project_id
 * @property int $creator_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\Imprest $imprest
 * @property-read \App\SentReceive $sentReceive
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentTurnoverDetail[] $sentTurnoverDetails
 * @property-read int|null $sent_turnover_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TurnoverDetail[] $turnoverDetails
 * @property-read int|null $turnover_details_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Receive onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereImprestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereReceiveSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Receive withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Receive withoutTrashed()
 */
	class Receive extends \Eloquent {}
}

namespace App{
/**
 * App\Reminder
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReminderInterval[] $intervals
 * @property-read int|null $intervals_count
 * @property-read \App\Note $note
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReminderState[] $reminderState
 * @property-read int|null $reminder_state_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reminder newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Reminder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reminder query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Reminder withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Reminder withoutTrashed()
 */
	class Reminder extends \Eloquent {}
}

namespace App{
/**
 * App\ReminderInterval
 *
 * @property mixed $end_date
 * @property mixed $start_date
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReminderInterval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReminderInterval newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ReminderInterval onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReminderInterval query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ReminderInterval withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ReminderInterval withoutTrashed()
 */
	class ReminderInterval extends \Eloquent {}
}

namespace App{
/**
 * App\ReminderState
 *
 * @property mixed $date
 * @property-read mixed $done_date
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReminderState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReminderState newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ReminderState onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReminderState query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ReminderState withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ReminderState withoutTrashed()
 */
	class ReminderState extends \Eloquent {}
}

namespace App{
/**
 * App\SentImage
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $hasImage
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentImage newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\SentImage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentImage query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\SentImage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\SentImage withoutTrashed()
 */
	class SentImage extends \Eloquent {}
}

namespace App{
/**
 * App\SentImprest
 *
 * @property mixed $end_date
 * @property mixed $start_date
 * @property-read \App\Imprest $imprest
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentPayment[] $payments
 * @property-read int|null $payments_count
 * @property-read \App\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentReceive[] $receives
 * @property-read int|null $receives_count
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentImprest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentImprest newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\SentImprest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentImprest query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\SentImprest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\SentImprest withoutTrashed()
 */
	class SentImprest extends \Eloquent {}
}

namespace App{
/**
 * App\SentPayment
 *
 * @property mixed $date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentImage[] $images
 * @property-read int|null $images_count
 * @property-read \App\SentImprest $imprest
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentTurnoverDetail[] $turnoverDetails
 * @property-read int|null $turnover_details_count
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentPayment newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\SentPayment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentPayment query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\SentPayment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\SentPayment withoutTrashed()
 */
	class SentPayment extends \Eloquent {}
}

namespace App{
/**
 * App\SentReceive
 *
 * @property mixed $date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentImage[] $images
 * @property-read int|null $images_count
 * @property-read \App\SentImprest $imprest
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentTurnoverDetail[] $turnoverDetails
 * @property-read int|null $turnover_details_count
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentReceive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentReceive newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\SentReceive onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentReceive query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\SentReceive withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\SentReceive withoutTrashed()
 */
	class SentReceive extends \Eloquent {}
}

namespace App{
/**
 * App\SentTurnoverDetail
 *
 * @property-read \App\AccountTitle $accountTitle
 * @property mixed $date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentPayment[] $payments
 * @property-read int|null $payments_count
 * @property-read \App\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentReceive[] $receives
 * @property-read int|null $receives_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentTurnoverDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentTurnoverDetail newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\SentTurnoverDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SentTurnoverDetail query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\SentTurnoverDetail withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\SentTurnoverDetail withoutTrashed()
 */
	class SentTurnoverDetail extends \Eloquent {}
}

namespace App{
/**
 * App\State
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State whereName($value)
 */
	class State extends \Eloquent {}
}

namespace App{
/**
 * App\StepByStep
 *
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property int $step
 * @property int $video_step
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep whereVideoStep($value)
 */
	class StepByStep extends \Eloquent {}
}

namespace App{
/**
 * App\SyncLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SyncLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SyncLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SyncLog query()
 */
	class SyncLog extends \Eloquent {}
}

namespace App{
/**
 * App\Transaction
 *
 * @property int $id
 * @property string|null $state
 * @property int|null $state_code
 * @property string|null $res_num
 * @property string|null $mid
 * @property string|null $ref_num
 * @property string|null $cid
 * @property string|null $trace_no
 * @property string|null $rrn
 * @property string|null $secure_pan
 * @property int $verify_status
 * @property int $bank_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereMid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereRefNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereResNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereRrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereSecurePan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereStateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereTraceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transaction whereVerifyStatus($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App{
/**
 * App\TurnoverDetail
 *
 * @property int $id
 * @property string $amount
 * @property string $description
 * @property int $account_title_id
 * @property int|null $payment_id
 * @property int|null $receive_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read AccountTitle $accountTitle
 * @property-read Project $project
 * @method static Builder|TurnoverDetails whereAccountTitleId($value)
 * @method static Builder|TurnoverDetails whereAmount($value)
 * @method static Builder|TurnoverDetails whereCreatedAt($value)
 * @method static Builder|TurnoverDetails whereDeletedAt($value)
 * @method static Builder|TurnoverDetails whereDescription($value)
 * @method static Builder|TurnoverDetails whereId($value)
 * @method static Builder|TurnoverDetails wherePaymentId($value)
 * @method static Builder|TurnoverDetails whereReceiveId($value)
 * @method static Builder|TurnoverDetails whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|TurnoverDetails onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|TurnoverDetails withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TurnoverDetails withoutTrashed()
 * @property mixed $date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receive[] $receives
 * @property-read int|null $receives_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TurnoverDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TurnoverDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TurnoverDetail query()
 */
	class TurnoverDetail extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $family
 * @property string|null $email
 * @property string|null $password
 * @property string|null $phone_number
 * @property int $state
 * @property string|null $company_name
 * @property string|null $verification_code
 * @property \Illuminate\Support\Carbon|null $vcode_generation_time
 * @property \Illuminate\Support\Carbon|null $last_sms_time
 * @property int $sms_counter
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $wallet
 * @property int $reserve_wallet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Feedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read mixed $created_at_date
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Poll[] $polls
 * @property-read int|null $polls_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProjectUser[] $projectUser
 * @property-read int|null $project_user_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Receive[] $receives
 * @property-read int|null $receives_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentImage[] $sentImages
 * @property-read int|null $sent_images_count
 * @property-read \App\StepByStep $stepByStep
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastSmsTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereReserveWallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSmsCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVcodeGenerationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWallet($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\UserActivationLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog query()
 */
	class UserActivationLog extends \Eloquent {}
}

namespace App{
/**
 * App\UserActivationState
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState query()
 */
	class UserActivationState extends \Eloquent {}
}

namespace App{
/**
 * App\UserReport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserReport query()
 */
	class UserReport extends \Eloquent {}
}

