function toggleSubmenu(event) {
    event.preventDefault(); // デフォルトのリンク動作を防ぐ
    const submenu = event.currentTarget.nextElementSibling;
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
}

document.addEventListener('click', function (event) {
    const isClickInsideMenu = event.target.closest('li');
    if (!isClickInsideMenu) {
        const submenus = document.querySelectorAll('.sample01 ul li ul');
        submenus.forEach(submenu => {
            submenu.style.display = 'none';
        });
    }
});


//index.php
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// モーダル外をクリックして閉じる
window.onclick = function (event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
};


document.addEventListener("DOMContentLoaded", function() {
    const chatMessages = document.getElementById("scroll-inner");
    if (chatMessages) {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  });