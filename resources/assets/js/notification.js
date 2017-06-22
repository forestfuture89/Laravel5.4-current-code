/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Echo = require('laravel-echo');

window.Pusher = require('pusher-js');


/**
 * Custom scripts for notifications on the head navbar
 */

var csrf_token = $("meta[name='csrf-token']").attr("content"),
    pusher_key = $("meta[name='pusher-key']").attr("content"),
    pusher_cluster = $("meta[name='pusher-cluster']").attr("content");

var echo = new window.Echo({
    broadcaster: 'pusher',
    key: pusher_key,
    csrfToken: csrf_token,
    cluster: pusher_cluster,
    encrypted: true
});

echo.private('notification')
    .listen('NewNotificationArrive', function(e) {
        addNewNotification(e);
    });

function addNewNotification (new_notification) {
    var notification_block = document.getElementById('notification_block');

    // check if notification navbar exists on the current page
    if (notification_block) {
        var user_id = parseInt(notification_block.getAttribute("data-id"));

        // check if the current user is a recipient of this notification
        if (new_notification.recipient_id == user_id) {
            var first_item = notification_block.children[0];
            var notification_id = parseInt(first_item.getAttribute('data-id'));

            // check if this new notification already exists on the navbar
            if (new_notification.id != notification_id) {
                var notification_badge = document.getElementById('notification_badge');
                var new_item = document.createElement('div');
                var mark_read_link = document.getElementById('mark_read');

                // increase the number of notifications on its badge
                notification_badge.innerHTML = parseInt(notification_badge.innerHTML) + 1;
                notification_badge.classList.remove('hide');
                notification_badge.classList.add('show');

                // add the new notification item into its block
                new_item.className = 'notification-item unread';
                new_item.setAttribute('data-id', new_notification.id);
                new_item.innerHTML = '<a href="#">' + new_notification.content + '</a>' +
                    '<span class="freshness">' + new_notification.passedTime + '</span>';
                first_item.parentNode.insertBefore(new_item, first_item);

                // activate the 'mark all as read' link
                mark_read_link.classList.remove('mark-disable');
            }

            // remove if the original first item is NOT notification
            if (notification_id == 0) {
                first_item.remove();
            }
        }
    }
}

$('#mark_read, #mark_read_btn').click(function(e) {
    e.stopPropagation();

    $.ajax({
        type: "POST",
        url: $(this).attr('data-url'),
        data: { _token: csrf_token },
        success: function() {
            // Remove the 'unread' class from all notification items
            $("#notification_block > .notification-item").removeClass('unread');
            $("#notificationTable tr > td").removeClass('unread');

            // Hide the alert badge and assign the default value(0) on its element
            $("#notification_badge").removeClass('show');
            $("#notification_badge").addClass('hide');
            $("#notification_badge").text(0);

            // Disable the 'mark all as read' link
            $('#mark_read').addClass('mark-disable');
            $('#mark_read_btn').addClass('mark-disable');

            // console.log("Mark all read!");
            return false;
        }
    });
});
