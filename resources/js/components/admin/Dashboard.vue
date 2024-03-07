
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


<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Dashboard</h1>
    </div>
    <div class="col-sm-6">

    </div>
    </div>
    </div>
    </div>






    <section class="content">
        <div class="container-fluid">

        <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>
        <div class="info-box-content">
        <span class="info-box-text">Invoices</span>
        <span class="info-box-number">
        {{ invoicesCount }}
        {{ totalPaidAmount }} DH
        </span>
        </div>

        </div>

        </div>

        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-alt"></i></span>
        <div class="info-box-content">
        <span class="info-box-text">Appointments</span>
        <span class="info-box-number">{{ appointmentCount }}</span>
        </div>

        </div>

        </div>


        <div class="clearfix hidden-md-up"></div>
        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="nav-icon fas fa-user-md"></i></span>
        <div class="info-box-content">
        <span class="info-box-text">Doctors</span>
        <span class="info-box-number">{{ doctorsCount }}</span>
        </div>

        </div>

        </div>

        <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
        <div class="info-box-content">
            <div class="row align-items-center">
                <div class="col">
                    <span class="info-box-text">Patients</span>
                </div>
                <div class="col">
                    <select v-model="selectedDateRange" @change="getPatientsCount()" class="form-select px-1 rounded border-0">
                        <option value="today">Today</option>
                        <option value="30_days">30 days</option>
                        <option value="1_year">360 days</option>
                        <option value="YTD">All Time</option>
                    </select>
                </div>
            </div>
            <span class="info-box-number">{{ totalUsersCount }}</span>
        </div>


        </div>

        </div>

        </div>
</div></section>

</template>
