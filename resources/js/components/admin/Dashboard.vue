
<script setup>

import axios from 'axios';
import { ref, onMounted , watch  } from 'vue';



const appointmentCount = ref(0);
const invoicesCount = ref(0);
const doctorsCount = ref(0);
const selectedDateRange = ref('today');
const totalUsersCount = ref(0);
const totalPaidAmount = ref(0);




const getPatientsCount = () => {

    axios.get('/api/dashboard/users-count',{

        params: {
            date_range: selectedDateRange.value,
        }
    })
     .then((response) => {

        totalUsersCount.value = response.data.count;
     })

};

const fetchAppointmentCount = async () => {
  try {
    const response = await axios.get('/api/dashboard/appointment-count');
    appointmentCount.value = response.data.count;
  } catch (error) {
    console.error('Error fetching appointment count:', error);
  }
};

const fetchDoctorsCount = async () => {
  try {
    const response = await axios.get('/api/dashboard/doctors-count');
    doctorsCount.value = response.data.count;
  } catch (error) {
    console.error('Error fetching doctors count:', error);
  }
};

const fetchInvoicesCount = async () => {
  try {
    const response = await axios.get('/api/dashboard/invoices-count');
    invoicesCount.value = response.data.count;
  } catch (error) {
    console.error('Error fetching invoices count:', error);
  }
};
const fetchTotalPaidAmount = async () => {
  try {
    const response = await axios.get('/api/dashboard/total-paid-amount');
    totalPaidAmount.value = response.data.totalPaidAmount;
  } catch (error) {
    console.error('Error fetching total paid amount:', error);
  }
};

onMounted(() => {
  fetchAppointmentCount();
  fetchDoctorsCount();
  getPatientsCount();
  fetchInvoicesCount();
  fetchTotalPaidAmount();
});


</script>

<template>






<main class="content-wrap">
    <header class="content-head">
        <h1>Dashboard</h1>


    </header>

    <div class="content">
        <section class="info-boxes">
            <div class="info-box">
                <div class="box-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M21 20V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1zm-2-1H5V5h14v14z"/><path d="M10.381 12.309l3.172 1.586a1 1 0 0 0 1.305-.38l3-5-1.715-1.029-2.523 4.206-3.172-1.586a1.002 1.002 0 0 0-1.305.38l-3 5 1.715 1.029 2.523-4.206z"/></svg>
                </div>

                <div class="box-content">
                    <span class="big">{{ totalPaidAmount }}</span>
                    Current price (DH) - {{ invoicesCount }}
                </div>
            </div>

            <div class="info-box">
                <div class="box-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M128 0c13.3 0 24 10.7 24 24V64H296V24c0-13.3 10.7-24 24-24s24 10.7 24 24V64h40c35.3 0 64 28.7 64 64v16 48V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V192 144 128C0 92.7 28.7 64 64 64h40V24c0-13.3 10.7-24 24-24zM400 192H48V448c0 8.8 7.2 16 16 16H384c8.8 0 16-7.2 16-16V192zM329 297L217 409c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 95-95c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </div>

                <div class="box-content">
                    <span class="big">{{ appointmentCount }}</span>
                    Appointments
                </div>
            </div>

            <div class="info-box active">
                <div class="box-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3,21c0,0.553,0.448,1,1,1h16c0.553,0,1-0.447,1-1v-1c0-3.714-2.261-6.907-5.478-8.281C16.729,10.709,17.5,9.193,17.5,7.5 C17.5,4.468,15.032,2,12,2C8.967,2,6.5,4.468,6.5,7.5c0,1.693,0.771,3.209,1.978,4.219C5.261,13.093,3,16.287,3,20V21z M8.5,7.5 C8.5,5.57,10.07,4,12,4s3.5,1.57,3.5,3.5S13.93,11,12,11S8.5,9.43,8.5,7.5z M12,13c3.859,0,7,3.141,7,7H5C5,16.141,8.14,13,12,13z"/></svg>
                </div>

                <div class="box-content">
                    <div class="col">
                        <select v-model="selectedDateRange" @change="getPatientsCount()" class="form-select px-1 rounded border-0">
                            <option value="today">Today</option>
                            <option value="30_days">30 days</option>
                            <option value="1_year">360 days</option>
                            <option value="YTD">All Time</option>
                        </select>
                    </div>
                    <span class="big">{{ totalUsersCount }}</span>
                    Patients
                </div>
            </div>
            <div class="info-box active">
                <div class="box-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-96 55.2C54 332.9 0 401.3 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7c0-81-54-149.4-128-171.1V362c27.6 7.1 48 32.2 48 62v40c0 8.8-7.2 16-16 16H336c-8.8 0-16-7.2-16-16s7.2-16 16-16V424c0-17.7-14.3-32-32-32s-32 14.3-32 32v24c8.8 0 16 7.2 16 16s-7.2 16-16 16H256c-8.8 0-16-7.2-16-16V424c0-29.8 20.4-54.9 48-62V304.9c-6-.6-12.1-.9-18.3-.9H178.3c-6.2 0-12.3 .3-18.3 .9v65.4c23.1 6.9 40 28.3 40 53.7c0 30.9-25.1 56-56 56s-56-25.1-56-56c0-25.4 16.9-46.8 40-53.7V311.2zM144 448a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"/></svg>
                </div>

                <div class="box-content">

                    <span class="big">{{ doctorsCount }}</span>
                    Doctors
                </div>
            </div>


        </section>


    </div>

</main>

</template>
