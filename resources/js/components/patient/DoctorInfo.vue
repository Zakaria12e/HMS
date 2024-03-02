<script setup>


import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const doctor = ref({ 'data': [] });
const route = useRoute();

const getDoctor = async () => {
    try {
        const response = await axios.get(`/patient/doctorinformation/${route.params.id}`);
        doctor.value = response.data;

    } catch (error) {
        console.error('Error fetching doctor informations:', error);
    }
};


onMounted(async () => {
    getDoctor();
});
</script>

<template>



        <section class="doctor-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6" v-if="doctor">
                        <div class="doctor-info">
                            <h2></h2>
                            <p>Email: {{ doctor.email }}</p>
                            <p>Specialization: </p>
                            <p>Department: {{ doctor.department_name}}</p>
                            <p>Contact Number: {{ doctor.contact_number }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6" v-else>
                        <p>Loading...</p>
                    </div>
                </div>
            </div>
        </section>



</template>
