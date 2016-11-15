const STATUS_NAMES = {
    info: 'alert-info',
    success: 'alert-success',
    error: 'alert-danger',
    warning: 'alert-warning'
};

$(document).ready(function () {
    $('#btn_delete_hide').click(function (event) {
        event.preventDefault();
        $('#modal-from-dom').modal('hide');
    });
    $(function () {
        $('input[name=repo_name]').keypress(function (e) {
            if (e.which == 13) {
                $('#btn_create').click();
            }
        })
    });
    $(document).on('click', 'a[data-repo-name]', function (event) {
        event.preventDefault();
        var repository = $(this).attr('data-repository');
        var repo_name = $(this).attr('data-repo-name');

        $('#btn_delete').one('click', function (event) {
            event.preventDefault();
            $.post('/admin/delete',
                {
                    'repository': repository,
                    'repo_name': repo_name
                }, function (data) {
                    showMessage(data.status, data.message);
                }, "json")
                .fail(function (data) {
                    showMessage('error', data.responseText);
                })
                .always(function (data) {
                    $('#modal-from-dom').modal('hide');
                    $('.table-responsive').load('index.php .table-responsive');
            });
        });

        $('#modal-from-dom').one('show.bs.modal', function () {
            $('#modal-title').text('Delete Repository "' + repo_name.replace(/^.*[\\\/]/, '') + '"');
        }).one('hide.bs.modal', function () {
            $('#btn_delete').off('click');
        }).modal({
            show: true,
            keyboard: true,
            backdrop: true
        });

    });
    $('#btn_create').click(function (event) {
        event.preventDefault();
        $.post('/admin/create',
            {
                'repository': $('select[name=repository]').val(),
                'repo_name': $('input[name=repo_name]').val()
            }, function (data) {
                $('#input').val('');
                showMessage(data.status, data.message);
            }, "json")
            .fail(function (data) {
                showMessage('error', data.responseText);
            }).always(function (data) {
                $('#input').focus();
                $('.table-responsive').load('index.php .table-responsive');
        })
        ;
    });
});

function capitalize(string) {
    if (typeof string === 'string' && string.length > 0) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }
    else
        return "";
}

function showMessage(status, message) {
    var alert = `<div class="alert ` + STATUS_NAMES[status] + ` alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>` + capitalize(status) + `!</strong> ` + message + `</div>`;
    $(alert).prependTo('.messages').delay(5000).queue(function () {
        $(this).remove();
    });
}