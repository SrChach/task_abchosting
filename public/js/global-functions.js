function destroy_session () {
    localStorage.removeItem('user_id');
    localStorage.removeItem('cash');
    localStorage.removeItem('username');
    window.location = 'index.html';
}

function logout () {
    $.get('controller/user.php?option=logout', function (res) {
        if ( typeof(res.error) != 'undefined' ) alert(res.error);
        if (res.data == true) destroy_session();
    })
}

function is_response_error (res) {
    if ( typeof(res.error) != 'undefined' ) {
        alert (res.error);
        if ( res.error_code == 1 )
            destroy_session()
        return true;
    }
    return false;
}