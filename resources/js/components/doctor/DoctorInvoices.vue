<script setup>

import { onMounted , ref} from 'vue';

const invoices = ref([]);
const doctorId = ref(null);

const getInvoices = async (doctorId) => {
    try {
        const response = await fetch(`/api/getinvoices/doctor/${doctorId}`);
        if (!response.ok) {
            throw new Error('Failed to fetch invoices');
        }
        const data = await response.json();
        invoices.value = data.invoices;
    } catch (error) {
        console.error('Error fetching invoices:', error);
    }
}

onMounted(async () => {
    doctorId.value = parseInt(document.getElementById('app').getAttribute('data-user-id'));
    await getInvoices(doctorId.value);
});

const markAsPaid = async (invoice) => {
    try {
        const response = await axios.put(`/api/markAsPaid/doctor/${invoice.id}`);
        if (response.status === 200) {
            invoice.status = 'Payé';
        } else {
            console.error('Unexpected response status:', response.status);
        }
    } catch (error) {
        console.error('Error marking invoice as paid:', error);
    }
};

</script>


<template>

    <main class="content-wrap">
        <header class="content-head">
            <h1>Invoices</h1>

        </header>

        <div class="content">


        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th style="width: 10px">id</th>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>DueDate</th>
                            <th>Description</th>
                            <th>TotalAmount</th>
                            <th>Status</th>
                            <th>Options</th>

                        </tr>
                    </thead>

                    <tbody v-if="invoices.length > 0">
                        <tr v-for="(invoice, index) in invoices" :key="invoice.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ invoice.appointment.patient.name }}</td>
                            <td>{{ invoice.Date }}</td>
                            <td>{{ invoice.DueDate }}</td>
                            <td>{{ invoice.DescriptionOfServices }}</td>
                            <td>{{ invoice.TotalAmount }} DH</td>
                            <td>
                                <span v-if="invoice.status === 'Payé'" class="badge badge-success">{{ invoice.status }}</span>
                                <span v-else-if="invoice.status === 'En attente'" class="badge badge-warning">{{ invoice.status }}</span>
                            </td>
                            <td>
                                <button @click="markAsPaid(invoice)" class="btn btn-success" :disabled="invoice.status === 'Payé'">Mark as Paid</button>


                            </td>
                        </tr>
                    </tbody>

                    <tbody v-else>
                        <td colspan="9" class="text-center">No result found</td>
                    </tbody>


                </table>
            </div>
        </div>



    </div>
</main>
</template>
