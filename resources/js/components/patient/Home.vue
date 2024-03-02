<script setup>

import { ref, onMounted } from 'vue';

const doctors = ref({'data':[]});

const getDoctors = async () => {
  try {
    const response = await axios.get('/api/patient/doctors');
    doctors.value = response.data.data;

  } catch (error) {
    console.error('Error fetching doctors:', error);
  }
};


onMounted(() => {
    getDoctors();
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
        <img src="img/banner_img.png" alt>
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
        <div class="col-lg-6 col-sm-6">
        <div class="single_our_depertment">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
        </div>
        <div class="col-lg-6 col-sm-6">
        <div class="single_our_depertment">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
        </div>
        <div class="col-lg-6 col-sm-6">
        <div class="single_our_depertment">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
        </div>
        <div class="col-lg-6 col-sm-6">
        <div class="single_our_depertment">

        <h4>Better Future</h4>
        <p>Darkness multiply rule Which from without life creature blessed
        give moveth moveth seas make day which divided our have.</p>
        </div>
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
                  <div class="single_blog_item">
                    <div class="single_blog_img">
                        <img :src="doctor.img_path ? doctor.img_path : 'img/doctor_1.png'" alt="doctor">

                    </div>
                    <div class="single_text">
                      <div class="single_blog_text">
                        <h3>{{ doctor.name }}</h3>
                        <p>{{ doctor.doctor_id }}</p>
                        <p>{{ doctor.specialization }}</p>
                        <router-link :to="{ name: 'patient.DoctorInfo', params: { id: parseInt(doctor.doctor_id) } }" class="mb-3 btn btn-primary">See Profile</router-link>
                      </div>
                    </div>
                  </div>
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

                <form>
                    <h2 class="mb-4">Make an Appointment</h2>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="inputEmail4" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputEmail4" placeholder="Enter your name">
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword4" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="inputPassword4" placeholder="Enter your email address">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="Select" class="form-label">Select Service</label>
                            <select class="form-select" id="Select">
                                <option value="1" selected>Select service</option>
                                <option value="2">Name of service</option>
                                <option value="3">Name of service</option>
                                <option value="4">Name of service</option>
                                <option value="5">Name of service</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="Select2" class="form-label">Select Time</label>
                            <select class="form-select" id="Select2">
                                <option value="" selected disabled>Select time</option>
                                <option value="1">8 AM TO 10AM</option>
                                <option value="2">10 AM TO 12PM</option>
                                <option value="3">12PM TO 2PM</option>
                                <option value="4">2PM TO 4PM</option>
                                <option value="5">4PM TO 6PM</option>
                                <option value="6">6PM TO 8PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="Textarea" class="form-label">Your Note</label>
                        <textarea class="form-control" id="Textarea" rows="4" placeholder="Enter your note"></textarea>
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
        <img src="img/reservation.png" alt class="reservation_img_iner">
        </div>
        </div>
        </div>
        </div>
        </section>

</template>
