<?php

// @formatter:off

/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App {
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
     * @method static \Illuminate\Database\Eloquent\Builder|AccountTitle newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AccountTitle newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AccountTitle query()
     */
    class AccountTitle extends \Eloquent
    {
    }
}

namespace App {
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
     * @method static \Illuminate\Database\Eloquent\Builder|AccountingCode newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AccountingCode newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AccountingCode query()
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
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingSoftware newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingSoftware newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingSoftware query()
 */
    class AccountingSoftware extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Advertisement
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Advertisement newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Advertisement newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Advertisement query()
     */
    class Advertisement extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Announcement
     *
     * @property int $id
     * @property string $title
     * @property string|null $text
     * @property string|null $summary
     * @property string|null $icon_path
     * @property string|null $image_path
     * @property string|null $gif_path
     * @property int $link_type
     * @property string|null $external_link
     * @property string|null $button_name
     * @property string|null $button_link
     * @property string|null $expire_at
     * @property string $send_at
     * @property int $user_type
     * @property int $panel_user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\AnnouncementUser[] $announcementUser
     * @property-read int|null $announcement_user_count
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereButtonLink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereButtonName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereExpireAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereExternalLink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereGifPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereIconPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereImagePath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereLinkType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement wherePanelUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereSendAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereSummary($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereText($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUserType($value)
     */
    class Announcement extends \Eloquent
    {
    }
}

namespace App{
    /**
     * App\AnnouncementUser
     *
     * @property int $id
     * @property int $user_id
     * @property int $announcement_id
     * @property bool $read
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser query()
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser whereAnnouncementId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser whereRead($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementUser whereUserId($value)
     */
    class AnnouncementUser extends \Eloquent
    {
    }
}

namespace App{
    /**
     * App\ApplicationVersion
     *
     * @property mixed $release_date
     * @method static \Illuminate\Database\Eloquent\Builder|ApplicationVersion newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ApplicationVersion newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ApplicationVersion query()
     */
    class ApplicationVersion extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\AutomationData
     *
     * @property int $id
     * @property string $name
     * @property string $phone_number
     * @property string $registered_at
     * @property int $transaction_count
     * @property string|null $max_time
     * @property int $automation_state
     * @property int $premium_state
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData query()
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData whereAutomationState($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData whereMaxTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData wherePhoneNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData wherePremiumState($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData whereRegisteredAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationData whereTransactionCount($value)
     */
    class AutomationData extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\AutomationMetric
     *
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationMetric newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationMetric newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationMetric query()
     */
    class AutomationMetric extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\AutomationSms
     *
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationSms newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationSms newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|AutomationSms query()
     */
    class AutomationSms extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Bank
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Bank newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Bank newQuery()
     * @method static \Illuminate\Database\Query\Builder|Bank onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Bank query()
     * @method static \Illuminate\Database\Query\Builder|Bank withTrashed()
     * @method static \Illuminate\Database\Query\Builder|Bank withoutTrashed()
     */
	class Bank extends \Eloquent {}
}

namespace App{
    /**
     * App\BankPattern
     *
     * @property-read \App\Bank|null $bank
     * @method static \Illuminate\Database\Eloquent\Builder|BankPattern newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BankPattern newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BankPattern query()
     */
    class BankPattern extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Banner
     *
     * @property int $id
     * @property string $title
     * @property string $image_path
     * @property string|null $button_name
     * @property string|null $button_link
     * @property string|null $expire_at
     * @property string $start_at
     * @property int $type
     * @property int $panel_user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\BannerUser[] $user
     * @property-read int|null $user_count
     * @method static \Illuminate\Database\Eloquent\Builder|Banner newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Banner newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Banner query()
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereButtonLink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereButtonName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereExpireAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereImagePath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner wherePanelUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereStartAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Banner whereUpdatedAt($value)
     */
    class Banner extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\BannerUser
     *
     * @property int $id
     * @property int $banner_id
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser query()
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser whereBannerId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BannerUser whereUserId($value)
     */
    class BannerUser extends \Eloquent
    {
    }
}

namespace App {
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
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign query()
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign wherePanelUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereSymbol($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereUpdatedAt($value)
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
     * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|City query()
     * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|City whereStateId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereFeedbackTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePanelUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereResponseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App{
    /**
     * App\DefaultAccountTitle
     *
     * @method static \Illuminate\Database\Eloquent\Builder|DefaultAccountTitle newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|DefaultAccountTitle newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|DefaultAccountTitle query()
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
 * @property string|null $app_version
 * @method static \Illuminate\Database\Eloquent\Builder|Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Device query()
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereAppVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereOsVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Device whereUserId($value)
 */
	class Device extends \Eloquent {}
}

namespace App{
    /**
     * App\ExcelExport
     *
     * @method static \Illuminate\Database\Eloquent\Builder|ExcelExport newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ExcelExport newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ExcelExport query()
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
 * @property-read \App\Device|null $device
 * @property-read \App\FeedbackResponse|null $feedbackResponse
 * @property-read \App\FeedbackTitle|null $feedbackTitles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereApplicationVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereFeedbackResponseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereFeedbackTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUserId($value)
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\PanelUser|null $panelUser
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse wherePanelUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse whereResponseUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackResponse whereUpdatedAt($value)
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
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle query()
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle whereLink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle whereTag($value)
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|FeedbackTitle whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereModelUpdateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
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
 * @property bool $sync_flag
 * @property int|null $project_id
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereSyncFlag($value)
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
 * @property-read \App\SentImprest|null $sentImprest
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest newQuery()
 * @method static \Illuminate\Database\Query\Builder|Imprest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereImprestNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Imprest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Imprest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Imprest withoutTrashed()
 */
	class Imprest extends \Eloquent {}
}

namespace App{
    /**
     * App\Memo
     *
     * @property mixed $date
     * @method static \Illuminate\Database\Eloquent\Builder|Memo newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Memo newQuery()
     * @method static \Illuminate\Database\Query\Builder|Memo onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Memo query()
     * @method static \Illuminate\Database\Query\Builder|Memo withTrashed()
     * @method static \Illuminate\Database\Query\Builder|Memo withoutTrashed()
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
 * @property-read \App\Reminder|null $reminder
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Query\Builder|Note onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereIsDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Note withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Note withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PanelUser whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset query()
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
 * @property-read \App\Imprest|null $imprest
 * @property-read \App\SentPayment|null $sentPayment
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TurnoverDetail[] $turnoverDetails
 * @property-read int|null $turnover_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereImprestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Payment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Payment withoutTrashed()
 */
	class Payment extends \Eloquent {}
}

namespace App{
    /**
     * App\PendingInvitation
     *
     * @method static \Illuminate\Database\Eloquent\Builder|PendingInvitation newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PendingInvitation newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PendingInvitation query()
     */
	class PendingInvitation extends \Eloquent {}
}

namespace App{
    /**
     * App\Poll
     *
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
     * @property-read int|null $users_count
     * @method static \Illuminate\Database\Eloquent\Builder|Poll newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Poll newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Poll query()
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
 * @property int|null $type
 * @property-read \App\AccountingSoftware|null $accountingSoftware
 * @property-read \App\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read mixed $currency_text
 * @property-read mixed $premium_state
 * @property mixed $start_date
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Imprest[] $imprests
 * @property-read int|null $imprests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Memo[] $memos
 * @property-read int|null $memos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProjectInviteNotification[] $projectInviteNotification
 * @property-read int|null $project_invite_notification_count
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
 * @property-read \App\State|null $state
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Query\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAccountingSoftwareId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereArchiveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Project withoutTrashed()
 */
	class Project extends \Eloquent {}
}

namespace App{
    /**
     * App\ProjectInvite
     *
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectInvite newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectInvite newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectInvite query()
     */
	class ProjectInvite extends \Eloquent {}
}

namespace App{
    /**
     * App\ProjectInviteNotification
     *
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectInviteNotification newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectInviteNotification newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectInviteNotification query()
     */
	class ProjectInviteNotification extends \Eloquent {}
}

namespace App{
    /**
     * App\ProjectReport
     *
     * @property int $id
     * @property string $name
     * @property int|null $state_id
     * @property int|null $city_id
     * @property \Illuminate\Support\Carbon $created_at
     * @property string|null $max_time
     * @property int $user_count
     * @property int $active_user_count
     * @property int $not_active_user_count
     * @property int $payment_count
     * @property int $receive_count
     * @property int $note_count
     * @property int $imprest_count
     * @property int $project_type
     * @property int|null $type
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport query()
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereActiveUserCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereCityId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereImprestCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereMaxTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereNotActiveUserCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereNoteCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport wherePaymentCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereProjectType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereReceiveCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereStateId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProjectReport whereUserCount($value)
     */
    class ProjectReport extends \Eloquent
    {
    }
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
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|ProjectUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereAddedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereExpiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|ProjectUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ProjectUser withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereMaxCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereMaxDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode wherePanelUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereReserveCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PromoCode whereUserId($value)
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
 * @property-read \App\Imprest|null $imprest
 * @property-read \App\SentReceive|null $sentReceive
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentTurnoverDetail[] $sentTurnoverDetails
 * @property-read int|null $sent_turnover_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TurnoverDetail[] $turnoverDetails
 * @property-read int|null $turnover_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|Receive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receive newQuery()
 * @method static \Illuminate\Database\Query\Builder|Receive onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Receive query()
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereCreatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereImprestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereReceiveSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Receive withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Receive withoutTrashed()
 */
	class Receive extends \Eloquent {}
}

namespace App{
    /**
     * App\Reminder
     *
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReminderInterval[] $intervals
     * @property-read int|null $intervals_count
     * @property-read \App\Note|null $note
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReminderState[] $reminderState
     * @property-read int|null $reminder_state_count
     * @method static \Illuminate\Database\Eloquent\Builder|Reminder newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Reminder newQuery()
     * @method static \Illuminate\Database\Query\Builder|Reminder onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|Reminder query()
     * @method static \Illuminate\Database\Query\Builder|Reminder withTrashed()
     * @method static \Illuminate\Database\Query\Builder|Reminder withoutTrashed()
     */
	class Reminder extends \Eloquent {}
}

namespace App{
    /**
     * App\ReminderInterval
     *
     * @property mixed $end_date
     * @property mixed $start_date
     * @method static \Illuminate\Database\Eloquent\Builder|ReminderInterval newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ReminderInterval newQuery()
     * @method static \Illuminate\Database\Query\Builder|ReminderInterval onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|ReminderInterval query()
     * @method static \Illuminate\Database\Query\Builder|ReminderInterval withTrashed()
     * @method static \Illuminate\Database\Query\Builder|ReminderInterval withoutTrashed()
     */
	class ReminderInterval extends \Eloquent {}
}

namespace App{
    /**
     * App\ReminderState
     *
     * @property mixed $date
     * @property-read mixed $done_date
     * @method static \Illuminate\Database\Eloquent\Builder|ReminderState newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ReminderState newQuery()
     * @method static \Illuminate\Database\Query\Builder|ReminderState onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|ReminderState query()
     * @method static \Illuminate\Database\Query\Builder|ReminderState withTrashed()
     * @method static \Illuminate\Database\Query\Builder|ReminderState withoutTrashed()
     */
	class ReminderState extends \Eloquent {}
}

namespace App{
    /**
     * App\SentImage
     *
     * @property int $id
     * @property int $model_id
     * @property string $model_type
     * @property string $path
     * @property int $size
     * @property int|null $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @property int|null $project_id
     * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $hasImage
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage newQuery()
     * @method static \Illuminate\Database\Query\Builder|SentImage onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage query()
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereModelId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereModelType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage wherePath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereProjectId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereSize($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SentImage whereUserId($value)
     * @method static \Illuminate\Database\Query\Builder|SentImage withTrashed()
     * @method static \Illuminate\Database\Query\Builder|SentImage withoutTrashed()
     */
	class SentImage extends \Eloquent {}
}

namespace App{
    /**
     * App\SentImprest
     *
     * @property mixed $end_date
     * @property mixed $start_date
     * @property-read \App\Imprest|null $imprest
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentPayment[] $payments
     * @property-read int|null $payments_count
     * @property-read \App\Project $project
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentReceive[] $receives
     * @property-read int|null $receives_count
     * @property-read \App\User $user
     * @method static \Illuminate\Database\Eloquent\Builder|SentImprest newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SentImprest newQuery()
     * @method static \Illuminate\Database\Query\Builder|SentImprest onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SentImprest query()
     * @method static \Illuminate\Database\Query\Builder|SentImprest withTrashed()
     * @method static \Illuminate\Database\Query\Builder|SentImprest withoutTrashed()
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
     * @property-read \App\SentImprest|null $imprest
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentTurnoverDetail[] $turnoverDetails
     * @property-read int|null $turnover_details_count
     * @property-read \App\User $user
     * @method static \Illuminate\Database\Eloquent\Builder|SentPayment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SentPayment newQuery()
     * @method static \Illuminate\Database\Query\Builder|SentPayment onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SentPayment query()
     * @method static \Illuminate\Database\Query\Builder|SentPayment withTrashed()
     * @method static \Illuminate\Database\Query\Builder|SentPayment withoutTrashed()
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
     * @property-read \App\SentImprest|null $imprest
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentTurnoverDetail[] $turnoverDetails
     * @property-read int|null $turnover_details_count
     * @property-read \App\User $user
     * @method static \Illuminate\Database\Eloquent\Builder|SentReceive newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SentReceive newQuery()
     * @method static \Illuminate\Database\Query\Builder|SentReceive onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SentReceive query()
     * @method static \Illuminate\Database\Query\Builder|SentReceive withTrashed()
     * @method static \Illuminate\Database\Query\Builder|SentReceive withoutTrashed()
     */
	class SentReceive extends \Eloquent {}
}

namespace App{
    /**
     * App\SentTurnoverDetail
     *
     * @property-read \App\AccountTitle|null $accountTitle
     * @property mixed $date
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentPayment[] $payments
     * @property-read int|null $payments_count
     * @property-read \App\Project $project
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\SentReceive[] $receives
     * @property-read int|null $receives_count
     * @method static \Illuminate\Database\Eloquent\Builder|SentTurnoverDetail newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SentTurnoverDetail newQuery()
     * @method static \Illuminate\Database\Query\Builder|SentTurnoverDetail onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|SentTurnoverDetail query()
     * @method static \Illuminate\Database\Query\Builder|SentTurnoverDetail withTrashed()
     * @method static \Illuminate\Database\Query\Builder|SentTurnoverDetail withoutTrashed()
     */
	class SentTurnoverDetail extends \Eloquent {}
}

namespace App{
    /**
     * App\State
     *
     * @property int $id
     * @property string $name
     * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|State query()
     * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|State whereName($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep query()
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StepByStep whereVideoStep($value)
 */
	class StepByStep extends \Eloquent {}
}

namespace App{
    /**
     * App\SyncLog
     *
     * @method static \Illuminate\Database\Eloquent\Builder|SyncLog newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SyncLog newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SyncLog query()
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
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRefNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereResNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSecurePan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStateCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTraceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereVerifyStatus($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|TurnoverDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TurnoverDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TurnoverDetail query()
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
 * @property string|null $verification_time
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BannerUser[] $banner
 * @property-read int|null $banner_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Device[] $devices
 * @property-read int|null $devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Feedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read mixed $created_at_date
 * @property-read mixed $full_name
 * @property-read mixed $premium_state
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $ownedProjects
 * @property-read int|null $owned_projects_count
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
 * @property-read \App\StepByStep|null $stepByStep
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserStatus[] $userStatus
 * @property-read int|null $user_status_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserStatusLog[] $userStatusLog
 * @property-read int|null $user_status_log_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserStatusLog[] $userStatusLogNull
 * @property-read int|null $user_status_log_null_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserStatus[] $userStatuses
 * @property-read int|null $user_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastSmsTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReserveWallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSmsCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVcodeGenerationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerificationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWallet($value)
 */
	class User extends \Eloquent {}
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
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog whereNotifyType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationLog whereUserId($value)
     */
	class UserActivationLog extends \Eloquent {}
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
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState whereState($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserActivationState whereUserId($value)
     */
	class UserActivationState extends \Eloquent {}
}

namespace App{
    /**
     * App\UserReport
     *
     * @property int $id
     * @property string $name
     * @property string $phone_number
     * @property string $registered_at
     * @property int $payment_count
     * @property int $receive_count
     * @property int $note_count
     * @property int $imprest_count
     * @property int $file_count
     * @property int $image_count
     * @property float $image_size
     * @property int $device_count
     * @property int $feedback_count
     * @property int|null $step_by_step
     * @property int $project_count
     * @property int $own_project_count
     * @property int $not_own_project_count
     * @property string|null $max_time
     * @property int $user_type
     * @property int $user_state
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereDeviceCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereFeedbackCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereFileCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereImageCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereImageSize($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereImprestCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereMaxTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereNotOwnProjectCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereNoteCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereOwnProjectCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport wherePaymentCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport wherePhoneNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereProjectCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereReceiveCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereRegisteredAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereStepByStep($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereUserState($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserReport whereUserType($value)
     */
    class UserReport extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\UserStatus
     *
     * @property int $id
     * @property int $user_id
     * @property string $start_date
     * @property string $end_date
     * @property int $volume_size
     * @property int $user_count
     * @property int $price_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property \Illuminate\Support\Carbon|null $deleted_at
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus newQuery()
     * @method static \Illuminate\Database\Query\Builder|UserStatus onlyTrashed()
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus wherePriceId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereUserCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatus whereVolumeSize($value)
     * @method static \Illuminate\Database\Query\Builder|UserStatus withTrashed()
     * @method static \Illuminate\Database\Query\Builder|UserStatus withoutTrashed()
     */
    class UserStatus extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\UserStatusLog
     *
     * @property int $id
     * @property int $user_id
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
     * @property-read \App\PromoCode|null $promoCode
     * @property-read \App\Transaction|null $transaction
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereAddedValueAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereCampaignId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereDiscountAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereEndDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog wherePriceId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog wherePromoCodeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereStartDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereTotalAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereTraceNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereTransactionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereUserCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereVolumeSize($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserStatusLog whereWalletAmount($value)
     */
    class UserStatusLog extends \Eloquent
    {
    }
}

