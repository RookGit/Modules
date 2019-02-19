$(document).on('submit', '[validator][data-form]', function (event) {

    var data_form = $(this).data('form');
    var data_form_id = $(this).data('form-id');
    var data_valid = $(this).data('valid');

    $(this).find('[data-element]').each(
        function () {
            var data_element = $(this).data('element');
            var data_type = $(this).data('type');
            var value = $(this).val();

            log(data_element);

            if(data_valid == 'js')
            {
                var rules = {};

                switch(data_type)
                {
                    case 'email':
                        rules = {
                            required: true,
                            email: {
                                message: 'Email введен не корректно'
                            }
                        };
                        break;

                    case 'password':
                        rules = {
                            required: true,
                            min: {
                                value: 5,
                                message: 'Минимальное число символов в пароле - 5'
                            },
                            max: 100,
                        };
                        break;
                }

                var result = approve.value(value, rules);

                if(result.approved === true)
                {
                    $(this).removeClass('input_error').addClass('input_success');
                    $('[data-error-element=' + data_element + ']').hide();
                }
                else
                {
                    $(this).removeClass('input_success').addClass('input_error');
                    $('[data-error-element=' + data_element + ']').text(result.errors[0]).show();
                }
                log(result);
            }
        }
    );

    if (data_valid == 'js' || data_valid == 'ajax')
        return false;
});

// Первичная проходка по всем формам
$('[validator][data-form]').each(function () {

    $(this)
        .attr({'data-form-id': generate_rand_id(10)})
        .find('[data-element]').each(
        function () {
            var data_element = $(this).data('element');
            $('[data-error-element=' + data_element + ']').hide();
        }
    );
});
