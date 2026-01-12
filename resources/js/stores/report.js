import { defineStore } from "pinia";
import api from "../api/index";

export const useSalesReportStore = defineStore("salesReport", {
    state: () => ({
        items: [],
        salesData: { total_revenue: 0, total_transactions: 0 },
        paginationMeta: null,
        filters: {
            start_date: "",
            end_date: "",
            search: "", // Tambahkan state search
        },
        isLoading: false,
        hasFetched: false,
    }),

    actions: {
        async fetchSalesReport(url = "/reports/sales") {
            this.isLoading = true;
            try {
                // Gabungkan filter dengan URL pagination jika ada
                const response = await api.get(url, {
                    params: this.filters,
                });

                const res = response.data.data;
                this.items = res.items_sold?.data || [];
                this.paginationMeta = res.items_sold;
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
