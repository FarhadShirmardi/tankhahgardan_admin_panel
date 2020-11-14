<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ردیف</th>
            <th>تاریخ</th>
            <th>موضوع</th>
            <th>منبع</th>
            <th>پلتفرم</th>
            <th>متن</th>
            <th>نام کاربر</th>
            <th>شماره کاربر</th>
            <th>کارشناس</th>
            <th>تاریخ به‌روزرسانی پاسخ</th>
            <th>پاسخ بازخورد</th>
            @if(auth()->user()->hasRole('Admin'))
                <th>امتیاز</th>
            @endif
            <th>وضعیت</th>
        </tr>
        </thead>
        <tbody>
        @foreach($feedbacks as $feedback)
            <tr class="clickableRow table-row-clickable align-center
                @if(isset($feedback_item) and $feedback->feedback_id == $feedback_item->feedback_id) table-primary @endif"
                @if($feedback->feedback_id)
                data-href="{{ route('dashboard.viewFeedback', ['feedback_id' => $feedback->feedback_id]) }}"
                @else
                data-href="{{ route('dashboard.commentView', ['id' => $feedback->comment_id]) }}"
                @endif
            >
                @if($feedbacks instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ ($feedbacks->currentPage() - 1) * $feedbacks->perPage() + $loop->iteration }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td class="ltr">{{ $feedback->date }}</td>
                <td>{{ $feedback->title }}</td>
                <td>{{ $feedback->source }}</td>
                <td>{{ $feedback->platform }}</td>
                <td class="breaking-text">{{ $feedback->text }}</td>
                <td>{{ $feedback->full_name }}</td>
                <td>{{ $feedback->user_phone_number }}</td>
                <td>{{ $feedback->panel_user_name }}</td>
                <td class="ltr">{{ $feedback->response_text_update_time }}</td>
                <td class="breaking-text">{{ $feedback->response_text }}</td>
                @if(auth()->user()->hasRole('Admin'))
                    <td>{{ $feedback->response_score }}</td>
                @endif
                <td>{{ $feedback->state }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
