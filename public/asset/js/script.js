
// ========================= DURASI =======================================
document.addEventListener('DOMContentLoaded', (event) => {
    const durationSelect = document.getElementById('duration');
    const startInput = document.getElementById('start');
    const endInput = document.getElementById('end');

    durationSelect.addEventListener('change', function() {
        const duration = parseInt(this.value);

        if (isNaN(duration)) return;

        // Ambil waktu sekarang dalam zona waktu lokal browser
        const now = new Date();
        const timezoneOffset = now.getTimezoneOffset() * 20; // Offset dalam milidetik
        const localDate = new Date(now.getTime() - timezoneOffset); // Waktu lokal

        // Format tanggal untuk input start
        const year = localDate.getFullYear();
        const month = String(localDate.getMonth() + 1).padStart(2, '0');
        const day = String(localDate.getDate()).padStart(2, '0');
        const hours = String(localDate.getHours()).padStart(2, '0');
        const minutes = String(localDate.getMinutes()).padStart(2, '0');
        const startIsoString = `${year}-${month}-${day}T${hours}:${minutes}`;

        // Set nilai input untuk waktu mulai
        startInput.value = startIsoString;

        // Hitung waktu selesai berdasarkan durasi, dalam zona waktu lokal browser
        const endLocalDate = new Date(localDate.getTime() + duration * 60 * 60 * 1000);
        const endYear = endLocalDate.getFullYear();
        const endMonth = String(endLocalDate.getMonth() + 1).padStart(2, '0');
        const endDay = String(endLocalDate.getDate()).padStart(2, '0');
        const endHours = String(endLocalDate.getHours()).padStart(2, '0');
        const endMinutes = String(endLocalDate.getMinutes()).padStart(2, '0');
        const endIsoString = `${endYear}-${endMonth}-${endDay}T${endHours}:${endMinutes}`;

        // Set nilai input untuk waktu selesai
        endInput.value = endIsoString;
    });
});
