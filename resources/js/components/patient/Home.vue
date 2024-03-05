<script setup>

import { ref, onMounted} from 'vue';

const doctors = ref({'data':[]});




const getDoctors = async () => {
  try {
    const response = await axios.get('/api/patient/doctors');
    doctors.value = response.data.data;

  } catch (error) {
    console.error('Error fetching doctors:', error);
  }
};





const departments = ref([]);

const getDepartments = async () => {
  try {

    const response = await axios.get('/api/departments');
    departments.value = response.data;
  } catch (error) {
    console.error('Error fetching departments:', error);
  }
};


onMounted(() => {
    getDoctors();
    getDepartments();


});


 </script>



<template>

    <section class="banner_part" style="background-image: url('img/banner_bg.png'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
        <div class="container">
        <div class="row align-items-center">
        <div class="col-lg-5 col-xl-5">
        <div class="banner_text">
        <div class="banner_text_iner">
        <h5>We are here for your care</h5>
        <h1>Best Care &
        Better Doctor</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing
        elit sed do eiusmod tempor incididunt ut labore et dolore
        magna aliqua. Quis ipsum suspendisse ultrices gravida.Risus cmodo viverra </p>
        <a href="#appointment" class="mb-3 btn btn-primary">Make an appointment</a>
        </div>
        </div>
        </div>
        <div class="col-lg-7">
        <div class="banner_img">
        <img  src="img/banner_img.png" alt>
        </div>
        </div>
        </div>
        </div>
        </section>


        <section class="about_us padding_top">
        <div class="container">
        <div class="row justify-content-between align-items-center">
        <div class="col-md-6 col-lg-6">
        <div class="about_us_img">
        <img src="img/top_service.png" alt>
        </div>
        </div>
        <div class="col-md-6 col-lg-5">
        <div class="about_us_text">
        <h2>About us</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed
        do eiusmod tempor incididunt ut labore et dolore magna aliqua
        Quis ipsum suspendisse ultrices gravida. Risus cmodo viverra
        maecenas accumsan lacus vel</p>
        <a class="btn_2 " href="#">learn more</a>
        <div class="banner_item">
            <div class="single_item">
              <img src="img/banner_1.svg" alt style="width: 50px; height: 50px;">
              <h5>Emergency</h5>
            </div>
            <div class="single_item">
              <img src="img/banner_2.svg" alt style="width: 50px; height: 50px;">
              <h5>Appointment</h5>
            </div>
            <div class="single_item">
              <img src="img/banner_3.svg" alt style="width: 50px; height: 50px;">
              <h5>Qualified</h5>
            </div>
          </div>

        </div>
        </div>
        </div>
        </div>
        </section>


        <section class="feature_part">
        <div class="container">
        <div class="row justify-content-center">
        <div class="col-xl-8">
        <div class="section_tittle text-center">
        <h2>Our services</h2>
        </div>
        </div>
        </div>
        <div class="row justify-content-between align-items-center">
        <div class="col-lg-3 col-sm-12">
        <div class="single_feature">
        <div class="single_feature_part">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
        </div>
        <div class="single_feature">
        <div class="single_feature_part">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
        </div>
        </div>
        <div class="col-lg-4 col-sm-12">
        <div class="single_feature_img">
        <img src="img/service.png" alt>
        </div>
        </div>
        <div class="col-lg-3 col-sm-12">
        <div class="single_feature">
        <div class="single_feature_part">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
        </div>
        <div class="single_feature">
        <div class="single_feature_part">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
        </div>
        </div>
        </div>
        </div>
        </section>


        <section class="our_depertment section_padding">
        <div class="container">
        <div class="row justify-content-center text-center">
        <div class="col-xl-12">
        <div class="depertment_content">
        <div class="row justify-content-center">
        <div class="col-xl-8">
        <h2>Our Depertment</h2>
        <div class="row">

            <div v-for="department in departments" :key="department.id" class="col-lg-6 col-sm-6">
                <div class="single_our_department">
                  <h4 style="color: rgb(102, 102, 245)">{{ department.name }}</h4>
                  <p>{{ department.description }}</p>
                </div>
              </div>

        <div class="single_our_depertment">


        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </section>


        <section class="doctor_part section_padding">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-xl-8">
                  <div class="section_tittle text-center">
                    <h2>Experienced Doctors</h2>
                    <p>Face replenish sea good winged bearing years air divide wasHave night male also</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div v-for="doctor in doctors" :key="doctor.doctor_id" class="col-sm-6 col-lg-3">
                  <div class="single_blog_item" style="padding: 20px;">
                    <div class="single_blog_img">
                        <img :src="doctor.img_path ? doctor.img_path : 'img/doctor_1.jpg'" alt="doctor" width="300" height="300">

                    </div>

                    <div class="single_text">
                      <div class="single_blog_text">
                        <h3>{{ doctor.name }}</h3>
                        <p>{{ doctor.specialization }}</p>
                        <router-link :to="{ name: 'patient.DoctorInfo', params: { id: parseInt(doctor.doctor_id) } }" class="mb-3 btn btn-primary">See Profile</router-link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>






</template>
