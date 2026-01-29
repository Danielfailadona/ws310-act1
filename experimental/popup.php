<div id="custom-popup-overlay" class="popup-overlay">
    <div id="custom-popup-box" class="popup-box">
        <div id="popup-icon" class="popup-icon"></div>
        <h2 id="popup-title">Title</h2>
        <p id="popup-message">Message goes here.</p>
        <button onclick="closePopup()" class="popup-close-btn">Okay</button>
    </div>
</div>

<style>
    .popup-overlay {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(3px);
    }

    .popup-box {
        background: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        animation: popIn 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    }

    @keyframes popIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Dynamic Colors based on "Type" */
    .popup-box.success { border-top: 8px solid #38a169; }
    .popup-box.error { border-top: 8px solid #e3342f; }
    .popup-box.warning { border-top: 8px solid #ffc107; }

    .popup-close-btn {
        margin-top: 20px;
        padding: 10px 25px;
        background: #2d68da;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
</style>

<script>
    function showPopup(type, title, message) {
        const overlay = document.getElementById('custom-popup-overlay');
        const box = document.getElementById('custom-popup-box');
        
        // 1. Set the Content
        document.getElementById('popup-title').innerText = title;
        document.getElementById('popup-message').innerText = message;
        
        // 2. Set the Style (Success, Error, etc.)
        box.className = 'popup-box ' + type;
        
        // 3. Show it
        overlay.style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('custom-popup-overlay').style.display = 'none';
    }









// How to use
    // <?php
    // if ($login_failed) {
    //     echo "<script>showPopup('error', 'Login Failed', 'Check your username and password.');</script>";
    // }
    // ?>
</script>



