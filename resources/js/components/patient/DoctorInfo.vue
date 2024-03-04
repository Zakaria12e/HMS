<script setup>

import { ref, onMounted , watchEffect, defineProps} from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useToastr } from '/resources/js/toastr.js';


const { 'user-id': userId } = defineProps(['user-id']);
const doctor = ref({ 'data': [] });
const route = useRoute();
const existingWorkingHours = ref([]);
const departments = ref([]);
const startTime = ref('');
const endTime = ref('');
const timeSlots = ref([]);
const selectedDay = ref('');
const selectedDate = ref('');
const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];




const getDoctor = async () => {
    try {
        const response = await axios.get(`/patient/doctorinformation/${route.params.id}`);
        window.scrollTo({ top: 0});
        doctor.value = response.data;


    } catch (error) {
        console.error('Error fetching doctor informations:', error);
    }
};

const getdays = async() => {

try {
    const response = await axios.get(`/api/working-days-hours/${route.params.id}`);
    existingWorkingHours.value = response.data;
} catch (error) {
    console.error('Error fetching existing working hours', error);
}

};


const getDepartments = async () => {
  try {

    const response = await axios.get('/api/departments');
    departments.value = response.data;
  } catch (error) {
    console.error('Error fetching departments:', error);
  }
};



const getWorkingHours = async (selectedDay) => {
    try {

        const currentDate = new Date();

        const daysToAdd = daysOfWeek.indexOf(selectedDay);
        currentDate.setDate(currentDate.getDate() + daysToAdd);

        selectedDate.value = currentDate.toISOString().split('T')[0];

         selectedDate.value
        const response = await axios.get(`/api/working-hours/${route.params.id}?day=${selectedDay}`);
        const workingHours = response.data;

        timeSlots.value = [];

        if (workingHours.length > 0) {
            const start = new Date(`04/04/2003 ${workingHours[0].start_time}`);
            const end = new Date(`04/04/2003 ${workingHours[0].end_time}`);

            const timeSlot = new Date(start);


            while (timeSlot < end) {
                const slot = {
                    time: timeSlot.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false }),
                    value: timeSlot.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false })
                };
                timeSlots.value.push(slot);

                timeSlot.setMinutes(timeSlot.getMinutes() + 20);
            }
        }
    } catch (error) {
        console.error('Error fetching working hours for the selected day', error);
    }
};






onMounted(async () => {
    getDoctor();
    getdays();
    getDepartments();
});

watchEffect(() => {
    getWorkingHours(selectedDay.value);
});


</script>

<style scoped>

p{
    color:black !important;
    font-size: 20px;
}
</style>


<template>
        <section class="section section-md bg-transparent">
            <div class="container">
              <div class="row row-30 person">
                <div class="col-md-6">
                  <div class="row row-30 row-lg-50">
                    <div class="col-xs-6 col-md-12"> <img :src="'/img/doctor_1.jpg'" alt="" width="520" height="390"/>
                    </div>
                    <div class="col-xs-6 col-md-12">

                      <div class="text-block text-block-3"><br><br>
                        <h5>Work days</h5>
                        <div class="col-md-6" v-for="(days, index) in existingWorkingHours" :key="index">
                        <ul class="list">
                          <li class="list-item">{{ days.day }} – {{ days.start_time }} – {{ days.end_time }}</li>
                        </ul>
                        </div>

                      </div>
                      <div class="text-block text-block-3">
                        <h5>Contact</h5>
                        <ul class="list list-icons">
                          <li class="list-item">
                            <div class=""></div><a href="mailto:#">{{ doctor.email }}</a>
                          </li>
                          <li class="list-item">
                            <div class="">{{ doctor.contact_number }}</div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 person-overview">
                  <h1>{{ doctor.name }}</h1><br>
                  <h4 style="color:rgb(102, 102, 245)" class="person-meta">{{ doctor.specialization }}</h4><br><hr><br>
                  <p class="big">{{ doctor.description }}</p>

                </div>
              </div>
            </div>
          </section>

          <section class="regervation_part section_padding" >
            <div class="container">
            <div class="row align-items-center regervation_content">
            <div class="col-lg-7">
            <div class="regervation_part_iner">
                <section id="appointment">


                    <form @submit.prevent="submitAppointment">
                        <h2 class="mb-4">Make an Appointment</h2>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="inputtitle" class="form-label">Title</label>
                                <input v-model="appointmentTitle" type="text" class="form-control" id="inputtitle" placeholder="Enter Title">
                            </div>
                            <div class="col-md-6">
                                <label for="Select" class="form-label">Select Service</label>
                                <select v-model="selectedDepartment" class="form-select" id="Select">
                                    <option value="" selected>Select service</option>
                                    <option v-for="(department, index) in departments" :key="index" :value="department.name">{{ department.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">

                            <div class="mb-6 row">
                                <div class="col-md-6">
                                    <input type="hidden" v-model="selectedDate">
                                    <label for="SelectDay" class="form-label">Select Day</label>
                                    <select v-model="selectedDay" class="form-select" id="SelectDay">
                                        <option value="" selected disabled>Select day</option>
                                        <option v-for="day in daysOfWeek" :key="day" :value="day">{{ day }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                <label for="Select2" class="form-label">Select Time</label>
                                <select v-model="selectedTimeSlot" class="form-select" id="Select2">
                                    <option value="" selected disabled>Select time</option>
                                    <option v-for="(slot, index) in timeSlots" :key="index" :value="slot.value">{{ slot.time }}</option>
                                </select>
                            </div>
                            </div>

                            <input type="hidden" v-model="selectedDate">
                        </div>
                        <div class="mb-3">
                            <label for="Textarea" class="form-label">Your Note</label>
                            <textarea v-model="appointmentNote" class="form-control" id="Textarea" rows="4" placeholder="Enter your note"></textarea>
                        </div>
                        <div class="regerv_btn">
                            <button type="submit" class="btn btn-primary">Make an Appointment</button>
                        </div>
                    </form>

                </section>

        </div>
        </div>
        <div class="col-lg-5 col-md-6">
        <div class="reservation_img">
        <img :src="'/img/reservation.png'" alt class="reservation_img_iner">
        </div>
        </div>
        </div>
        </div>
        </section>






</template>
