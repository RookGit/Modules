function log(data) {
    console.log(data);
}

// Сгенерировать случайный id
function generate_rand_id(length)
{
    var text = "";
    var possible = "abcdefghijklmnopqrstuvwxyz12345689";

    for( var i=0; i < length; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}