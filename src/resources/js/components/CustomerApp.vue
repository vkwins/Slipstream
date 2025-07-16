<template>
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-300 mb-6">
                    Demo Menu
                </h2>
                <nav class="space-y-2">
                    <a
                        href="/customers"
                        class="block px-3 py-2 rounded-md text-sm font-medium bg-gray-900 text-white"
                    >
                        Customers
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <nav class="bg-white border-b border-gray-200">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="flex-shrink-0 flex items-center">
                                <h1 class="text-xl font-semibold text-gray-900">
                                    Slipstream
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1 py-6">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-bold text-gray-900">
                                Customers
                            </h2>
                            <button
                                @click="openCreateModal"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out"
                            >
                                Create Customer
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="max-w-md">
                            <label
                                for="search"
                                class="block text-sm font-medium text-gray-700 mb-2"
                                >Search Customers</label
                            >
                            <div class="flex space-x-2">
                                <div class="relative flex-1">
                                    <input
                                        type="text"
                                        id="search"
                                        v-model="searchQuery"
                                        @keyup.enter="applySearch"
                                        placeholder="Search by name, reference, or description..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                    <div
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <button
                                    @click="applySearch"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                >
                                    Apply
                                </button>
                                <button
                                    @click="clearSearch"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                                >
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Customers Table -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Reference
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Category
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Start Date
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Contacts
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="customer in customers"
                                        :key="customer.id"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ customer.name }}
                                            </div>
                                            <div
                                                v-if="customer.description"
                                                class="text-sm text-gray-500 truncate max-w-xs"
                                            >
                                                {{ customer.description }}
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ customer.reference }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800':
                                                        customer.category
                                                            .name === 'Gold',
                                                    'bg-gray-100 text-gray-800':
                                                        customer.category
                                                            .name === 'Silver',
                                                    'bg-orange-100 text-orange-800':
                                                        customer.category
                                                            .name === 'Bronze',
                                                }"
                                            >
                                                {{ customer.category.name }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{
                                                formatDate(customer.start_date)
                                            }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {{ customer.contacts.length }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <button
                                                @click="openEditModal(customer)"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="confirmDelete(customer)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Customer Modal -->
        <CustomerModal
            v-if="showModal"
            :customer="currentCustomer"
            :categories="categories"
            :is-editing="isEditing"
            @close="closeModal"
            @saved="onCustomerSaved"
        />

        <!-- Contact Modal -->
        <ContactModal
            v-if="showContactModal"
            :contact="currentContact"
            :is-editing="isEditingContact"
            @close="closeContactModal"
            @saved="onContactSaved"
        />

        <!-- Delete Confirmation Modal -->
        <DeleteModal
            v-if="showDeleteModal"
            :title="'Delete Customer'"
            :message="'Are you sure you want to delete this customer? This action cannot be undone.'"
            @confirm="deleteCustomer"
            @close="closeDeleteModal"
        />
    </div>
</template>

<script>
import CustomerModal from "./CustomerModal.vue";
import ContactModal from "./ContactModal.vue";
import DeleteModal from "./DeleteModal.vue";

export default {
    name: "CustomerApp",
    components: {
        CustomerModal,
        ContactModal,
        DeleteModal,
    },
    data() {
        return {
            customers: [],
            categories: [],
            searchQuery: "",
            showModal: false,
            showContactModal: false,
            showDeleteModal: false,
            isEditing: false,
            isEditingContact: false,
            currentCustomer: null,
            currentContact: null,
            customerToDelete: null,
        };
    },
    async mounted() {
        await this.loadCustomers();
        await this.loadCategories();
        this.searchQuery =
            new URLSearchParams(window.location.search).get("search") || "";
    },
    methods: {
        async loadCustomers() {
            try {
                const response = await fetch("/api/customers");
                const data = await response.json();
                this.customers = data.customers.data || [];
            } catch (error) {
                console.error("Error loading customers:", error);
            }
        },
        async loadCategories() {
            try {
                const response = await fetch("/api/customers");
                const data = await response.json();
                this.categories = data.categories || [];
            } catch (error) {
                console.error("Error loading categories:", error);
            }
        },
        applySearch() {
            const searchParam = this.searchQuery
                ? `?search=${encodeURIComponent(this.searchQuery)}`
                : "";
            window.location.href = `/customers${searchParam}`;
        },
        clearSearch() {
            this.searchQuery = "";
            window.location.href = "/customers";
        },
        openCreateModal() {
            this.isEditing = false;
            this.currentCustomer = null;
            this.showModal = true;
        },
        openEditModal(customer) {
            this.isEditing = true;
            this.currentCustomer = customer;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.currentCustomer = null;
        },
        async onCustomerSaved() {
            this.closeModal();
            await this.loadCustomers();
        },
        openContactModal() {
            this.isEditingContact = false;
            this.currentContact = null;
            this.showContactModal = true;
        },
        closeContactModal() {
            this.showContactModal = false;
            this.currentContact = null;
        },
        async onContactSaved() {
            this.closeContactModal();
            await this.loadCustomers();
        },
        confirmDelete(customer) {
            this.customerToDelete = customer;
            this.showDeleteModal = true;
        },
        async deleteCustomer() {
            try {
                const response = await fetch(
                    `/api/customers/${this.customerToDelete.id}`,
                    {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    }
                );

                if (response.ok) {
                    await this.loadCustomers();
                    this.closeDeleteModal();
                }
            } catch (error) {
                console.error("Error deleting customer:", error);
            }
        },
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.customerToDelete = null;
        },
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
            });
        },
    },
};
</script>
