<template>
    <div
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="contact-modal-title"
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
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <form @submit.prevent="saveContact">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mt-3 text-center sm:mt-0 sm:text-left w-full"
                            >
                                <h3
                                    class="text-lg leading-6 font-medium text-gray-900"
                                    id="contact-modal-title"
                                >
                                    {{
                                        isEditing
                                            ? "Edit Contact"
                                            : "Add Contact"
                                    }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label
                                            for="contact_first_name"
                                            class="block text-sm font-medium text-gray-700"
                                            >First Name</label
                                        >
                                        <input
                                            type="text"
                                            id="contact_first_name"
                                            v-model="form.first_name"
                                            required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="contact_last_name"
                                            class="block text-sm font-medium text-gray-700"
                                            >Last Name</label
                                        >
                                        <input
                                            type="text"
                                            id="contact_last_name"
                                            v-model="form.last_name"
                                            required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                        />
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
                            {{ isEditing ? "Update" : "Add" }}
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
    name: "ContactModal",
    props: {
        contact: {
            type: Object,
            default: null,
        },
        isEditing: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            form: {
                first_name: "",
                last_name: "",
            },
        };
    },
    watch: {
        contact: {
            handler(newContact) {
                if (newContact) {
                    this.form = {
                        first_name: newContact.first_name,
                        last_name: newContact.last_name,
                    };
                } else {
                    this.resetForm();
                }
            },
            immediate: true,
        },
    },
    methods: {
        async saveContact() {
            try {
                const url = this.isEditing
                    ? `/api/contacts/${this.contact.id}`
                    : "/api/contacts";
                const method = this.isEditing ? "PUT" : "POST";

                const formData = {
                    ...this.form,
                    customer_id: this.$parent.currentCustomer.id,
                };

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify(formData),
                });

                const data = await response.json();

                if (data.success) {
                    this.$emit("saved");
                }
            } catch (error) {
                console.error("Error saving contact:", error);
            }
        },
        resetForm() {
            this.form = {
                first_name: "",
                last_name: "",
            };
        },
    },
};
</script>
