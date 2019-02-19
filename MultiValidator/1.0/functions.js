$(document).on('submit', '[validator][data-form]', function (event) {

    event.preventDefault();

    var data_action = $(this).data('action');
    var data_form = $(this).data('form');
    var data_form_id = $(this).data('form-id');
    var data_valid = $(this).data('valid');

    var errors = 0;
    var form_data = new Array();
    form_data.push({'name': 'action', 'value': data_action});
    form_data.push({'name': 'form_id', 'value': data_form_id});
    form_data.push({'name': 'data_form', 'value': data_form});

    $(this).find('[data-element]').each(
        function () {
            var data_element = $(this).data('element');
            var data_type = $(this).data('type');
            var value = $(this).val();


            form_data.push({'name': data_element, 'value': value});

            var attr_required = $(this).attr('required');

            if (typeof attr_required !== typeof undefined && attr_required !== false) {
                if (data_valid == 'js') {
                    var rules = {};

                    switch (data_type) {
                        case 'email':
                            rules = {
                                required: true,
                                email: {
                                    message: 'Email введен не корректно'
                                },
                                stop: true,
                            };
                            break;

                        case 'name':
                            rules = {
                                required: {
                                    message: 'Поле не может быть пустым',
                                    required: true
                                },
                                stop: true,
                            };
                            break;

                        case 'password':
                            rules = {
                                required: true,
                                min: {
                                    validate: function (value, pars) {
                                        return typeof value === 'string' && value.length >= 5;
                                    },
                                    message: 'Минимальная длина пароля 5 символов',
                                    min: 5
                                },
                                stop: true,
                                max: 100,
                            };
                            break;
                    }
                    var result = approve.value(value, rules);

                    if (result.approved === true) {
                        $(this).removeClass('input_error').addClass('input_success');
                        $('[data-error-element=' + data_element + ']').hide();
                    }
                    else {
                        $(this).removeClass('input_success').addClass('input_error');
                        $('[data-error-element=' + data_element + ']').text(result.errors[0]).show();
                    }

                    if (result.errors.length > 0)
                        errors += result.errors.length;
                }
            }
        }
    );

    if (errors === 0) {

        // var file_data = $('#cm_builder_pictures').prop('files');
        //
        // $.each( file_data, function( key, value ){
        //     form_data.append(key, value);
        // });


        // form_data.push({'name': 'js_now', 'value': $.now()});
        send_request(form_data);
    }

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


