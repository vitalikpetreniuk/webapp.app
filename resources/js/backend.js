window.instance = axios.create({
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
    return await $.ajax({
        "url": apivars.expenseurl,
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
            return true;
        },
        error: (error) => {
            expenseErrorCatch(error);
            return false;
        },
    });
}

async function newRevenue(formdata) {
    return await $.ajax({
        "url": apivars.revenueurl,
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
            return true;
        },
        error: (error) => {
            revenueErrorCatch(error);

            return false;
        },
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

    window.formSubmit = async function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        console.log('expenseFormSubmit', e.target)
        let form = $(e.target);
        let formdata = new FormData(e.target);
        let id = form.attr('data-id');
        const type = $(`tr[data-id=${id}]`).attr('data-type');
        let newamount = formdata.get('edited-amount');
        let oldvalue = $(`tr[data-id=${id}] .minus span`).text();
        let ispercent = !!oldvalue.includes('%');
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

            if (ispercent) {
                $(`tr[data-id=${form.data('id')}]`).find('.plus span, .minus span').text(newamount.toLocaleString('en-US', {minimumFractionDigits: 2}) + '%')
            } else {
                $(`tr[data-id=${form.data('id')}]`).find('.plus span, .minus span').text('-$' + newamount.toLocaleString('en-US', {minimumFractionDigits: 2}))
            }

            closeModals();
        } else {
            let res;
            if (e.target.id === 'expenseF') {
                res = await newExpense(formdata);
            } else {
                res = await newRevenue(formdata);
            }
            console.log(res)
            if (res) resetForm(form);
        }

        return false;
    }

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
