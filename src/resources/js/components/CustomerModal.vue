<template>
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"
            ></div>

            <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen"
                aria-hidden="true"
                >&#8203;</span
            >

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
            >
                <form @submit.prevent="saveCustomer">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mt-3 text-center sm:mt-0 sm:text-left w-full"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                    id="modal-title"
                                >
                                    {{
                                        isEditing
                                            ? "Edit Customer"
                                            : "Create Customer"
                                    }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <!-- Customer Form Fields -->
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                    >
                                        <div>
                                            <label
                                                for="name"
                                                class="block text-sm font-medium text-gray-700"
                                                >Name</label
                                            >
                                            <input
                                                type="text"
                                                id="name"
                                                v-model="form.name"
                                                required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                for="reference"
                                                class="block text-sm font-medium text-gray-700"
                                                >Reference</label
                                            >
                                            <input
                                                type="text"
                                                id="reference"
                                                v-model="form.reference"
                                                required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                for="category"
                                                class="block text-sm font-medium text-gray-700"
                                                >Category</label
                                            >
                                            <select
                                                id="category"
                                                v-model="
                                                    form.customer_category_id
                                                "
                                                required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            >
                                                <option value="">
                                                    Select Category
                                                </option>
                                                <option
                                                    v-for="category in categories"
                                                    :key="category.id"
                                                    :value="category.id"
                                                >
                                                    {{ category.name }}
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <label
                                                for="start_date"
                                                class="block text-sm font-medium text-gray-700"
                                                >Start Date</label
                                            >
                                            <input
                                                type="date"
                                                id="start_date"
                                                v-model="form.start_date"
                                                required
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            for="description"
                                            class="block text-sm font-medium text-gray-700"
                                            >Description</label
                                        >
                                        <textarea
                                            id="description"
                                            v-model="form.description"
                                            rows="3"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        ></textarea>
                                    </div>

                                    <!-- Contacts Section -->
                                    <div
                                        v-if="isEditing && customer"
                                        class="border-t pt-4"
                                    >
                                        <div
                                            class="flex justify-between items-center mb-4"
                                        >
                                            <h4
                                                class="text-md font-medium text-gray-900"
                                            >
                                                Contacts
                                            </h4>
                                            <button
                                                type="button"
                                                @click="openContactModal"
                                                class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1 px-3 rounded transition duration-150 ease-in-out"
                                            >
                                                Add Contact
                                            </button>
                                        </div>

                                        <!-- Contacts List -->
                                        <div class="space-y-2">
                                            <div
                                                v-for="contact in customer.contacts"
                                                :key="contact.id"
                                                class="flex justify-between items-center p-3 bg-gray-50 rounded-lg"
                                            >
                                                <span
                                                    >{{ contact.first_name }}
                                                    {{
                                                        contact.last_name
                                                    }}</span
                                                >
                                                <div class="space-x-2">
                                                    <button
                                                        type="button"
                                                        @click="
                                                            editContact(contact)
                                                        "
                                                        class="text-blue-600 hover:text-blue-900 text-sm"
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="
                                                            deleteContact(
                                                                contact.id
                                                            )
                                                        "
                                                        class="text-red-600 hover:text-red-900 text-sm"
                                                    >
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
                    >
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            {{ isEditing ? "Update" : "Create" }}
                        </button>
                        <button
                            type="button"
                            @click="$emit('close')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CustomerModal",
    props: {
        customer: {
            type: Object,
            default: null,
        },
        categories: {
            type: Array,
            default: () => [],
        },
        isEditing: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            form: {
                name: "",
                reference: "",
                customer_category_id: "",
                start_date: "",
                description: "",
            },
        };
    },
    watch: {
        customer: {
            handler(newCustomer) {
                if (newCustomer) {
                    // Format the date for the input field (YYYY-MM-DD format)
                    const startDate = new Date(newCustomer.start_date);
                    const formattedDate = startDate.toISOString().split("T")[0];

                    this.form = {
                        name: newCustomer.name,
                        reference: newCustomer.reference,
                        customer_category_id: newCustomer.customer_category_id,
                        start_date: formattedDate,
                        description: newCustomer.description || "",
                    };
                } else {
                    this.resetForm();
                }
            },
            immediate: true,
        },
    },
    methods: {
        async saveCustomer() {
            try {
                const url = this.isEditing
                    ? `/api/customers/${this.customer.id}`
                    : "/api/customers";
                const method = this.isEditing ? "PUT" : "POST";

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify(this.form),
                });

                const data = await response.json();

                if (data.success) {
                    this.$emit("saved");
                }
            } catch (error) {
                console.error("Error saving customer:", error);
            }
        },
        resetForm() {
            this.form = {
                name: "",
                reference: "",
                customer_category_id: "",
                start_date: "",
                description: "",
            };
        },
        openContactModal() {
            this.$emit("open-contact-modal");
        },
        editContact(contact) {
            this.$emit("edit-contact", contact);
        },
        async deleteContact(contactId) {
            if (!confirm("Are you sure you want to delete this contact?"))
                return;

            try {
                const response = await fetch(`/api/contacts/${contactId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                });

                const data = await response.json();

                if (data.success) {
                    this.$emit("saved");
                }
            } catch (error) {
                console.error("Error deleting contact:", error);
            }
        },
    },
};
</script>
