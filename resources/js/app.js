import { createIcons, icons } from "lucide";
import Chart from "chart.js/auto";
import Swal from "sweetalert2";

const renderIcons = () => createIcons({ icons });

document.addEventListener("DOMContentLoaded", () => {
    renderIcons();

    const schoolPointsChart = document.getElementById("school-points-chart");

    if (schoolPointsChart instanceof HTMLCanvasElement) {
        const chartData = JSON.parse(schoolPointsChart.dataset.chart ?? "[]");

        new Chart(schoolPointsChart, {
            type: "bar",
            data: {
                labels: chartData.map((item) => item.label),
                datasets: [
                    {
                        label: "Pelanggaran",
                        data: chartData.map((item) => item.violations),
                        backgroundColor: "rgba(225, 29, 72, 0.78)",
                        borderRadius: 6,
                        pointStyle: "circle",
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: "Apresiasi",
                        data: chartData.map((item) => item.appreciations),
                        backgroundColor: "rgba(16, 185, 129, 0.78)",
                        borderRadius: 6,
                        pointStyle: "circle",
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                plugins: {
                    legend: {
                        align: "end",
                        labels: {
                            boxWidth: 10,
                            boxHeight: 10,
                            color: "#475569",
                            font: { size: 11, weight: 600 },
                            usePointStyle: true,
                        },
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: "#64748b", font: { size: 11 } },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: "rgba(226, 232, 240, 0.75)" },
                        ticks: { color: "#64748b", precision: 0, font: { size: 11 } },
                    },
                },
            },
        });
    }

    document.addEventListener("submit", async (event) => {
        const form = event.target;
        const submitter = event.submitter;
        const confirmation = submitter?.dataset.confirm ?? form.dataset.confirm;

        if (!(form instanceof HTMLFormElement) || !confirmation || form.dataset.confirmed === "true") {
            return;
        }

        event.preventDefault();

        const result = await Swal.fire({
            icon: submitter?.dataset.confirmIcon ?? form.dataset.confirmIcon ?? "warning",
            title: submitter?.dataset.confirmTitle ?? form.dataset.confirmTitle ?? "Konfirmasi tindakan",
            text: confirmation,
            showCancelButton: true,
            confirmButtonColor: "#6d1a1a",
            cancelButtonColor: "#64748b",
            confirmButtonText: submitter?.dataset.confirmButton ?? form.dataset.confirmButton ?? "Ya, lanjutkan",
            cancelButtonText: "Batal",
            reverseButtons: true,
            focusCancel: true,
        });

        if (result.isConfirmed) {
            form.dataset.confirmed = "true";
            form.requestSubmit(submitter ?? undefined);
        }
    });

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
