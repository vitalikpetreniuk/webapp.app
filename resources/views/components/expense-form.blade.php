<div id="modal-expenses" class="modal alt d-flex flex-column">
    <span class="modal__title">Expenses</span>
    {{--    <form class="d-flex flex-column" action="">--}}
    {{--        <div class="modal__select select blue mb-15">--}}
    {{--            <input class="select__input" type="hidden" name="expense-category" id="expense-category">--}}
    {{--            <div class="select__head">Category</div>--}}
    {{--            <ul class="select__list" style="display: none;">--}}
    {{--                <li class="select__item" data-prop="1">Category 1</li>--}}
    {{--                <li class="select__item" data-prop="2">Category 2</li>--}}
    {{--                <li class="select__item" data-prop="3">Category 3</li>--}}
    {{--            </ul>--}}
    {{--        </div>--}}
    {{--        <div class="modal__select select blue mb-15">--}}
    {{--            <input class="select__input" type="hidden" name="">--}}
    {{--            <div class="select__head">Expenses type</div>--}}
    {{--            <ul class="select__list" style="display: none;">--}}
    {{--                <li class="select__item" data-prop="1">Summ  $ (USD)</li>--}}
    {{--                <li class="select__item" data-prop="2">% of Ad spend</li>--}}
    {{--                <li class="select__item" data-prop="3">% of net revenue</li>--}}
    {{--            </ul>--}}
    {{--        </div>--}}
    {{--        <div class="cat1">--}}
    {{--            <div class="modal__url d-flex align-items-center">--}}
    {{--                <input class="field-text align-self-end blue" type="text" placeholder="or paste URL adress">--}}
    {{--                <button type="submit" href="#" class="blue">Submit</button>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="cat2">--}}
    {{--            <p class="modal__text">--}}
    {{--                select the date range for which you want to try on data--}}
    {{--            </p>--}}
    {{--            <div class="datepicker d-flex mb-20">--}}
    {{--                <input id="datepicker-modal" type="text" class="flatpickr-input" readonly="readonly">--}}
    {{--                <div class="datepicker__icon d-flex align-items-center justify-content-center">--}}
    {{--                    <img src="{{ asset('frontend/images/dist/icons/calendar.svg') }}" alt="">--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <input class="input-field" type="number" step="10" placeholder="Summ">--}}
    {{--            <div class="autoComplete mt-15">--}}
    {{--                <div class="autoComplete_wrapper">--}}
    {{--                    <div class="autoComplete_wrapper" role="combobox" aria-owns="autoComplete_list_1" aria-haspopup="true" aria-expanded="false"><input id="autoComplete" type="text" tabindex="1" aria-controls="autoComplete_list_1" aria-autocomplete="both" placeholder="Source"><ul id="autoComplete_list_1" role="listbox" hidden=""></ul></div>--}}
    {{--                </div>--}}
    {{--                <div class="selection"></div>--}}
    {{--            </div>--}}
    {{--            <input class="input-field mt-15" type="text" placeholder="Tag">--}}
    {{--            <textarea class="input-field textarea mt-15" name="" placeholder="Comment"></textarea>--}}
    {{--            <!--		<div class="repeat d-flex align-items-center mt-20">-->--}}
    {{--            <!--			<img class="mr-7" src="images/dist/icons/repeat.svg" alt="repeat">-->--}}
    {{--            <!--			<span>Repeat monthly</span>-->--}}
    {{--            <!--		</div>-->--}}
    {{--            <div class="modal__select secondary select alt mt-15">--}}
    {{--                <input class="select__input" type="hidden" name="">--}}
    {{--                <div class="select__head">--}}
    {{--                    <div class="repeat d-flex align-items-center">--}}
    {{--                        <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}" alt="repeat">--}}
    {{--                        <span>Repeat monthly</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <ul class="select__list" style="display: none;">--}}
    {{--                    <li class="select__item">--}}
    {{--                        <div class="repeat d-flex align-items-center">--}}
    {{--                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}" alt="repeat">--}}
    {{--                            <span>Repeat monthly</span>--}}
    {{--                        </div>--}}
    {{--                    </li>--}}
    {{--                    --}}{{--                <li class="select__item">--}}
    {{--                    --}}{{--                    <div class="repeat alt d-flex align-items-center">--}}
    {{--                    --}}{{--                        <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat-grey.svg') }}" alt="repeat">--}}
    {{--                    --}}{{--                        <span>Weekly</span>--}}
    {{--                    --}}{{--                    </div>--}}
    {{--                    --}}{{--                </li>--}}
    {{--                    <li class="select__item">--}}
    {{--                        <div class="repeat alt d-flex align-items-center">--}}
    {{--                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat-grey.svg') }}" alt="repeat">--}}
    {{--                            <span>Monthly</span>--}}
    {{--                        </div>--}}
    {{--                    </li>--}}
    {{--                    <li class="select__item">--}}
    {{--                        <div class="repeat alt d-flex align-items-center">--}}
    {{--                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat-grey.svg') }}" alt="repeat">--}}
    {{--                            <span>Save for all time</span>--}}
    {{--                        </div>--}}
    {{--                    </li>--}}
    {{--                </ul>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    {{--        <button type="submit" class="btn__modal grey mt-20 d-flex align-items-center justify-content-center">--}}
    {{--            <img src="{{ asset('frontend/images/dist/icons/copy.svg') }}" alt="copy">--}}
    {{--            <span>Дублировать</span>--}}
    {{--        </button>--}}
    {{--        <button type="submit" class="btn__modal delete mt-12 d-flex align-items-center justify-content-center">--}}
    {{--            <img src="{{ asset('frontend/images/dist/icons/basket.svg') }}" alt="basket">--}}
    {{--            <span>Удалить</span>--}}
    {{--        </button>--}}
    {{--        <button type="submit" class="btn__modal blue mt-12">Cохранить изменения</button>--}}
    {{--    </form>--}}
    <form action="{{ route('expenses.val') }}" method="POST">
        <div class="modal__select select blue mb-15">
            <input class="select__input" onchange="expenseInputChange(event)" type="hidden" name="expense-category"
                   id="expense-category">
            <div class="select__head">Category</div>
            <ul class="select__list" style="display: none;">
                <li class="select__item" data-prop="1">Category 1</li>
                <li class="select__item" data-prop="2">Category 2</li>
                {{--                <li class="select__item" data-prop="3">Category 3</li>--}}
            </ul>
        </div>
        <div class="cat1 cat2">
            <div class="modal__select select blue mb-15">
                <input class="select__input" type="hidden" name="">
                <div class="select__head">Expenses type</div>
                <ul class="select__list" style="display: none;">
                    <li class="select__item" data-prop="1">Summ $ (USD)</li>
                    <li class="select__item" data-prop="2">% of Ad spend</li>
                    <li class="select__item" data-prop="3">% of net revenue</li>
                </ul>
            </div>
        </div>
        <div class="cat2">
            <p class="modal__text">
                select the date range for which you want to try on data
            </p>
            <div class="datepicker d-flex mb-20">
                <input id="monthpicker"/>
                <div class="datepicker__icon d-flex align-items-center justify-content-center">
                    <img src="{{ asset('frontend/images/dist/icons/calendar.svg') }}" alt="">
                </div>
            </div>

            <input class="input-field" type="number" step="10" placeholder="Summ">
            <div class="autoComplete mt-15">
                <div class="autoComplete_wrapper">
                    <input id="autoComplete" type="text" tabindex="1">
                </div>
                <div class="selection"></div>
            </div>
            <input class="input-field mt-15" type="text" placeholder="Tag">
            <textarea class="input-field textarea mt-15" name="" placeholder="Comment"></textarea>
            <div class="modal__select secondary select alt mt-15">
                <input class="select__input" type="hidden" name="">
                <div class="select__head">
                    <div class="repeat d-flex align-items-center">
                        <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}" alt="repeat">
                        <span>Repeat monthly</span>
                    </div>
                </div>
                <ul class="select__list" style="display: none;">
                    <li class="select__item">
                        <div class="repeat d-flex align-items-center">
                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}" alt="repeat">
                            <span>Repeat monthly</span>
                        </div>
                    </li>
                    {{--                <li class="select__item">--}}
                    {{--                    <div class="repeat alt d-flex align-items-center">--}}
                    {{--                        <img class="mr-7" src="images/dist/icons/repeat-grey.svg" alt="repeat">--}}
                    {{--                        <span>Weekly</span>--}}
                    {{--                    </div>--}}
                    {{--                </li>--}}
                    <li class="select__item">
                        <div class="repeat alt d-flex align-items-center">
                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat-grey.svg') }}"
                                 alt="repeat">
                            <span>Monthly</span>
                        </div>
                    </li>
                    <li class="select__item">
                        <div class="repeat alt d-flex align-items-center">
                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat-grey.svg') }}"
                                 alt="repeat">
                            <span>Save for all time</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="cat1 default-shown">
            <div id="expensesForm" class="drag-drop d-flex flex-column">
                <div class="drag-drop__input d-flex flex-column align-items-center">
                    <input class="drag-drop__file" type="file" name="files" id="expensesFile"
                           data-multiple-caption="{count} files selected"/>
                    <div class="drag-drop__selected d-flex flex-column align-items-center">
                        <div class="drag-drop__close">
                            <img src="{{ asset('frontend/images/dist/icons/close.svg') }}" alt="close">
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('frontend/images/dist/icons/doc.svg') }}" alt="doc">
                        </div>
                        <span class="filename"></span>
                    </div>
                    <label for="expensesFile" class="drag-drop__label d-flex justify-content-center">
                        <img src="{{ asset('frontend/images/dist/icons/add-doc.svg') }}" alt="add">
                        <span>Select file</span>
                    </label>
                    <!--			<button class="drag-drop__button" type="submit">Upload</button>-->
                </div>
                <div class="drag-drop__success">Successfully sent!</div>
                <div class="drag-drop__error">Warning or error!</div>
            </div>
            <div class="modal__url d-flex align-items-center">
                <input class="field-text align-self-end blue" type="text" placeholder="or paste URL adress">
                <button type="submit" href="#" class="blue">Submit</button>
            </div>
        </div>

        {{--        <button type="submit" class="btn__modal grey mt-20 d-flex align-items-center justify-content-center">--}}
        {{--            <img src="{{ asset('frontend/images/dist/icons/copy.svg') }}" alt="copy">--}}
        {{--            <span>Дублировать</span>--}}
        {{--        </button>--}}
{{--        <button type="submit" class="btn__modal delete mt-12 d-flex align-items-center justify-content-center">--}}
{{--            <img src="{{ asset('frontend/images/dist/icons/basket.svg') }}" alt="basket">--}}
{{--            <span>Удалить</span>--}}
{{--        </button>--}}

        <a href="#" class="btn__modal blue mt-30" download>Download Sample</a>
    </form>
    <div class="modal__close">
        <img src="{{ asset('frontend/images/dist/icons/x.svg') }}" alt="x">
    </div>
</div>
{!! $validator->selector('#expenseF') !!}
<script>
    jQuery(function ($) {
        $('#expenseF').on('submit', function (e) {
            let form = '#expenseF';
            e.preventDefault();
            let formdata = new FormData(document.querySelector(form))
            $.ajax({
                "url": "{{ route('revenues.store') }}",
                "type": "POST",
                "processData": false,
                "contentType": false,
                "data": formdata,
                success: function (response) {
                    let result = JSON.parse(response)
                    if (!result.success) {
                        expenseErrorCatch(response)
                        return;
                    }
                    $(form).find('.drag-drop__success').text(result.message).addClass('show');
                },
                error: expenseErrorCatch,
            });
        });

        function expenseErrorCatch(response) {
            (typeof response == 'string') ? result = JSON.parse(response) : result = response;
            $('#expensesF').find(`.drag-drop__error`).text(result.message).addClass('show');
        }
    })
</script>
