<span class="ltr">{{ $user->fullname }}</span> -
<span class="ltr">{{ $user->phone_number }}</span>
<a href="{{ route('dashboard.report.userActivity', ['id' => $user->id]) }}"><i class="icon-user"></i></a>
