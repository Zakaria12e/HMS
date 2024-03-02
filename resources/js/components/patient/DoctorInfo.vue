<script setup>


import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const doctor = ref({ 'data': [] });
const route = useRoute();
const existingWorkingHours = ref([]);

const getDoctor = async () => {
    try {
        const response = await axios.get(`/patient/doctorinformation/${route.params.id}`);
        doctor.value = response.data;

    } catch (error) {
        console.error('Error fetching doctor informations:', error);
    }
};

const getdays = async() => {

try {
    const response = await axios.get(`/api/working-hours/${route.params.id}`);
    existingWorkingHours.value = response.data;
} catch (error) {
    console.error('Error fetching existing working hours', error);
}

};


onMounted(async () => {
    getDoctor();
    getdays();
});
</script>

<template>


        <section class="section section-md bg-transparent">
            <div class="container">
              <div class="row row-30 person">
                <div class="col-md-6">
                  <div class="row row-30 row-lg-50">
                    <div class="col-xs-6 col-md-12"> <img :src="'/img/doctor_1.png'" alt="" width="520" height="390"/>
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
                  <h2>{{ doctor.name }}</h2>
                  <h4 class="person-meta">{{ doctor.specialization }}</h4>
                  <p class="big">{{ doctor.description }}</p>

                </div>
              </div>
            </div>
          </section>




</template>
