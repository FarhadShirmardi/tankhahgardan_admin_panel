<!-- Bootstrap and necessary plugins -->
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
{{--<script src="{{ asset('js/jquery.min.js') }}"></script>--}}
<script src="{{ asset('js/pace.min.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/i18n/en.js') }}"></script>
<script src="{{ asset('js/i18n/fa.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
{{--<script src="{{ asset('js/messages_fa.js') }}"></script>--}}
<script src="{{ asset('js/cropper.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<script src="{{ asset('js/loadingoverlay.min.js') }}"></script>
<script src="{{ asset('js/jquery.toast.min.js') }}"></script>
<script src="{{ asset('js/highcharts.js') }}"></script>

<!-- Plugins and scripts required by all views -->
{{--<script src="{{ asset('dashboard/js/views/charts.js') }}"></script>--}}

<script src="{{ asset('js/jscolor.min.js') }}"></script>

<!-- GenesisUI main scripts -->

<script src="{{ asset('dashboard/js/app.js') }}"></script>
{{--<script src="{{ asset('dashboard/js/views/selectr.js') }}"></script>--}}

<!-- Plugins and scripts required by this views -->
<script src="{{ asset('js/jstree.min.js') }}"></script>

<!-- Custom scripts required by this view -->
<script src="{{ asset('dashboard/js/views/main.js') }}"></script>


<script>
    jQuery(document).ready(function ($) {
        $(".clickableRow").click(function () {
            let target = $(this).data("target");
            window.open($(this).data("href"), !target ? '_blank' : target);
        });
    });
</script>
