const routes = [
    {
        // Bungkus login di dalam AuthLayout
        path: "/auth",
        component: () => import("@/layouts/AuthLayout.vue"),
        meta: { guestOnly: true },
        children: [
            {
                path: "/login",
                name: "login",
                component: () => import("@/modules/auth/pages/LoginPage.vue"),
            },
        ],
    },
    {
        path: "/",
        component: () => import("@/layouts/MainLayout.vue"),
        meta: { requiresAuth: true },
        children: [
            {
                path: "",
                name: "dashboard",
                component: () =>
                    import("@/modules/dashboard/pages/DashboardPage.vue"),
            },
            // Rute Modul Produk
            {
                path: "products",
                name: "products.index",
                component: () =>
                    import("@/modules/product/pages/ProductIndex.vue"),
            },
            {
                path: "/products/:id",
                name: "products.show",
                component: () =>
                    import("@/modules/product/pages/DetailProduct.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/categories",
                name: "categories",
                component: () =>
                    import("@/modules/category/pages/CategoryIndex.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/users",
                name: "User Management",
                component: () =>
                    import("@/modules/userManagement/pages/UserIndex.vue"),
                meta: { requiresAuth: true },
            },
            {
                path: "/reports/sales",
                name: "Sales Report",
                component: () =>
                    import("@/modules/report/pages/SalesReport.vue"),
                meta: { requiresAuth: true },
            },

            // Contoh rute lainnya tetap tersimpan sebagai komentar sesuai kode Anda...
            // {
            //     path: "inventory/bulk-restock",
            //     name: "inventory.restock",
            //     component: () =>
            //         import("@/modules/inventory/pages/BulkRestockPage.vue"),
            //     meta: { roles: ["owner", "gudang"] },
            // },
        ],
    },
    // Fallback 404 (Opsional: aktifkan jika komponen sudah ada)
    // {
    //     path: "/:pathMatch(.*)*",
    //     name: "not-found",
    //     component: () => import("@/components/NotFound.vue"),
    // },
];

export default routes;
