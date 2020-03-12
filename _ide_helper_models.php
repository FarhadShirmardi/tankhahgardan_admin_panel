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
 * App\FeedbackTitle
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackTitle query()
 */
	class FeedbackTitle extends \Eloquent {}
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
 * App\State
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\State query()
 */
	class State extends \Eloquent {}
}

namespace App{
/**
 * App\Payment
 *
 * @property int $id
 * @property string $amount
 * @property string $description
 * @property string $date
 * @property int|null $imprest_id
 * @property int|null $payment_subject
 * @property int $creator_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read TurnoverDetail $turnoverDetails
 * @method static Builder|Payments whereAmount($value)
 * @method static Builder|Payments whereContactId($value)
 * @method static Builder|Payments whereCreatedAt($value)
 * @method static Builder|Payments whereCreatorUserId($value)
 * @method static Builder|Payments whereDate($value)
 * @method static Builder|Payments whereDeletedAt($value)
 * @method static Builder|Payments whereDescription($value)
 * @method static Builder|Payments whereId($value)
 * @method static Builder|Payments whereImprestId($value)
 * @method static Builder|Payments whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $project_id
 * @property-read Collection|Image[] $images
 * @method static Builder|Payments whereProjectId($value)
 * @property-read int|null $images_count
 * @property-read \App\Imprest $imprest
 * @property-read \App\SentPayment $sentPayment
 * @property-read int|null $turnover_details_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payment query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Payment withoutTrashed()
 */
	class Payment extends \Eloquent {}
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
 * App\Device
 *
 * @mixin Eloquent
 * @property int $id
 * @property string|null $client_id
 * @property string|null $token
 * @property string|null $token_generation_time
 * @property int $user_id
 * @property string|null $device_model
 * @property string|null $device_info
 * @property string|null $last_update_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|Devices whereClientId($value)
 * @method static Builder|Devices whereCreatedAt($value)
 * @method static Builder|Devices whereDeletedAt($value)
 * @method static Builder|Devices whereDeviceInfo($value)
 * @method static Builder|Devices whereDeviceModel($value)
 * @method static Builder|Devices whereId($value)
 * @method static Builder|Devices whereLastUpdateTime($value)
 * @method static Builder|Devices whereToken($value)
 * @method static Builder|Devices whereTokenGenerationTime($value)
 * @method static Builder|Devices whereUpdatedAt($value)
 * @method static Builder|Devices whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device query()
 */
	class Device extends \Eloquent {}
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
 * App\Feedback
 *
 * @property-read \App\Device $device
 * @property-read \App\FeedbackResponse $feedbackResponse
 * @property-read \App\FeedbackTitle $feedbackTitles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feedback query()
 */
	class Feedback extends \Eloquent {}
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
 * App\FeedbackResponse
 *
 * @property-read \App\PanelUser $panelUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FeedbackResponse query()
 */
	class FeedbackResponse extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PanelUser query()
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
 * App\Receive
 *
 * @property int $id
 * @property string $amount
 * @property string $description
 * @property string $date
 * @property int|null $imprest_id
 * @property int $creator_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read TurnoverDetail $turnoverDetails
 * @method static Builder|Payment whereAmount($value)
 * @method static Builder|Payment whereContactId($value)
 * @method static Builder|Payment whereCreatedAt($value)
 * @method static Builder|Payment whereCreatorUserId($value)
 * @method static Builder|Payment whereDate($value)
 * @method static Builder|Payment whereDeletedAt($value)
 * @method static Builder|Payment whereDescription($value)
 * @method static Builder|Payment whereId($value)
 * @method static Builder|Payment whereImprestId($value)
 * @method static Builder|Payment whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $receive_subject
 * @method static Builder|Receives whereReceiveSubject($value)
 * @property int $project_id
 * @property-read Collection|Image[] $images
 * @method static Builder|Receives whereProjectId($value)
 * @property-read int|null $images_count
 * @property-read \App\Imprest $imprest
 * @property-read \App\SentReceive $sentReceive
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentTurnoverDetail[] $sentTurnoverDetails
 * @property-read int|null $sent_turnover_details_count
 * @property-read int|null $turnover_details_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Receive onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Receive query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Receive withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Receive withoutTrashed()
 */
	class Receive extends \Eloquent {}
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
 * App\ProjectUser
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AccountingCode[] $accountingCodes
 * @property-read int|null $accounting_codes_count
 * @property mixed $added_date
 * @property-read \App\Project $project
 * @property-read \App\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProjectUser query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\ProjectUser withoutTrashed()
 */
	class ProjectUser extends \Eloquent {}
}

namespace App{
/**
 * App\Note
 *
 * @property int $id
 * @property int $project_id
 * @property string $text
 * @property string $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static Builders|Notes onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notes whereUpdatedAt($value)
 * @method static Builders|Notes withTrashed()
 * @method static Builders|Notes withoutTrashed()
 * @mixin Eloquent
 * @property int $creator_user_id
 * @property bool $is_done
 * @property int|null $related_user_id
 * @property int|null $reminder_id
 * @property string|null $description
 * @property-read \App\Reminder $reminder
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereIsDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereRelatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Note whereReminderId($value)
 */
	class Note extends \Eloquent {}
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
 * App\City
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City query()
 */
	class City extends \Eloquent {}
}

namespace App{
/**
 * App\UserActivationState
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationState whereUserId($value)
 */
	class UserActivationState extends \Eloquent {}
}

namespace App{
/**
 * App\UserActivationLog
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $notify_type
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog whereNotifyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserActivationLog whereUserId($value)
 */
	class UserActivationLog extends \Eloquent {}
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
 * App\File
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File query()
 */
	class File extends \Eloquent {}
}

namespace App{
/**
 * App\StepByStep
 *
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StepByStep query()
 */
	class StepByStep extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image query()
 */
	class Image extends \Eloquent {}
}

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
 * App\Imprest
 *
 * @property int $id
 * @property int $imprest_number
 * @property int $state
 * @property string $start_date
 * @property string $end_date
 * @property string|null $description
 * @property int $project_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection|Payment[] $payments
 * @property-read Collection|Receive[] $receives
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Imprests onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Imprests whereCreatedAt($value)
 * @method static Builder|Imprests whereDeletedAt($value)
 * @method static Builder|Imprests whereDescription($value)
 * @method static Builder|Imprests whereEndDate($value)
 * @method static Builder|Imprests whereId($value)
 * @method static Builder|Imprests whereImprestNumber($value)
 * @method static Builder|Imprests whereProjectId($value)
 * @method static Builder|Imprests whereStartDate($value)
 * @method static Builder|Imprests whereState($value)
 * @method static Builder|Imprests whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Imprests withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Imprests withoutTrashed()
 * @mixin Eloquent
 * @property-read int|null $payments_count
 * @property-read \App\Project $project
 * @property-read int|null $receives_count
 * @property-read \App\SentImprest $sentImprest
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Imprest query()
 */
	class Imprest extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSmsCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVcodeGenerationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereVerificationCode($value)
 */
	class User extends \Eloquent {}
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

