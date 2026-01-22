<?php
// notif_modal.php

include '../Landing Repository/Connection.php';

$user_id = $_SESSION['User_ID'] ?? 0;

// Get notifications
$notifications = [];
$notification_query = $con->prepare("
    SELECT n.*, m.type as medicine_type 
    FROM notifications n 
    LEFT JOIN medicine m ON n.medicine_id = m.med_id 
    WHERE (n.user_id = ? OR n.user_id IS NULL) 
    ORDER BY n.created_at DESC 
    LIMIT 50
");
$notification_query->bind_param("i", $user_id);
$notification_query->execute();
$result = $notification_query->get_result();

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Mark all as read when modal is opened
if (isset($_GET['mark_read']) && $_GET['mark_read'] == 'all') {
    $update_query = $con->prepare("UPDATE notifications SET is_read = 1 WHERE (user_id = ? OR user_id IS NULL)");
    $update_query->bind_param("i", $user_id);
    $update_query->execute();
}

// Get notification counts
$total_count = count($notifications);
$unread_count = 0;
foreach ($notifications as $notif) {
    if (!$notif['is_read']) $unread_count++;
}
?>
<div id="notificationModal" class="modal hidden fixed inset-0 items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm" style="z-index: 1000px;">
    <div class="modal-content bg-white rounded-2xl p-6 shadow-2xl w-full max-w-2xl max-h-[80vh] overflow-y-auto">
        <!-- Header -->
        <div class="modal-header flex justify-between items-center border-b-2 border-blue-200 pb-4 mb-6">
            <div class="flex items-center gap-2">
                <i class='bx bx-bell text-blue-600 text-2xl'></i>
                <h2 class="text-lg font-bold text-gray-800">Notifications</h2>
                <?php if ($unread_count > 0): ?>
                <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-full"><?= $unread_count ?> new</span>
                <?php endif; ?>
            </div>
            <div class="flex gap-2">
                <button onclick="markAllNotificationsRead()" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm hover:bg-blue-200">
                    Mark all read
                </button>
                <span class="close-btn cursor-pointer text-xl font-bold text-gray-400 hover:text-gray-600 transition" onclick="closeNotificationModal()">&times;</span>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="notifications-list space-y-3">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class='bx bx-bell-off text-4xl mb-2'></i>
                    <p>No notifications yet.</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): 
                    $type_icon = 'bx-info-circle';
                    $type_color = 'bg-blue-100 text-blue-700';
                    
                    switch($notification['type']) {
                        case 'low_stock':
                            $type_icon = 'bx-error';
                            $type_color = 'bg-yellow-100 text-yellow-700';
                            break;
                        case 'near_expiry':
                            $type_icon = 'bx-time-five';
                            $type_color = 'bg-orange-100 text-orange-700';
                            break;
                        case 'expired':
                            $type_icon = 'bx-x-circle';
                            $type_color = 'bg-red-100 text-red-700';
                            break;
                        case 'add':
                            $type_icon = 'bx-plus-circle';
                            $type_color = 'bg-green-100 text-green-700';
                            break;
                        case 'update':
                            $type_icon = 'bx-edit';
                            $type_color = 'bg-blue-100 text-blue-700';
                            break;
                        case 'dispose':
                            $type_icon = 'bx-trash';
                            $type_color = 'bg-purple-100 text-purple-700';
                            break;
                        case 'deduct':
                            $type_icon = 'bx-minus-circle';
                            $type_color = 'bg-red-100 text-red-700';
                            break;
                    }
                    
                    $is_read = $notification['is_read'] ? 'opacity-75' : 'bg-blue-50';
                ?>
                <div class="notification-item p-4 rounded-lg border <?= $is_read ?>" id="notification-<?= $notification['id'] ?>">
                    <div class="flex items-start gap-3">
                        <div class="notification-icon <?= $type_color ?> p-2 rounded-full">
                            <i class='bx <?= $type_icon ?> text-lg'></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold text-gray-800">
                                        <?= htmlspecialchars($notification['medicine_name']) ?>
                                        <?php if ($notification['medicine_id']): ?>
                                        <span class="text-xs text-gray-500">(ID: <?= $notification['medicine_id'] ?>)</span>
                                        <?php endif; ?>
                                    </h4>
                                    <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($notification['message']) ?></p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-500"><?= date('M d, h:i A', strtotime($notification['created_at'])) ?></span>
                                    <?php if (!$notification['is_read']): ?>
                                    <button onclick="markNotificationRead(<?= $notification['id'] ?>)" class="block mt-1 text-xs text-blue-600 hover:text-blue-800">
                                        Mark read
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-2 flex gap-2">
                                <span class="text-xs px-2 py-1 bg-gray-100 rounded"><?= ucfirst(str_replace('_', ' ', $notification['type'])) ?></span>
                                <?php if ($notification['medicine_type']): ?>
                                <span class="text-xs px-2 py-1 bg-gray-100 rounded"><?= ucfirst($notification['medicine_type']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="modal-footer flex justify-between pt-4 border-t border-gray-200 mt-6">
            <div class="text-sm text-gray-600">
                Showing <?= $total_count ?> notification<?= $total_count != 1 ? 's' : '' ?>
                <?php if ($unread_count > 0): ?>
                · <span class="text-blue-600"><?= $unread_count ?> unread</span>
                <?php endif; ?>
            </div>
            <div>
                <button onclick="closeNotificationModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                    <i class='bx bx-x'></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function closeNotificationModal() {
    document.getElementById('notificationModal').style.display = 'none';
}

function markNotificationRead(notificationId) {
    fetch(`?action=mark_notification_read&id=${notificationId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const notificationItem = document.getElementById(`notification-${notificationId}`);
                notificationItem.classList.remove('bg-blue-50');
                notificationItem.classList.add('opacity-75');
                
                // Update notification count
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    const currentCount = parseInt(badge.textContent);
                    if (currentCount > 1) {
                        badge.textContent = currentCount - 1;
                    } else {
                        badge.remove();
                    }
                }
            }
        });
}

function markAllNotificationsRead() {
    fetch('?mark_read=all')
        .then(() => {
            // Update all notifications UI
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('bg-blue-50');
                item.classList.add('opacity-75');
            });
            
            // Remove all mark read buttons
            document.querySelectorAll('[onclick^="markNotificationRead"]').forEach(btn => {
                btn.remove();
            });
            
            // Remove notification badge
            const badge = document.querySelector('.notification-badge');
            if (badge) badge.remove();
            
            showToast('All notifications marked as read', 'success');
        });
}

// Show notification modal when called from main page
function showNotificationModal() {
    document.getElementById('notificationModal').style.display = 'flex';
}
</script>