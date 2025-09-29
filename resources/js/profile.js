// ! {{-- punya profile --}}
document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("editProfileBtn");
    const panel = document.getElementById("editProfilePanel");
    const overlay = document.getElementById("overlay");

    // Tampilkan panel
    editBtn?.addEventListener("click", () => {
        panel.classList.remove("translate-x-full");
        overlay.classList.remove("hidden");
    });

    // Sembunyikan saat klik overlay
    overlay?.addEventListener("click", () => {
        panel.classList.add("translate-x-full");
        overlay.classList.add("hidden");
    });

    // Tutup panel saat tombol "Batal" ditekan
    const cancelEditBtn = document.getElementById("cancelEditBtn");
    cancelEditBtn?.addEventListener("click", () => {
        panel.classList.add("translate-x-full");
        overlay.classList.add("hidden");
    });
});

const burgerBtn = document.getElementById("burgerBtn");
const mobileSidebar = document.getElementById("mobileSidebar");
const closeSidebar = document.getElementById("closeSidebar");

burgerBtn?.addEventListener("click", () => {
    mobileSidebar.classList.remove("-translate-x-full");
});

closeSidebar?.addEventListener("click", () => {
    mobileSidebar.classList.add("-translate-x-full");
});

// ! {{-- punya address --}}
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("toggleAddressFormBtn");
    const formSection = document.getElementById("addressFormSection");

    toggleBtn.addEventListener("click", function () {
        formSection.classList.toggle("hidden");
        // Optional: scroll ke form kalau di tampilkan
        if (!formSection.classList.contains("hidden")) {
            formSection.scrollIntoView({
                behavior: "smooth",
            });
        }
    });
});

// radio
document.addEventListener("DOMContentLoaded", function () {
    const radios = document.querySelectorAll(
        "input[type=radio][name=default_address]"
    );

    radios.forEach((radio) => {
        radio.addEventListener("change", function () {
            // reset semua radio
            radios.forEach((r) => (r.checked = false));
            // aktifkan yang diklik
            this.checked = true;
            // submit form
            this.closest("form").submit();
        });
    });
});

// ! edit address
document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".editAddressBtn");
    const panel = document.getElementById("editAddressPanel");
    const overlay = document.getElementById("addressOverlay");
    const form = document.getElementById("editAddressForm");

    editButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const address = JSON.parse(button.dataset.address);
            form.action = `/profile/address/${address.id}`;

            form.fullname.value = address.fullname ?? "";
            form.company.value = address.company ?? "";
            form.address1.value = address.address1 ?? "";
            form.address2.value = address.address2 ?? "";
            form.country.value = address.country ?? "";
            form.city.value = address.city ?? "";
            form.postal.value = address.postal ?? "";
            form.phone.value = address.phone ?? "";

            panel.classList.remove("translate-x-full");
            overlay.classList.remove("hidden");
        });
    });

    overlay.addEventListener("click", () => {
        panel.classList.add("translate-x-full");
        overlay.classList.add("hidden");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("editAddressBtn");
    const panel = document.getElementById("editAddressPanel");
    const overlay = document.getElementById("addressOverlay"); // disesuaikan
    const cancelEditBtn = document.getElementById("cancelEditBtn");

    editBtn?.addEventListener("click", () => {
        panel.classList.remove("translate-x-full");
        overlay.classList.remove("hidden");
    });

    function closePanel() {
        panel.classList.add("translate-x-full");
        overlay.classList.add("hidden");
    }

    overlay?.addEventListener("click", closePanel);
    cancelEditBtn?.addEventListener("click", closePanel);
});

// ! {{-- confirm logout --}}
const logoutForm = document.getElementById("logoutForm");
logoutForm.addEventListener("submit", function (e) {
    if (!confirm("Yakin ingin logout?")) {
        e.preventDefault();
    }
});

// ! {{-- phone number format --}}
const phoneInput = document.getElementById("phoneInput");

phoneInput.addEventListener("input", function (e) {
    let raw = e.target.value.replace(/\D/g, ""); // Hapus semua non-digit

    // Tambahkan awalan '62' jika belum ada
    if (!raw.startsWith("62")) {
        raw = "62" + raw;
    }

    // Batas maksimal 13 digit setelah 62
    raw = raw.slice(0, 13);

    // Format jadi +62-xxxx-xxxx-xxxx
    let formatted = "+62";
    if (raw.length > 2) formatted += "-" + raw.slice(2, 6);
    if (raw.length > 6) formatted += "-" + raw.slice(6, 10);
    if (raw.length > 10) formatted += "-" + raw.slice(10);

    e.target.value = formatted;
});
