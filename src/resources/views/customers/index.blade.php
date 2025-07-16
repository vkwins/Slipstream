@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8" x-data="customerManager()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Customers</h2>
            <button
                @click="openCreateModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                Create Customer
            </button>
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
                        x-model="searchQuery"
                        @keyup.enter="applySearch()"
                        placeholder="Search by name, reference, or description..."
                        value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <button
                    @click="applySearch()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                    Apply
                </button>
                <button
                    @click="clearSearch()"
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
                <tbody class="bg-white divide-y divide-gray-200" id="customers-table-body">
                    @foreach($customers as $customer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                            @if($customer->description)
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $customer->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->reference }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($customer->category->name === 'Gold') bg-yellow-100 text-yellow-800
                                @elseif($customer->category->name === 'Silver') bg-gray-100 text-gray-800
                                @else bg-orange-100 text-orange-800
                                @endif">
                                {{ $customer->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->start_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->contacts->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="openEditModal({{ $customer->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button @click="confirmDelete({{ $customer->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $customers->links() }}
        </div>
        @endif
    </div>

    <!-- Customer Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <form @submit.prevent="saveCustomer()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    <span x-text="isEditing ? 'Edit Customer' : 'Create Customer'"></span>
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <!-- Customer Form Fields -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="name" x-model="form.name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference</label>
                                            <input type="text" id="reference" x-model="form.reference" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                            <select id="category" x-model="form.customer_category_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                            <input type="date" id="start_date" x-model="form.start_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea id="description" x-model="form.description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>

                                    <!-- Contacts Section -->
                                    <div x-show="isEditing" class="border-t pt-4">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="text-md font-medium text-gray-900">Contacts</h4>
                                            <button type="button" @click="openContactModal()" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1 px-3 rounded transition duration-150 ease-in-out">
                                                Add Contact
                                            </button>
                                        </div>

                                        <!-- Contacts List -->
                                        <div class="space-y-2" id="contacts-list">
                                            <template x-for="contact in currentCustomer.contacts" :key="contact.id">
                                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                                    <span x-text="contact.first_name + ' ' + contact.last_name"></span>
                                                    <div class="space-x-2">
                                                        <button type="button" @click="editContact(contact)" class="text-blue-600 hover:text-blue-900 text-sm">Edit</button>
                                                        <button type="button" @click="deleteContact(contact.id)" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-text="isEditing ? 'Update' : 'Create'"></span>
                        </button>
                        <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div x-show="showContactModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="contact-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showContactModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showContactModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="saveContact()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="contact-modal-title">
                                    <span x-text="isEditingContact ? 'Edit Contact' : 'Add Contact'"></span>
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="contact_first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" id="contact_first_name" x-model="contactForm.first_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="contact_last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" id="contact_last_name" x-model="contactForm.last_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-text="isEditingContact ? 'Update' : 'Add'"></span>
                        </button>
                        <button type="button" @click="closeContactModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="delete-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="delete-modal-title">
                                Delete Customer
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this customer? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="deleteCustomer()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" @click="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function customerManager() {
    return {
        searchQuery: '{{ request('search') }}',
        showModal: false,
        showContactModal: false,
        showDeleteModal: false,
        isEditing: false,
        isEditingContact: false,
        currentCustomer: null,
        customerToDelete: null,
        contactToEdit: null,
        form: {
            name: '',
            reference: '',
            customer_category_id: '',
            start_date: '',
            description: ''
        },
        contactForm: {
            first_name: '',
            last_name: ''
        },

        openCreateModal() {
            this.isEditing = false;
            this.resetForm();
            this.showModal = true;
        },

        openEditModal(customerId) {
            this.isEditing = true;
            this.loadCustomer(customerId);
        },

        async loadCustomer(customerId) {
            try {
                const response = await fetch(`/customers/${customerId}`);
                const data = await response.json();
                this.currentCustomer = data.customer;

                // Format the date for the input field (YYYY-MM-DD format)
                const startDate = new Date(data.customer.start_date);
                const formattedDate = startDate.toISOString().split('T')[0];

                this.form = {
                    name: data.customer.name,
                    reference: data.customer.reference,
                    customer_category_id: data.customer.customer_category_id,
                    start_date: formattedDate,
                    description: data.customer.description || ''
                };
                this.showModal = true;
            } catch (error) {
                console.error('Error loading customer:', error);
            }
        },

        async saveCustomer() {
            try {
                const url = this.isEditing ? `/customers/${this.currentCustomer.id}` : '/customers';
                const method = this.isEditing ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    this.closeModal();
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error saving customer:', error);
            }
        },

        confirmDelete(customerId) {
            this.customerToDelete = customerId;
            this.showDeleteModal = true;
        },

        async deleteCustomer() {
            try {
                const response = await fetch(`/customers/${this.customerToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.closeDeleteModal();
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error deleting customer:', error);
            }
        },

        openContactModal() {
            this.isEditingContact = false;
            this.resetContactForm();
            this.showContactModal = true;
        },

        editContact(contact) {
            this.isEditingContact = true;
            this.contactToEdit = contact;
            this.contactForm = {
                first_name: contact.first_name,
                last_name: contact.last_name
            };
            this.showContactModal = true;
        },

        async saveContact() {
            try {
                const url = this.isEditingContact ? `/contacts/${this.contactToEdit.id}` : '/contacts';
                const method = this.isEditingContact ? 'PUT' : 'POST';

                const formData = {
                    ...this.contactForm,
                    customer_id: this.currentCustomer.id
                };

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    this.closeContactModal();
                    this.loadCustomer(this.currentCustomer.id);
                }
            } catch (error) {
                console.error('Error saving contact:', error);
            }
        },

        async deleteContact(contactId) {
            if (!confirm('Are you sure you want to delete this contact?')) return;

            try {
                const response = await fetch(`/contacts/${contactId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.loadCustomer(this.currentCustomer.id);
                }
            } catch (error) {
                console.error('Error deleting contact:', error);
            }
        },

                                applySearch() {
            // Simple page reload approach for search
            const searchParam = this.searchQuery ? `?search=${encodeURIComponent(this.searchQuery)}` : '';
            window.location.href = `/customers${searchParam}`;
        },

        clearSearch() {
            this.searchQuery = '';
            window.location.href = '/customers';
        },



        resetForm() {
            this.form = {
                name: '',
                reference: '',
                customer_category_id: '',
                start_date: '',
                description: ''
            };
        },

        resetContactForm() {
            this.contactForm = {
                first_name: '',
                last_name: ''
            };
        },

        closeModal() {
            this.showModal = false;
            this.resetForm();
        },

        closeContactModal() {
            this.showContactModal = false;
            this.resetContactForm();
            this.contactToEdit = null;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
            this.customerToDelete = null;
        }
    }
}
</script>
@endpush
@endsection
