import { defineStore } from "pinia";
import api from "../api/index";

export const useSalesReportStore = defineStore("salesReport", {
    state: () => ({
        items: [],
        salesData: { total_revenue: 0, total_transactions: 0 },
        paginationMeta: null, // Tempat menyimpan meta pagination
        filters: { start_date: "", end_date: "" },
        isLoading: false,
        hasFetched: false,
    }),

    actions: {
        async fetchSalesReport(url = "/reports/sales") {
            this.isLoading = true;
            try {
                // Jika url adalah URL lengkap dari Laravel (misal: ?page=2), axios akan menanganinya
                const response = await api.get(url, {
                    params: url.includes("?") ? {} : this.filters, // Jangan tumpuk params jika sudah ada di URL
                });

                const res = response.data.data;

                // 1. Ambil data array-nya
                const rawItems = res.items_sold?.data || res.items_sold || [];
                this.items = Array.isArray(rawItems)
                    ? rawItems.filter((i) => i && i.product_id)
                    : [];

                // 2. Simpan meta pagination untuk komponen Pagination
                this.paginationMeta = res.items_sold;

                // 3. Simpan summary
                this.salesData.total_revenue = res.total_revenue || 0;
                this.salesData.total_transactions = res.total_transactions || 0;

                this.hasFetched = true;
            } catch (error) {
                console.error("Gagal load report:", error);
            } finally {
                this.isLoading = false;
            }
        },
    },
});
