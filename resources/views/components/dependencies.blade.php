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
                    values: data.data
                },
                errors: {
                    empty:'Attention, vous ne pouvez pas ajouter un tag vide.',
                    minLength:'Attention, votre tag doit avoir au minimum %s caractères.',
                    maxLength:'Attention, votre tag ne doit pas dépasser %s caractères.',
                    max:'Attention, le nombre de tags ne doit pas dépasser %s.',
                    exists:'Attention, ce tag existe déjà !',
                    autocomplete_only:'Attention, vous devez sélectionner une valeur dans la liste.',
                    timeout: 8000
                }
            });
        });
    })
</script>
<script src="{{ asset('frontend/js/app.min.js') }}"></script>
