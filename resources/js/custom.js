$(function () {

    $('.del-event').on('click', function () {

        let val = $(this).val();
        $.ajax({
            url: "/del-event",
            data: {
                'val': val,
            },
            success: function (result) {
                alert('Your event deleted');
                location.reload();
            }
        })
    });

})
