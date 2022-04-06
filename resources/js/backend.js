var instance = axios.create({
    withCredentials: true,
    baseURL: `${window.location.origin}/api`
})

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

const getExpense = async (ID) => {
    try {
        const resp = await instance.get(`/expense/${ID}`);
        return await {...resp.data, expense: true}
    } catch (err) {
        console.error(err);
    }
}

async function newExpense(formdata) {
    await $.ajax({
        "url": expenseformvars.url,
        "type": "POST",
        "processData": false,
        "contentType": false,
        "data": formdata,
        success: function (response) {
            let result = response.responseJSON ? response.responseJSON : JSON.parse(response);
            if (!result.success) {
                expenseErrorCatch(response)
                return;
            }
            $('#modal-expenses').find(`.drag-drop__error`).removeClass('show')
            $('#modal-expenses').find('.drag-drop__success').text(result.message).addClass('show');

            setTimeout(() => $('#modal-expenses').find(`.drag-drop__success, .drag-drop__error`).removeClass('show'), 5000);
        },
        error: expenseErrorCatch,
    });
}

async function newRevenue(formdata) {
    await $.ajax({
        "url": revenueformvars.url,
        "type": "POST",
        "processData": false,
        "contentType": false,
        "data": formdata,
        success: function (response) {
            let result = response.responseJSON ? response.responseJSON : JSON.parse(response);
            if (!result.success) {
                revenueErrorCatch(response)
                return;
            }

            $('#modal-revenue').find('.drag-drop__success').addClass('show').text(result.message)

            setTimeout(() => $('#modal-revenue').find(`.drag-drop__success, .drag-drop__error`).removeClass('show'), 5000);
        },
        error: revenueErrorCatch,
    });
}

const updateExpense = async (ID, data) => {
    try {
        const resp = await instance.post(`/expense/${ID}`, {
            ...data
        })

        console.log(resp.data)
    } catch (err) {
        console.error(err);
    }
}

const getRevenue = async (ID) => {
    try {
        const resp = await instance.get(`/revenue/${ID}`);
        return await {...resp.data, revenue: true}
    } catch (err) {
        console.error(err);
    }
}

const updateRevenue = async (ID, data) => {
    try {
        const resp = await instance.post(`/revenue/${ID}`, {
            ...data
        })
    } catch (err) {
        console.error(err);
    }
}

const deleteRevenue = async (ID) => {
    try {
        const resp = await instance.delete(`/revenue/${ID}`)
    } catch (err) {
        console.error(err);
    }
}

const deleteExpense = async (ID) => {
    try {
        const resp = await instance.delete(`/expense/${ID}`)
    } catch (err) {
        console.error(err);
    }
}

function expenseErrorCatch(response) {
    let result = response.responseJSON ? response.responseJSON : JSON.parse(response);
    console.log(result)
    $('#modal-expenses').find(`.drag-drop__success`).removeClass('show')
    $('#modal-expenses').find(`.drag-drop__error`).text(result.message).addClass('show');

    setTimeout(() => $('#modal-expenses').find(`.drag-drop__success, .drag-drop__error`).removeClass('show'), 5000);
}

function revenueErrorCatch(response) {
    let result = response.responseJSON ? response.responseJSON : JSON.parse(response);
    $('#revenueForm').append(`<span class="help-block error-help-block">${result.message}</span>`);
    setTimeout(() => $('#modal-revenue').find(`.drag-drop__success, .drag-drop__error`).removeClass('show'), 5000);
}

function resetForm(form) {
    form[0].reset();

    console.log(form.find('.type1'))
    form.find('.type1').show();
    form.find('.type2').hide().find('.edited-amount').val('');
}

function closeModals() {
    $('.modal, .modal-overlay').removeClass('active')
    $('html, body').removeClass('_over-hidden')
}

