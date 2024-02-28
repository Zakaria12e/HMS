
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


    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-3 col-12">
                    <div class="small-box">
                        <div class="inner">
                            <div class="d-flex justify-content-between">

                                <h3>{{ invoicesCount }}</h3>
                                <h3>{{ totalPaidAmount }} DH</h3>

                            </div>
                            <p>Invoices</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <router-link to="/admin/invoices"  class="small-box-footer bg-gray">
                            View Invoices
                        </router-link>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="small-box">
                        <div class="inner">
                            <div class="d-flex justify-content-between">

                                <h3>{{ appointmentCount }}</h3>

                            </div>
                            <p>Appointments</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <router-link to="/admin/appointments"  class="small-box-footer bg-gray">
                            View Appointments
                        </router-link>
                    </div>
                </div>

                <div class="col-lg-3 col-12">
                    <div class="small-box">
                        <div class="inner">
                            <div class="d-flex justify-content-between">

                                <h3>{{ totalUsersCount }}</h3>

                                <select v-model="selectedDateRange" @change="getPatientsCount()" class="form-select px-1 rounded border-0">
                                    <option value="today">Today</option>
                                    <option value="30_days">30 days</option>
                                    <option value="1_year">360 days</option>
                                    <option value="YTD">All Time</option>
                                </select>
                            </div>
                            <p>Patients</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <router-link to="/admin/patients" class="small-box-footer bg-gray">
                            View Patients
                        </router-link>
                    </div>
                </div>

                <div class="col-lg-3 col-12">
                    <div class="small-box">
                        <div class="inner">
                            <div class="d-flex justify-content-between">
                                <h3>{{ doctorsCount }}</h3>

                            </div>
                            <p>Doctors</p>
                        </div>
                        <div class="icon ">
                            <i class="ion ion-bag"></i>
                        </div>
                        <router-link to="/admin/doctors" class="small-box-footer bg-gray">
                            View Doctors
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

    </div>



</template>
