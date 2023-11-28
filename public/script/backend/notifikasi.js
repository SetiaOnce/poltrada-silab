jQuery(document).ready(function() {
    _loadNotification();
});
// handle notification staff all
const _loadNotification = () => {
    $.ajax({
        url: BASE_URL+ "/app_admin/ajax/load_notification",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
          $('.notif-jadwal-praktek').html(data.jadwal_praktek);
        }
    });
}