$(document).ready(function () {
    const redirectForm = document.getElementById('redirectForm');
    if (redirectForm) {
        redirectForm.addEventListener('click', () => {
            window.location.href = '../logger_tips/activity_logger.html';
        });
    }
});
