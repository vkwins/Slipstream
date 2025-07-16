@extends('layouts.app')

@section('content')
<div id="app">
    <!-- Vue will render the template here -->
</div>

<script>
// Debug: Check if Vue is loaded
console.log('Vue loaded:', typeof Vue !== 'undefined')

if (typeof Vue === 'undefined') {
    console.error('Vue is not loaded!')
    document.getElementById('app').innerHTML = '<div class="p-4 text-red-600">Error: Vue.js failed to load. Please refresh the page.</div>'
} else {
    const { createApp } = Vue

    createApp({
    delimiters: ['[[', ']]'],
    data() {
        return {
            customers: [],
            categories: [],
            searchQuery: '',
            showModal: false,
            isEditing: false,
            currentCustomer: null,
            currentContact: null,
            showContactModal: false,
            editingContactIndex: null,
            vueReady: false
        }
    },
        async mounted() {
        console.log('Vue mounted - showModal:', this.showModal, 'vueReady:', this.vueReady)
        // Force modal to be hidden initially
        this.showModal = false
        this.vueReady = true
        await this.loadCustomers()
        this.searchQuery = new URLSearchParams(window.location.search).get('search') || ''
        console.log('After loading - showModal:', this.showModal, 'vueReady:', this.vueReady)
    },
    template: `
        <div>
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-900">Customers</h2>
                        <div>
                            <button
                                @click="openCreateModal"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                Create Customer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="max-w-md">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Customers</label>
                        <div class="flex space-x-2">
                            <div class="relative flex-1">
                                <input
                                    type="text"
                                    id="search"
                                    v-model="searchQuery"
                                    @keyup.enter="applySearch"
                                    placeholder="Search by name, reference, or description..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button
                                @click="applySearch"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                Apply
                            </button>
                            <button
                                @click="clearSearch"
                                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacts</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="customer in customers" :key="customer.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">[[ customer.name ]]</div>
                                        <div v-if="customer.description" class="text-sm text-gray-500 truncate max-w-xs">[[ customer.description ]]</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">[[ customer.reference ]]</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                            :class="{
                                                'bg-yellow-100 text-yellow-800': customer.category.name === 'Gold',
                                                'bg-gray-100 text-gray-800': customer.category.name === 'Silver',
                                                'bg-orange-100 text-orange-800': customer.category.name === 'Bronze'
                                            }">
                                            [[ customer.category.name ]]
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">[[ formatDate(customer.start_date) ]]</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">[[ customer.contacts.length ]]</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button @click="openEditModal(customer)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                        <button @click="confirmDelete(customer)" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!-- Customer Modal -->
            <div v-if="showModal && vueReady" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            <span v-if="isEditing">Edit Customer</span>
                            <span v-else>Create Customer</span>
                        </h3>
                        <form @submit.prevent="saveCustomer">
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="currentCustomer.name"
                                        required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="reference" class="block text-sm font-medium text-gray-700">Reference</label>
                                    <input
                                        type="text"
                                        id="reference"
                                        v-model="currentCustomer.reference"
                                        required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea
                                        id="description"
                                        v-model="currentCustomer.description"
                                        rows="3"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                                <div>
                                    <label for="customer_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                    <select
                                        id="customer_category_id"
                                        v-model="currentCustomer.customer_category_id"
                                        required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select a category</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            [[ category.name ]]
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input
                                        type="date"
                                        id="start_date"
                                        v-model="currentCustomer.start_date"
                                        required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <!-- Contacts Section -->
                                <div class="border-t pt-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="text-sm font-medium text-gray-700">Contacts</h4>
                                        <button
                                            type="button"
                                            @click="addContact"
                                            class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md transition duration-150 ease-in-out">
                                            Add Contact
                                        </button>
                                    </div>

                                    <!-- Contacts List -->
                                    <div v-if="currentCustomer.contacts && currentCustomer.contacts.length > 0" class="space-y-2">
                                        <div v-for="(contact, index) in currentCustomer.contacts" :key="contact.id || index" class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">[[ contact.first_name ]] [[ contact.last_name ]]</div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button
                                                    type="button"
                                                    @click="editContact(index)"
                                                    class="text-sm text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </button>
                                                <button
                                                    type="button"
                                                    @click="deleteContact(index)"
                                                    class="text-sm text-red-600 hover:text-red-900">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No Contacts Message -->
                                    <div v-else class="text-sm text-gray-500 italic">
                                        No contacts added yet.
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <span v-if="isEditing">Update</span>
                                    <span v-else>Create</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Modal -->
        <div v-if="showContactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <span v-if="editingContactIndex !== null">Edit Contact</span>
                        <span v-else>Add Contact</span>
                    </h3>
                    <form @submit.prevent="saveContact">
                        <div class="space-y-4">
                            <div>
                                <label for="contact_first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                                <input
                                    type="text"
                                    id="contact_first_name"
                                    v-model="currentContact.first_name"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="contact_last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input
                                    type="text"
                                    id="contact_last_name"
                                    v-model="currentContact.last_name"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="closeContactModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span v-if="editingContactIndex !== null">Update</span>
                                <span v-else>Add</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `,
    methods: {
        async loadCustomers() {
            try {
                const searchParam = this.searchQuery ? `?search=${encodeURIComponent(this.searchQuery)}` : ''
                const response = await fetch(`/api/customers${searchParam}`)
                const data = await response.json()
                this.customers = data.customers.data || []
                this.categories = data.categories || []
            } catch (error) {
                console.error('Error loading customers:', error)
            }
        },
        async applySearch() {
            await this.loadCustomers()
            // Update URL without page reload
            const searchParam = this.searchQuery ? `?search=${encodeURIComponent(this.searchQuery)}` : ''
            window.history.pushState({}, '', `/customers${searchParam}`)
        },
        async clearSearch() {
            this.searchQuery = ''
            await this.loadCustomers()
            // Update URL without page reload
            window.history.pushState({}, '', '/customers')
        },
        debugModal() {
            console.log('Modal state:', {
                showModal: this.showModal,
                vueReady: this.vueReady,
                isEditing: this.isEditing,
                currentCustomer: this.currentCustomer
            })
            alert(`Modal state: showModal=${this.showModal}, vueReady=${this.vueReady}`)
        },
        openCreateModal() {
            this.isEditing = false
            this.currentCustomer = {
                name: '',
                reference: '',
                description: '',
                customer_category_id: '',
                start_date: new Date().toISOString().split('T')[0],
                contacts: []
            }
            this.showModal = true
        },
                        openEditModal(customer) {
            this.isEditing = true
            this.currentCustomer = {
                ...customer,
                // Format date for HTML date input (YYYY-MM-DD)
                start_date: customer.start_date ? new Date(customer.start_date).toISOString().split('T')[0] : '',
                // Ensure contacts array exists
                contacts: customer.contacts || []
            }
            this.showModal = true
        },
        closeModal() {
            this.showModal = false
            this.currentCustomer = null
            this.isEditing = false
        },
                async saveCustomer() {
            try {
                const url = this.isEditing
                    ? `/api/customers/${this.currentCustomer.id}`
                    : '/api/customers'

                const method = this.isEditing ? 'PUT' : 'POST'

                // Prepare customer data
                const customerData = {
                    name: this.currentCustomer.name,
                    reference: this.currentCustomer.reference,
                    description: this.currentCustomer.description,
                    customer_category_id: this.currentCustomer.customer_category_id,
                    start_date: this.currentCustomer.start_date
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(customerData)
                })

                if (response.ok) {
                    const result = await response.json()

                    // Handle contacts
                    if (this.currentCustomer.contacts && this.currentCustomer.contacts.length > 0) {
                        for (const contact of this.currentCustomer.contacts) {
                            if (!contact.id) {
                                // New contact - create it
                                const contactData = {
                                    customer_id: result.customer.id,
                                    first_name: contact.first_name,
                                    last_name: contact.last_name
                                }

                                await fetch('/api/contacts', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify(contactData)
                                })
                            }
                        }
                    }

                    await this.loadCustomers()
                    this.closeModal()
                } else {
                    const error = await response.json()
                    alert('Error: ' + (error.message || 'Something went wrong'))
                }
            } catch (error) {
                console.error('Error saving customer:', error)
                alert('Error saving customer')
            }
        },
        confirmDelete(customer) {
            if (confirm('Are you sure you want to delete this customer?')) {
                this.deleteCustomer(customer)
            }
        },
        async deleteCustomer(customer) {
            try {
                const response = await fetch(`/api/customers/${customer.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })

                if (response.ok) {
                    await this.loadCustomers()
                }
            } catch (error) {
                console.error('Error deleting customer:', error)
            }
        },
                formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            })
        },

        // Contact management methods
        addContact() {
            this.currentContact = {
                first_name: '',
                last_name: ''
            }
            this.editingContactIndex = null
            this.showContactModal = true
        },
        editContact(index) {
            this.currentContact = { ...this.currentCustomer.contacts[index] }
            this.editingContactIndex = index
            this.showContactModal = true
        },
        deleteContact(index) {
            if (confirm('Are you sure you want to delete this contact?')) {
                this.currentCustomer.contacts.splice(index, 1)
            }
        },
        closeContactModal() {
            this.showContactModal = false
            this.currentContact = null
            this.editingContactIndex = null
        },
        saveContact() {
            if (this.editingContactIndex !== null) {
                // Update existing contact
                this.currentCustomer.contacts[this.editingContactIndex] = { ...this.currentContact }
            } else {
                // Add new contact
                this.currentCustomer.contacts.push({ ...this.currentContact })
            }
            this.closeContactModal()
        }
    }
}).mount('#app')
}

// Debug: Check if app mounted
console.log('Vue app mounted successfully')
</script>
@endsection
