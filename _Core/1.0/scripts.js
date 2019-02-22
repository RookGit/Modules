var system = {};

system.url = location.pathname.split('/');
system.url.splice(0, 1);

if (system.url[0] == '')
    system.url[0] = 'root';

function log(data) {
    console.log(data);
}

// Сгенерировать случайный id
function generate_rand_id(length) {
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyz12345689";

    for (var i = 0; i < length; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function send_request(params) {

    $.ajax({
        type: 'POST',
        url: '/api',
        dataType: 'json',
        data: params,
        success: function (data) {

            if (data.response.render != null) {

                window[data.response.render](data);

            }

        }

    });

}

