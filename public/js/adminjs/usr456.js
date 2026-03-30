var __usersCurrentPage = 1;
var __pendingUserStatus = null; // { sNo, nextStatus, name, email }

function __ensureCsrf() {
    var token = $('meta[name="csrf-token"]').attr('content');
    if (!token) return;

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': token }
    });
}

function __loadUsers(page) {
    __usersCurrentPage = page || 1;

    $.ajax({
        type: "get",
        url: "/ussr" + (__usersCurrentPage ? ("?page=" + __usersCurrentPage) : ""),
        beforeSend: function () {
            if (!document.getElementById('usersLoader')) {
                $('#ch10ngcon').append("<div class='loader' id='usersLoader'></div>");
            }
        },
        complete: function () {
            $('#usersLoader').remove();
        },
        success: function (response) {
            $('#ch10ngcon').empty().append(response);
        },
        error: function () {
            alert('Error loading users');
        }
    });
}

function __showUserStatusDialog() {
    var overlay = document.getElementById('userStatusOverlay');
    if (!overlay) {
        if (__pendingUserStatus && confirm('Are you sure?')) {
            __confirmUserStatus();
        }
        return;
    }

    overlay.style.display = 'flex';
    setTimeout(function () { overlay.classList.add('show'); }, 10);
}

function __closeUserStatusDialog() {
    var overlay = document.getElementById('userStatusOverlay');
    if (!overlay) return;

    overlay.classList.remove('show');
    setTimeout(function () { overlay.style.display = 'none'; }, 300);
    __pendingUserStatus = null;
}

function __setUserStatusModalContent(pending) {
    var titleEl = document.getElementById('userStatusTitle');
    var descEl = document.getElementById('userStatusDesc');
    var iconEl = document.getElementById('userStatusIcon');
    var confirmEl = document.getElementById('userStatusConfirm');

    if (!titleEl || !descEl || !iconEl || !confirmEl) return;

    var nextStatus = (pending && pending.nextStatus) ? String(pending.nextStatus).toLowerCase() : 'inactive';
    var isActivate = nextStatus === 'active';

    titleEl.textContent = isActivate ? 'Activate User' : 'Deactivate User';

    var who = pending && (pending.name || pending.email) ? (" (" + (pending.name || pending.email) + ")") : '';
    descEl.textContent = isActivate
        ? ('Are you sure you want to activate this user' + who + '?')
        : ('Are you sure you want to deactivate this user' + who + '?');

    iconEl.classList.remove('usm-icon--danger', 'usm-icon--success');
    confirmEl.classList.remove('usm-confirm--danger');

    if (isActivate) {
        iconEl.classList.add('usm-icon--success');
        confirmEl.textContent = 'Yes, Activate';
    } else {
        iconEl.classList.add('usm-icon--danger');
        confirmEl.classList.add('usm-confirm--danger');
        confirmEl.textContent = 'Yes, Deactivate';
    }
}

function __confirmUserStatus() {
    if (!__pendingUserStatus) return;

    __ensureCsrf();

    var sNo = __pendingUserStatus.sNo;
    var nextStatus = __pendingUserStatus.nextStatus;

    $.ajax({
        type: "post",
        url: "/admin/users/" + encodeURIComponent(String(sNo)) + "/status",
        data: { status: nextStatus },
        success: function () {
            __closeUserStatusDialog();
            __loadUsers(__usersCurrentPage);
        },
        error: function (xhr) {
            __closeUserStatusDialog();
            var msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to update status';
            alert(msg);
        }
    });
}

$('#da2026').click(function (e) {
    e.preventDefault();
    __loadUsers(1);
});

// Pagination: keep this delegated (users list is loaded via AJAX)
$(document).on('click', '#ch10ngcon .pagination a', function (event) {
    event.preventDefault();
    var page = String($(this).attr('href') || '').split('page=')[1];
    var pageNum = parseInt(page, 10);
    __loadUsers(isNaN(pageNum) ? 1 : pageNum);
});

// User status button + modal
$(document).on('click', '.js-user-status-btn', function (e) {
    e.preventDefault();

    __pendingUserStatus = {
        sNo: $(this).data('user-sno'),
        nextStatus: $(this).data('next-status'),
        name: $(this).data('user-name'),
        email: $(this).data('user-email')
    };

    __setUserStatusModalContent(__pendingUserStatus);
    __showUserStatusDialog();
});

$(document).on('click', '#userStatusCancel', function (e) {
    e.preventDefault();
    __closeUserStatusDialog();
});

$(document).on('click', '#userStatusConfirm', function (e) {
    e.preventDefault();
    __confirmUserStatus();
});

$(document).on('click', '#userStatusOverlay', function (e) {
    if (e.target === this) __closeUserStatusDialog();
});
