/**
 * Syarihub Utility Formatter
 * Menggunakan standar Intl untuk performa dan akurasi locale Indonesia
 */

export const formatCurrency = (value) => {
    // 1. Antisipasi jika nilai null, undefined, atau kosong
    if (value === null || value === undefined || value === "") return "Rp 0";

    // 2. Konversi ke float jika input berupa string (seperti dari API: "3646536.00")
    const numberValue = typeof value === "string" ? parseFloat(value) : value;

    // 3. Cek jika hasil konversi bukan angka yang valid
    if (isNaN(numberValue)) return "Rp 0";

    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(numberValue);
};

export const formatNumber = (value) => {
    if (value === null || value === undefined) return "0";
    const numberValue = typeof value === "string" ? parseInt(value) : value;
    if (isNaN(numberValue)) return "0";

    return new Intl.NumberFormat("id-ID").format(numberValue);
};

export const formatDateFull = (dateString) => {
    if (!dateString) return "-";
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString("id-ID", options);
};
