<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Account Verified</title>
</head>
<body>
<script>
    (function () {
        try {
            // Talk to the original staff window
            if (window.opener && !window.opener.closed) {
                // Make sure Livewire exists in the opener
                if (window.opener.Livewire && typeof window.opener.Livewire.dispatch === 'function') {
                    // 🔹 This triggers your #[On('encode-account-verified')] listener
                    window.opener.Livewire.dispatch('encode-account-verified');
                }
            }
        } catch (e) {
            console.error('Error notifying opener window:', e);
        }

        // Close this OTP tab/window
        window.close();
    })();
</script>
</body>
</html>
