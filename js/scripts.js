//! Переменные
const adminpaneltext = document.querySelector(".adminpaneltext");
const sidebar = document.querySelector(".side-panel");
const sidebarcloseBtn = document.querySelector(".sidebar-toggle");
const adminListOpen = document.querySelector(".window_two-button");
const adminList = document.querySelector(".admin-list ul");
const promoListOpen = document.querySelector(".window_three-button");
const promoList = document.querySelector(".window_three_cont");

const server_stats = document.getElementById("server_stats");
const server_admins = document.getElementById("server_admins");

const dash_server_stats = document.getElementById("dash_server_stats");
const dash_server_admins = document.getElementById("dash_server_admins");

//! Обработчик кнопок по class
adminpaneltext.addEventListener("click", () => {
    window.open('https://t.me/spritestudio', '_blank');
});
sidebarcloseBtn.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    sidebarcloseBtn.classList.toggle("rotated");
});
adminListOpen.addEventListener("click", () => {
    adminList.classList.toggle("collapsed");
    adminListOpen.classList.toggle("rotated");
});
promoListOpen.addEventListener("click", () => {
    promoList.classList.toggle("collapsed");
    promoListOpen.classList.toggle("rotated");
});

//! Обнуление значений
dash_server_admins.style.display = 'none';

//! Обработчик кнопок по ID
document.getElementById("server_stats").onclick = function() {
    if (server_stats.className != 'active') {
        // Показ окна dashboard
        server_stats.classList.add("active"); // сделать кнопку активной
        dash_server_stats.style.display = 'flex';

        // Очистка окна dashboard
        server_admins.classList.remove("active");
        dash_server_admins.style.display = 'none';
    }

    if (window.innerWidth <= 600) {
        sidebar.classList.toggle("collapsed");
        sidebarcloseBtn.classList.toggle("rotated");
    }
}
document.getElementById("server_admins").onclick = function() {
    if (server_admins.className != 'active') {
        // Показ окна dashboard
        server_admins.classList.add("active"); // сделать кнопку активной
        dash_server_admins.style.display = 'flex';

        // Очистка окна dashboard
        server_stats.classList.remove("active");
        dash_server_stats.style.display = 'none';
    }

    if (window.innerWidth <= 600) {
        sidebar.classList.toggle("collapsed");
        sidebarcloseBtn.classList.toggle("rotated");
    }
}
document.getElementById("sign_out").onclick = function() {
    window.location.href = "logout"; 
    
    // Очистка окна dashboard
    dash_server_stats.style.display = 'none';
    dash_server_admins.style.display = 'none';

    if (window.innerWidth <= 600) {
        sidebar.classList.toggle("collapsed");
        sidebarcloseBtn.classList.toggle("rotated");
    }
}