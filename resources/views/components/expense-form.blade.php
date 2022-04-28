<div id="modal-expenses" class="modal alt d-flex flex-column">
    <span class="modal__title">Expenses</span>

    <form method="POST" id="expenseF" name="expenseF" onsubmit="formSubmit(event)" action="">
        @csrf
        <div class="type1">
            <div class="modal__select select blue mb-15">
                <input class="select__input" onchange="expenseInputChange(event)" type="hidden" name="expensecategory"
                       id="expense-category" value="1">
                <div class="select__head">Category</div>
                <ul class="select__list" style="display: none;">
                    <li class="select__item" data-prop="1">Ad Spend</li>
                    <li class="select__item" data-prop="2">Fixed</li>
                    <li class="select__item" data-prop="3">Variable</li>
                </ul>
            </div>

            <div class="cat2">
                <div class="modal__select select blue mb-15">
                    <input class="select__input" type="hidden" name="expensetype" value="1">
                    {{--                    <div class="select__head">Expenses type</div>--}}
                    <div class="select__head">Summ $ (USD)</div>
                    <ul class="select__list" style="display: none;">
                        <li class="select__item" data-prop="1">Summ $ (USD)</li>
                        {{--                        <li class="select__item" data-prop="2">% of Ad spend</li>--}}
                        {{--                        <li class="select__item" data-prop="3">% of net revenue</li>--}}
                    </ul>
                </div>
                <p class="modal__text">
                    select the date range for which you want to try on data
                </p>
                <div class="datepicker d-flex mb-20">
                    <div class="d-flex align-items-center">
                        <span class="mrp-icon icon-calendar d-flex justify-content-center align-items-center"><i
                                class="fa fa-calendar"></i></span>
                        <input class="flat-monthpicker" name="monthpicker2" placeholder="Choose a date"/>
                    </div>
                </div>

                <div class="form-group">
                    <input class="input-field" type="number" name="amount" placeholder="Summ">
                </div>
                <div class="autoComplete mt-15 form-group">
                    <div class="autoComplete_wrapper">
                        <input id="autoComplete" type="text" name="source" tabindex="1">
                    </div>
                    <div class="selection"></div>
                </div>
                <div class="form-group">
                    <label for="tags" class="mt-15">Tags</label>
                    <input id="tags" name="tags" class="input-field mt-15" type="text" placeholder="Tag">
                </div>
                <div class="form-group">
                    <textarea class="input-field textarea mt-15" name="comment" placeholder="Comment"></textarea>
                </div>

                {{--                <div class="modal__select secondary select alt mt-15">--}}
                {{--                    <input class="select__input" value="1" type="hidden" name="repeated2">--}}
                {{--                    <div class="select__head open">--}}
                {{--                        <div class="repeat d-flex align-items-center">--}}
                {{--                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}" alt="repeat">--}}
                {{--                            <span>Don't repeat in future</span>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <ul class="select__list" style="display: block;">--}}
                {{--                        <li data-prop="0" class="select__item">--}}
                {{--                            <div class="repeat d-flex align-items-center">--}}
                {{--                                <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}"--}}
                {{--                                     alt="repeat">--}}
                {{--                                <span>Don't repeat in future</span>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        <li data-prop="1" class="select__item">--}}
                {{--                            <div class="repeat alt d-flex align-items-center">--}}
                {{--                                <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat-grey.svg') }}"--}}
                {{--                                     alt="repeat">--}}
                {{--                                <span>Weekly</span>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        <li data-prop="2" class="select__item">--}}
                {{--                            <div class="repeat d-flex align-items-center">--}}
                {{--                                <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}"--}}
                {{--                                     alt="repeat">--}}
                {{--                                <span>Repeat monthly</span>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}
            </div>

            <div class="cat1 default-shown">
                <div id="expensesForm" class="drag-drop d-flex flex-column">
                    <div class="drag-drop__input d-flex flex-column align-items-center">
                        <input class="drag-drop__file" type="file" name="files" id="expensesFile"
                               data-multiple-caption="{count} files selected" accept=".xls, .xlsx"/>
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
                </div>
            </div>

            <div class="cat3">
                <div class="modal__select select blue mb-15">
                    <input class="select__input" type="hidden" onchange="cat3InputChange(event)" value="0"
                           name="cat3input">
                    <div class="select__head">Type</div>
                    <ul class="select__list" style="display: none;">
                        <li class="select__item" data-prop="1">Cost of good Sold</li>
                        <li class="select__item" data-prop="2">Affiliate comission</li>
                        <li class="select__item" data-prop="3">Ad spend commission</li>
                    </ul>
                </div>
                <p class="modal__text">
                    select the date range for which you want to try on data
                </p>
                <div class="datepicker d-flex mb-20">
                    <div class="d-flex align-items-center">
                        <span class="mrp-icon icon-calendar d-flex justify-content-center align-items-center"><i
                                class="fa fa-calendar"></i></span>
                        <input class="flat-monthpicker" name="monthpicker3" placeholder="Choose a date"/>
                    </div>
                </div>

                <div class="cat3_1 default-shown">
                    <div class="form-group">
                        <input class="input-field" type="number" min="1" step="0.01" max="100" name="cost-of-good-sold">
                    </div>
                </div>
                <div class="cat3_2">
                    <div class="form-group">
                        <input class="input-field" type="number" min="0.01"
                               step="0.01" max="100" name="affiliate-commission">
                    </div>
                </div>
                <div class="cat3_3">
                    <div class="form-group">
                        <input class="input-field" type="number" min="1" max="100" name="ad-spend-commission">
                    </div>
                </div>

                {{--                <div class="modal__select secondary select alt mt-15">--}}
                {{--                    <input class="select__input" value="1" type="hidden" name="repeated3">--}}
                {{--                    <div class="select__head open">--}}
                {{--                        <div class="repeat d-flex align-items-center">--}}
                {{--                            <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}" alt="repeat">--}}
                {{--                            <span>Don't repeat in future</span>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <ul class="select__list" style="display: block;">--}}
                {{--                        <li data-prop="0" class="select__item">--}}
                {{--                            <div class="repeat d-flex align-items-center">--}}
                {{--                                <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}"--}}
                {{--                                     alt="repeat">--}}
                {{--                                <span>Don't repeat in future</span>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        <li data-prop="1" class="select__item">--}}
                {{--                            <div class="repeat alt d-flex align-items-center">--}}
                {{--                                <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat-grey.svg') }}"--}}
                {{--                                     alt="repeat">--}}
                {{--                                <span>Weekly</span>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        <li data-prop="2" class="select__item">--}}
                {{--                            <div class="repeat d-flex align-items-center">--}}
                {{--                                <img class="mr-7" src="{{ asset('frontend/images/dist/icons/repeat.svg') }}"--}}
                {{--                                     alt="repeat">--}}
                {{--                                <span>Repeat monthly</span>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}
            </div>
        </div>

        <div class="drag-drop__success">Successfully sent!</div>
        <div class="drag-drop__error">Warning or error!</div>

        <div class="type2">
            <div class="form-group">
                <input step="0.01" class="input-field edited-amount" type="number" name="edited-amount">
            </div>
        </div>

        <button type="submit" class="btn__modal blue mt-30">Submit</button>
        <div class="type1">
            <div class="cat1 default-shown">
                <a href="{{ asset('xlsx/Day.xlsx') }}" class="btn__modal azure mt-30" download>Download Sample</a>
            </div>
        </div>
    </form>
    <div class="modal__close">
        <img src="{{ asset('frontend/images/dist/icons/x.svg') }}" alt="x">
    </div>
</div>
{{--<script>--}}
{{--    jQuery(document).ready(function () {--}}

{{--        $("#revenueF").each(function () {--}}
{{--            $(this).validate({--}}
{{--                errorElement: 'span',--}}
{{--                errorClass: 'help-block error-help-block',--}}

{{--                errorPlacement: function (error, element) {--}}
{{--                    if (element.parent('.input-group').length ||--}}
{{--                        element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {--}}
{{--                        error.insertAfter(element.parent());--}}
{{--                        // else just place the validation message immediately after the input--}}
{{--                    } else {--}}
{{--                        error.insertAfter(element);--}}
{{--                    }--}}
{{--                },--}}
{{--                highlight: function (element) {--}}
{{--                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error'); // add the Bootstrap error class to the control group--}}
{{--                },--}}


{{--                ignore: ":hidden, [contenteditable='true']",--}}

{{--                /*--}}
{{--                 // Uncomment this to mark as validated non required fields--}}
{{--                 unhighlight: function(element) {--}}
{{--                 $(element).closest('.form-group').removeClass('has-error').addClass('has-success');--}}
{{--                 },--}}
{{--                 */--}}
{{--                success: function (element) {--}}
{{--                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // remove the Boostrap error class from the control group--}}
{{--                },--}}

{{--                focusInvalid: true,--}}

{{--                rules: {--}}
{{--                    "files": {"laravelValidation": [["Required", [], "The files field is required.", true, "files"], ["Mimes", ["xls", "xlsx"], "The files must be a file of type: xls, xlsx.", false, "files"]]}--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
{{--{!! $validator->selector('#expenseF') !!}--}}
