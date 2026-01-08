/**
 * Format angka ke Rupiah
 */
export const formatCurrency = (value) => {
    if (!value) return "Rp 0";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
};

/**
 * Format tanggal ke format lokal Indonesia
 */
export const formatDate = (dateString, includeTime = false) => {
    if (!dateString) return "-";
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    if (includeTime) {
        options.hour = "2-digit";
        options.minute = "2-digit";
    }
    return new Date(dateString).toLocaleDateString("id-ID", options);
};

/**
 * Capitalize first letter
 */
export const capitalize = (str) => {
    if (!str) return "";
    return str.charAt(0).toUpperCase() + str.slice(1);
};
