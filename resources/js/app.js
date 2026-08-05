import { createIcons, icons } from "lucide";
import Swal from "sweetalert2";

const renderIcons = () => createIcons({ icons });

document.addEventListener("DOMContentLoaded", () => {
    renderIcons();

    const flash = window.flashMessage;

    if (flash?.message) {
        Swal.fire({
            icon: flash.type,
            title: flash.type === "success" ? "Berhasil" : "Terjadi kesalahan",
            text: flash.message,
            confirmButtonColor: "#6d1a1a",
        });
    }
});

window.Swal = Swal;
window.renderIcons = renderIcons;
