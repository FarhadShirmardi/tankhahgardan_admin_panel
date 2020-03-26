<?php

return [
    'success' => 'با موفقیت انجام شد.',
    'unsuccessful' => 'ناموفق',
    'otp_sent' => 'پیامک فعال سازی ارسال شد.',
    'retry_after_5_minute' => 'لطفا 5 دقیقه دیگر تلاش کنید.',
    'otp_expired' => 'رمز یکبار مصرف باطل شده است.',
    'trying_too_much' => 'تعداد تلاش بیش از حد مجاز است. دوباره تلاش کنید.',
    'integrity_constraint_failed' => 'خطا در کلید خارجی',
    'database_error' => 'خطا در دیتابیس',
    'no_results' => 'موجود نیست!',
    'can_not_insert_value_for_boolean_attributes' => 'برای اتریبیوت های بولینی نمی توان مقداری را وارد کرد!',
    'can_not_update_value_for_boolean_attributes' => 'برای اتریبیوت های بولینی نمی توان مقداری را وارد کرد!',
    'can_not_insert_value_for_text_attributes' => 'برای اتریبیوت های متنی مقدار نمی توان وارد کرد!',
    'can_not_update_value_for_text_attributes' => 'برای اتریبیوت های متنی مقدار نمی توان وارد کرد!',
    'reach_today_sms_limit' => 'تعداد مجاز ثبت‌نام یا بازیابی رمزعبور به پایان رسیده است
     برای تلاش مجدد تا پایان روز منتظر بمانید.',
    'not_found' => 'پیدا نشد!',
    'data_already_stored' => 'اطلاعات قبلا ذخیره شده است.',

    /** Oauth */
    'The_user_credentials_were_incorrect.' => 'اطلاعات ورود کاربر اشتباه است.',
    'Client_authentication_failed' => 'کلاینت شناسایی نشد.',

    /** not provided */
    'phone_not_provided' => 'شماره تلفن داده نشده است.',
    'phone_or_password_not_provided' => 'شماره تلفن یا کلمه عبور داده نشده است.',
    'phone_already_registered' => 'این شماره قبلا ثبت شده است.',
    'data_not_provided' => 'اطلاعات داده نشده است.',
    'phone_not_found' => 'این شماره پیدا نشد',
    'phone_number_not_activated' => 'شماره تلفن فعال نشده است.',

    /** user login, register or verification */
    'user_not_found' => 'کاربر با مشخصات داده شده یافت نشد.',
    'user_found' => 'کاربر با مشخصات داده شده یافت شد.',
    'user_not_activated_please_register_for_activation' =>
        'کاربر فعال نشده است. برای فعال سازی به بخش ثبت نام مراجعه کنید.',
    'verification_code_time_out' => 'کد فعال سازی منقضی شده است.',
    'verification_code_incorrect' => 'کد فعال سازی اشتباه است.',
    'login_success' => 'ورود با موفقیت انجام شد.',
    'register_success' => 'با موفقیت انجام شد. جهت تکمیل ثبت‌نام کد ارسال‌شده را وارد کنید.',
    'this_token_is_invalid' => 'این توکن منقضی شده است',
    'user_info_updated_successfully' => 'مشخصات کاربر با موفقیت ویرایش شد.',


    /** project */
    'project_not_found' => 'اطلاعات شرکت یافت نشد.',
    'this_user_is_not_a_member_of_this_project' => 'کاربر عضوی از این شرکت نیست.',
    'project_not_updated' => 'اطلاعات شرکت به روزرسانی نشد.',
    'project_successfully_updated' => 'اطلاعات شرکت با موفقیت به روزرسانی شد.',
    'project_name_duplicated' => 'نام شرکت تکراری می‌باشد.',
    'project_successfully_deleted' => 'اطلاعات شرکت با موفقیت حذف شد.',
    'project_successfully_created' => 'شرکت با موفقیت ایجاد شد.',
    'project_not_yours' => 'شرکت متعلق به شما نیست.',
    'you_are_currently_inactive_in_this_project' => 'شما در این شرکت غیر فعال هستید.',
    'project_can_not_be_deleted' => 'شما قادر به حذف اطلاعات شرکت نیستید.',
    'project_can_not_be_updated' => 'شما قادر به ویرایش اطلاعات شرکت نیستید.',
    'state_not_found' => 'استان یافت نشد.',
    'city_not_found' => 'شهر یافت نشد.',

    /** payment */
    'payment_not_found' => 'پرداخت یافت نشد.',
    'payment_not_updated' => 'پرداخت به روزرسانی نشد.',
    'payment_successfully_updated' => 'پرداخت با موفقیت به روزرسانی شد.',
    'payment_name_duplicated' => 'نام پرداخت تکراری می‌باشد.',
    'payment_successfully_deleted' => 'پرداخت با موفقیت حذف شد.',
    'payment_can_not_deleted' => 'قادر به حذف پرداخت نمی‌باشید.',
    'payment_date_can_not_be_in_future' => 'تاریخ پرداخت نمی تواند در آینده باشد.',
    'payment_successfully_created' => 'پرداخت با موفقیت ایجاد شد.',
    'payment_creator_can_not_change' => 'قادر به تغییر ثبت کننده پرداخت نمی‌باشید.',
    'payment_amount_changed' => 'شما قادر به تغییر مبلغ پرداخت نمی‌باشید.',
    'payment_used_in_imprest_can_not_edit' => 'پرداخت در یک تنخواه استفاده شده است. اجازه ویرایش آن را ندارید.',
    'payment_used_in_imprest_can_not_delete' => 'پرداخت در یک تنخواه استفاده شده است. اجازه پاک کردن آن را ندارید.',
    'user_is_active_do_not_store_payment' =>
        'کاربر فعال است. شما قادر به ثبت، ویرایش و یا حذف پرداخت اصلاحی نمی‌باشید.',

    /** receive */
    'receive_not_found' => 'دریافت یافت نشد.',
    'receive_not_updated' => 'دریافت به روزرسانی نشد.',
    'receive_subject_not_allowed' => 'موضوع دریافت صحیح نمی‌باشد.',
    'receive_successfully_updated' => 'دریافت با موفقیت به روزرسانی شد.',
    'receive_name_duplicated' => 'نام دریافت تکراری می‌باشد.',
    'receive_successfully_deleted' => 'دریافت با موفقیت حذف شد.',
    'receive_creator_can_not_change' => 'قادر به تغییر ثبت کننده دریافت نمی‌باشید.',
    'receive_can_not_deleted' => 'قادر به حذف دریافت نمی‌باشید.',
    'receive_date_can_not_be_in_future' => 'تاریخ دریافت نمی تواند در آینده باشد.',
    'receive_successfully_created' => 'دریافت با موفقیت ایجاد شد.',
    'receive_amount_changed' => 'شما قادر به تغییر مبلغ دریافت نمی‌باشید.',
    'receive_used_in_imprest_can_not_edit' => 'دریافت در یک تنخواه استفاده شده است. اجازه ویرایش آن را ندارید.',
    'receive_used_in_imprest_can_not_delete' => 'دریافت در یک تنخواه استفاده شده است. اجازه پاک کردن آن را ندارید.',
    'user_is_active_do_not_store_receive' =>
        'کاربر فعال است. شما قادر به ثبت، ویرایش و یا حذف دریافت اصلاحی نمی‌باشید.',

    /** account title */
    'account_title_not_found' => 'سر فصل حساب مورد نظر پیدا نشد.',
    'account_title_used_not_delete' => 'سر فصل حساب در یک پرداخت یا دریافت استفاده شده، نمی‌توانید آن را پاک کنید.',
    'account_title_not_yours' => 'سر فصل حساب به شما تعلق ندارد.',
    'account_title_not_updated' => 'سر فصل حساب به روزرسانی نشد.',
    'account_title_duplicated' => 'سر فصل حساب تکراری می‌باشد.',
    'accounting_codes_not_sequential' => 'کد حسابداری ها باید به ترتیب وارد شوند.',
    'account_title_successfully_updated' => 'سر فصل حساب با موفقیت به روزرسانی شد.',
    'account_title_successfully_deleted' => 'سر فصل حساب با موفقیت حذف شد.',
    'account_title_successfully_created' => 'سر فصل حساب با موفقیت ایجاد شد.',
    'account_title_problem' => 'سر فصل حساب :name به شرکت اضافه نشد.',

    /** turnover detail */
    'turnover_sum_not_equal' => 'جمع ریز اقلام با جمع کل باید برابر باشد.',
    'turnover_detail_not_allowed' => 'ریز اقلام برای این موضوع امکان پذیر نمی‌باشد.',


    /** imprest */
    'imprest_number_not_ok' => 'شماره تنخواه صحیح نمی‌باشد.',
    'imprest_all_should_be_finalize' => 'تمام تنخواه‌های قبلی باید ثبت نهایی شده باشند.',
    'imprest_payment_not_found' => 'پرداخت‌های وارد شده صحیح نمی‌باشند.',
    'imprest_receive_not_found' => 'دریافت‌های وارد شده صحیح نمی‌باشند.',
    'imprest_payment_was_used' => 'پرداخت‌های وارد شده قبلا استفاده شده‌اند.',
    'imprest_receive_was_used' => 'دریافت‌های وارد شده قبلا استفاده شده‌اند.',
    'imprest_payment_or_receive_not_yours' => 'دریافت یا پرداخت وارد شده متعلق به شما نیست.',
    'imprest_not_updated' => 'تنخواه به روزرسانی نشد.',
    'imprest_successfully_updated' => 'تنخواه با موفقیت به روزرسانی شد.',
    'imprest_successfully_deleted' => 'تنخواه با موفقیت حذف شد.',
    'imprest_successfully_created' => 'تنخواه با موفقیت ایجاد شد.',
    'imprest_can_not_be_deleted' => 'تنها حذف آخرین تنخواه امکان‌پذیر می‌باشد.',
    'imprest_sent_can_not_be_deleted' => 'شما مجاز به حذف تنخواه ارسال شده نمی‌باشید.',
    'imprest_sent_can_not_be_update' => 'شما مجاز به ویرایش تنخواه ارسال شده نمی‌باشید.',
    'only_last_imprest_can_be_sent' => 'تنها آخرین تنخواه قابل بازگرداندن می‌باشد.',
    'imprest_amendment_can_not_be_updated' => 'شما مجاز به ویرایش تنخواه اصلاحی نمی‌باشید.',
    'imprest_amendment_can_not_be_deleted' => 'شما مجاز به حذف تنخواه اصلاحی نمی‌باشید.',
    'only_first_imprest_can_be_changed' => 'مجاز به ارسال این تنخواه نمی‌باشید. تمام تنخواه‌های قبلی باید ارسال شده باشند.',
    'imprest_not_found' => 'تنخواه یافت نشد.',
    'imprest_not_yours' => 'تنخواه متعلق به شما نیست.',
    'imprest_change_state_not_allowed' => 'شما قادر به تغییر وضعیت تنخواه نمی‌باشید.',
    'imprest_sent_successfully' => 'تنخواه با موفقیت ارسال شد.',
    'imprest_returned_successfully' => 'تنخواه با موفقیت بازگردانده شد.',
    'imprest_sent_user_is_not_active' => 'کاربر در شرکت فعال نیست. نمی‌توانید وضعیت این تنخواه را تغییر دهید.',
    'send_imprest' => 'ارسال تنخواه',
    'user_send_imprest_to_panel' => 'کاربر :name، تنخواه شماره :imprestNumber را در شرکت :project ارسال کرده است.',
    'owner_change_imprest_status_from_panel'
    => 'مدیر، وضعیت تنخواه شماره :imprestNumber را در شرکت :project تغییر داد.',

    /** image */
    'image_not_yours' => 'این عکس متعلق به شما نیست',
    'image_not_found' => 'عکس پیدا نشد',
    'image_not_valid' => 'این عکس معتبر نمی‌باشد.',

    /** project setting */
    'project_setting_already_registered' => 'تنظیمات شرکت قبلا ثبت شده است.',
    'project_setting_not_found' => 'تنظیمات شرکت وجود ندارد.',
    'project_setting_updated' => 'تنظیمات شرکت به‌روزرسانی شد.',
    'project_setting_user_updated' => 'اطلاعات کاربر به‌روزرسانی شد.',
    'you_are_not_owner_of_this_project_setting' => 'شما جزو دعوت شدگان به شرکت هستید و قادر به تغییر تنظیمات نمی‌باشید.',
    'you_are_not_owner_of_this_project' => 'شما جزو دعوت شدگان به شرکت هستید و نمی‌توانید آن را ویرایش یا حذف کنید.',
    'you_are_not_owner_of_this_project_panel' => 'شما جزو دعوت شدگان به شرکت هستید. لطفا مجددا برای ورود اقدام کنید.',


    /** note */
    'note_not_found' => 'فعالیت مورد نظر پیدا نشد.',
    'note_not_yours' => 'فعالیت به شما تعلق ندارد.',
    'note_not_updated' => 'فعالیت به روزرسانی نشد.',
    'note_duplicated' => 'فعالیت تکراری می‌باشد.',
    'note_successfully_updated' => 'فعالیت با موفقیت به روزرسانی شد.',
    'note_successfully_deleted' => 'فعالیت با موفقیت حذف شد.',
    'note_successfully_created' => 'فعالیت با موفقیت ایجاد شد.',

    /** user invite */
    'invitation' => 'دعوت‌نامه',
    'accept_invitation' => 'تایید دعوت‌نامه',
    'reject_invitation' => 'رد دعوت‌نامه',
    'user_to_invite_not_found' => 'شماره مورد نظر در اپلیکیشن ثبت نام نکرده است.',
    'user_phone_number_incorrect' => 'شماره مورد نظر نامعتبر است.',
    'user_invitation_to_project' => 'کاربر :name، شما را به همکاری در شرکت :project دعوت کرده است.',
    'user_comeback_to_project' => 'کاربر :name، شما را دعوت کرده است که به شرکت :project بازگردید.',
    'user_accept_invitation_to_project' => 'کاربر :name، دعوت شما برای همکاری در شرکت :project را قبول کرده است. ',
    'user_reject_invitation_to_project' => 'کاربر :name، دعوت شما برای همکاری در شرکت :project را رد کرده است. ',
    'user_invitation_sent' => 'دعوت برای کاربر ارسال شد.',
    'user_invitation_sms_sent' => 'پیامک برای کاربر ارسال شد.',
    'user_invitation_was_sent_before' => 'دعوت قبلا برای کاربر ارسال شده است.',
    'invitation_was_not_found' => 'دعوت نامه پیدا نشد.',
    'invitation_accepted_successfully' => 'دعوت با موفقیت تایید شد.',
    'user_already_invited' => 'این کاربر قبلا دعوت شده است.',
    'you_can_not_invite_yourself' => 'شما نمی‌توانید خودتان را دعوت کنید.',
    'check_limit_finished' => 'سهمیه امروز چک کردن شماره شما به پایان رسیده است.',

    /** Sync */
    'sync_page_not_found' => 'صفحه مورد نظر پیدا نشد.',
    'sync_time_garbage_danger' => ':day روز از آخرین تاریخ به روزرسانی می‌گذرد.',

    /** Change Password */
    'old_password_not_correct' => 'کلمه عبور وارد شده صحیح نمی‌باشد.',
    'password_changed_successfully' => 'کلمه عبور با موفقیت تغییر یافت.',

    /** report */
    'file_is_under_construction' => 'فایل در حال ساخت می‌باشد.',
    'file_created' => 'فایل ساخته شد.',
    'file_creation_failed' => 'ساخت فایل انجام نشد.',
    'file_not_found' => 'فایل پیدا نشد.',
    'file_limit_this_day_passed' => 'محدودیت فایل‌های ساخته شده در یک روز به اتمام رسیده است.',

    /** application version */
    'no_update_available' => 'نسخه شما به روز می‌باشد.',
    'update_available_no_force' => 'نسخه شما قدیمی می‌باشد، لطفا بروزرسانی کنید.',
    'update_available_force' => 'برای ادامه باید بروزرسانی کنید.',
    'wrong_version_number' => 'نسخه شما اشتباه است.',

    /** poll */
    'poll_created_successfully' => 'نظرسنجی با موفقیت ایجاد شد.',
    'poll_read_successfully' => 'در حال نمایش نظرسنجی',
    'poll_dismissed_successfully' => 'نظرسنجی با موفقیت لغو شد.',

    /** notification */
    'notification_not_found' => 'اعلان پیدا نشد.',
    'notification_deleted_successfully' => 'اعلان با موفقیت حذف شد.',

    /** feedback */
    'feedback_not_found' => 'بازخورد یافت نشد.',
    'feedback_response_not_found' => 'پاسخ بازخورد یافت نشد.',
    'feedback_response_score_already_saved' => 'امتیاز قبلا ثبت شده است.',
    'feedback_successfully_created' => 'بازخورد با موفقیت ایجاد شد.',
    'feedback_response_already_exist' => 'این بازخورد قبلا پاسخ داده شده است.',
    'feedback_response_successfully_created' => 'پاسخ بازخورد با موفقیت ثبت شد.',
    'feedback_response_successfully_read' => 'پاسخ بازخورد با موفقیت خوانده شد.',
    'feedback_response_score_successfully_stored' => 'از امتیازدهی شما سپاسگزاریم.',

    /** import account title */
    'import_account_title_error' => 'خطاهای زیر رخ داده است : ',

    /** Accounting Software */
    'accounting_software_should_be_selected' => 'برای این گزارش باید یک نرم‌افزار حسابداری انتخاب شده باشد.',

    /** help */
    'help_page_not_found' => 'صفحه یافت نشد.',
    'help_above_footer_title' => 'هنوز پاسخ خود را نیافته‌اید؟',
    'help_footer_info_title' => 'تنخواه‌گردان',
    'help_footer_info_text' => 'در این سرویس برای شما امکان افزودن دریافت، پرداخت و صورت حساب را فراهم کرده ایم. ' .
        'همچنین میتوانید درخواست تولید لیست تنخواه گردان داده و لیستها را هر زمان که خواستید مشاهده کنید.' .
        'برای شروع کار لطفا در سامانه ثبت نام کنید، و یا اگر عضو هستید روی لینک ورود کلیک کرده ' .
        'و با نام کاربری خود وارد سامانه شوید. ما را از پیشنهادات و انتقادات سازنده خود محروم نکنید',
    'help_footer_contact_us_address' => 'تهران، محله طرشت، میدان تیموری، قبل از مترو شریف،' .
        ' نبش کوچه برومند، پلاک ۲، طبقه ۴، واحد ۸',
    'help_footer_download_title' => 'هنوز تنخواه‌گردان را دریافت نکرده‌اید.',
    'help_footer_copyright' => 'تمامی حقوق متعلق به داموس',
    'help_about_us_about_us_title' => 'درباره ما',
    'help_about_us_contact_us_title' => 'ارتباط با ما',
    'help_about_us_about_us_text' => 'سیبمنتسیابمنتسیباشسمنیتباسیبمنتسیابمنتسیباشسمنیتباسیبمنتسیابمنتسیباشسمنیتباسیبمنتسیابمنتسیباشسمنیتبا',

    /** garbage */
    'garbage_collection_done_successfully' => 'عملیات آشغال جمع کن به درستی پایان یافت.',
    'garbage_collection_done_unsuccessfully' => 'عملیات آشغال جمع کن با خطا پایان یافت.',

    /** queue */
    'queue_health_check_ended_successfully' => 'عملیات تست صف‌ :name با موفقیت پایان یافت.',
    'queue_health_check_ended_unsuccessfully' => 'عملیات تست صف‌ :name با خطا پایان یافت.',

    /** memo */
    'memo_successfully_updated' => 'یادداشت با موفقیت به روزرسانی شد.',
    'memo_successfully_deleted' => 'یادداشت با موفقیت حذف شد.',
    'memo_successfully_created' => 'یادداشت با موفقیت ایجاد شد.',
    'memo_not_found' => 'یادداشت پیدا نشد.',

    /** reminder */
    'interval_type_invalid' => 'نوع وقفه صحیح نمی‌باشد',
    'reminder_action_invalid' => 'نوع عملیات صحیح نمی‌باشد.',
    'reminder_successfully_updated' => 'یادآور با موفقیت به روزرسانی شد.',
    'reminder_successfully_deleted' => 'یادآور با موفقیت حذف شد.',
    'reminder_successfully_created' => 'یادآور با موفقیت ایجاد شد.',
    'reminder_not_found' => 'یادآور پیدا نشد.',
    'reminder_start_date_is_past' => 'تاریخ یادآور نمی‌تواند در گذشته باشد.',
    'reminder_done_date_not_ok' => 'تاریخ انجام یادآور صحیح نمی‌باشد.',
    'reminder_used_in_note' => 'از این یادآور در یک وظیفه استفاده شده است. نمی‌توانید آن را پاک کنید.',
    'reminder_done_duplicate' => 'این یادآور قبلا انجام شده است.',
];
