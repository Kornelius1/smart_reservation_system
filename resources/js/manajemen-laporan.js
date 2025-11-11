document.addEventListener("DOMContentLoaded", function () {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const filterForm = document.getElementById("filterForm");

    // Fungsi untuk format tanggal ke YYYY-MM-DD
    const formatDate = (date) => {
        const d = new Date(date);
        const month = String(d.getMonth() + 1).padStart(2, "0");
        const day = String(d.getDate()).padStart(2, "0");
        const year = d.getFullYear();
        return [year, month, day].join("-");
    };

    // Tombol "Hari Ini"
    document
        .getElementById("filterToday")
        .addEventListener("click", function () {
            const today = formatDate(new Date());
            startDateInput.value = today;
            endDateInput.value = today;
            filterForm.submit();
        });

    // Tombol "Minggu Ini"
    document
        .getElementById("filterThisWeek")
        .addEventListener("click", function () {
            const today = new Date();
            const firstDayOfWeek = new Date(
                today.setDate(today.getDate() - today.getDay())
            );

            startDateInput.value = formatDate(firstDayOfWeek);
            endDateInput.value = formatDate(new Date());
            filterForm.submit();
        });
});