jQuery(function ($) {
    window.expenseInputChange = function (e) {
        console.log($(e.target).val())
        let val = parseInt($(e.target).val());
        if (val == 1) {
            console.log('1')
            $('#modal-expenses').find(".cat2, .cat3").hide();
            $('#modal-expenses').find(".cat1").show();
        } else if (val == 2) {
            console.log('2')
            $('#modal-expenses').find(".cat1, .cat3").hide();
            $('#modal-expenses').find(".cat2").show();
        } else if (val == 3) {
            console.log('3')
            $('#modal-expenses').find(".cat1, .cat2").hide();
            $('#modal-expenses').find(".cat3").show();
        }
    }

    window.cat3InputChange = function (e) {
        let val = parseInt($(e.target).val());
        if (val == 1) {
            console.log('1')
            $('#modal-expenses').find(".cat3_2, .cat3_3").hide();
            $('#modal-expenses').find(".cat3_1").show();
        } else if (val == 2) {
            console.log('2')
            $('#modal-expenses').find(".cat3_1, .cat3_3").hide();
            $('#modal-expenses').find(".cat3_2").show();
        } else if (val == 3) {
            console.log('3')
            $('#modal-expenses').find(".cat3_1, .cat3_2").hide();
            $('#modal-expenses').find(".cat3_3").show();
        }
    }

    window.formSubmit = function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        console.log('expenseFormSubmit', e.target)
        let form = $(e.target);
        let formdata = new FormData(e.target);
        let id = form.attr('data-id');
        const type = $(`tr[data-id=${id}]`).attr('data-type');
        let newamount = formdata.get('edited-amount');
        console.log(form, type, id)
        if (newamount.length) {

            if (type === 'expense') {
                updateExpense(id, {
                    amount: Number(newamount).toFixed(2),
                })
            } else {
                updateRevenue(id, {
                    amount: Number(newamount).toFixed(2),
                })
            }

            resetForm(form);

            $(`tr[data-id=${form.data('id')}]`).find('.plus span, .minus span').text('-$' + newamount)

            closeModals();
        } else {
            if (e.target.id === 'expenseF') {
                newExpense(formdata);
            }else {
                newRevenue(formdata);
            }
            resetForm(form);
        }

        return false;
    }

    // const $autoComplete = document.querySelector('.autoComplete')
    //
    // if ($('.autoComplete').length) {
    //     //https://tarekraafat.github.io/autoComplete.js/demo/db/generic.json
    //     //https://webapp.test/sources?user_id=1
    //     fetch(
    //         backendvars.autocompleteroute
    //     ).then(async (source) => {
    //         const fetched = await source.json();
    //         const autoCompleteJS = new autoComplete({
    //             data: {
    //                 src: async () => {
    //                     let arr = [];
    //                     for (let key in fetched[0]) {
    //                         console.warn(key)
    //                         arr.push(fetched[0][key]);
    //                     }
    //                     console.log(arr)
    //                     return arr;
    //                 },
    //                 cache: true,
    //             },
    //             placeHolder: "Source",
    //             resultsList: {
    //                 element: (list, data) => {
    //                     const info = document.createElement("p");
    //                     if (data.results.length > 0) {
    //                         info.innerHTML = `Displaying <strong>${data.results.length}</strong> out of <strong>${data.matches.length}</strong> results`;
    //                     } else {
    //                         // info.innerHTML = `Создать категорию <strong>"${data.query}"</strong>`;
    //                     }
    //                     list.prepend(info);
    //                 },
    //                 noResults: true,
    //                 maxResults: 15,
    //                 tabSelect: true
    //             },
    //             resultItem: {
    //                 element: (item, data) => {
    //                     // Modify Results Item Style
    //                     item.style = "display: flex; justify-content: space-between;";
    //                     // Modify Results Item Content
    //                     item.innerHTML = `
    //   <span style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
    //     ${data.match}
    //   </span>
    //   <span style="display: flex; align-items: center; font-size: 13px; font-weight: 100; text-transform: uppercase; color: rgba(0,0,0,.2);">
    //     ${data.value}
    //   </span>`;
    //                 },
    //                 highlight: true
    //             },
    //             events: {
    //                 input: {
    //                     focus: () => {
    //                         if (autoCompleteJS.input.value.length) autoCompleteJS.start();
    //                     }
    //                 }
    //             }
    //         });
    //         autoCompleteJS.input.addEventListener("selection", function (event) {
    //             const feedback = event.detail;
    //             autoCompleteJS.input.blur();
    //             // Prepare User's Selected Value
    //             const selection = feedback.selection.value;
    //             console.log(feedback.selection.value)
    //             // Render selected choice to selection div
    //             document.querySelector(".selection").innerHTML = selection;
    //             // Replace Input value with the selected value
    //             autoCompleteJS.input.value = selection;
    //             // Console log autoComplete data feedback
    //             console.log(feedback);
    //         });
    //     });
    // }

    $('.btn__edit').on('click', async function () {
        const type = $(this).closest('tr').data('type').trim();
        const ID = $(this).closest('tr').data('id');
        let form = (type === 'expense') ? $('#expenseF') : $('#revenueF');
        form.attr('data-id', ID);

        if (type === 'expense') {
            let item = await getExpense(ID);

            $('#modal-expenses, .modal-overlay').addClass('active')
            $('html, body').addClass('_over-hidden')

            $('#modal-expenses .type1').hide()

            $('#modal-expenses .type2').show().find('.edited-amount').val(item.amount);
        } else {
            let item = await getRevenue(ID);

            $('#modal-revenue, .modal-overlay').addClass('active')
            $('html, body').addClass('_over-hidden')

            $('#modal-revenue .type1').hide()

            $('#modal-revenue .type2').show().find('.edited-amount').val(item.amount);
        }
    })

    $('.btn__delete').on('click', async function () {
        const type = $(this).closest('tr').data('type').trim();
        const ID = $(this).closest('tr').data('id');

        if (type === 'expense') {
            deleteExpense(ID);
        } else {
            deleteRevenue(ID)
        }

        $(this).closest('tr').remove();
    });
})
