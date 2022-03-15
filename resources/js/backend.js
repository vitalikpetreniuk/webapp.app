jQuery(function ($) {
    window.expenseInputChange = function (e) {
        console.log($(e.target).val())
        let val = parseInt($(e.target).val());
        if (val == 1) {
            console.log('1')
            $(".cat2").hide();
            $(".cat1").show();
        }else if (val == 2) {
            console.log('2')
            $(".cat1").hide();
            $(".cat2").show();
        }else if (val == 3) {
            console.log('3')
        }
    }
})
