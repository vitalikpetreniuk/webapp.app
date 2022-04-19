{{--<script src="https://cdn.amcharts.com/lib/5/index.js"></script>--}}
{{--<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>--}}
{{--<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>--}}
<script src="{{ asset('frontend/js/Flat-jQuery-Tags-Input/src/index.js') }}"></script>
<script>
    jQuery(window).ready(async function ($) {
        instance.get('/tags').then(function (data) {
            $('#tags').inputTags({
                minLength: 1,
                max: 15,
                autocomplete: {
                    values: data.data || []
                },
                errors: {
                    empty:'Please note that you cannot add an empty tag.',
                    minLength:`Attention, your tag must have at least ${this.minLength} characters.`,
                    max:`Attention, the number of tags must not exceed ${this.max}.`,
                    exists:'Warning, this tag already exists!',
                    timeout: 8000
                }
            });
        });
    })
</script>
<script src="{{ asset('frontend/js/app.min.js') }}"></script>
