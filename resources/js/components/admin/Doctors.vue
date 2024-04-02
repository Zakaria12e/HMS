<script setup>
import axios from 'axios';
import { ref, onMounted, reactive , watch  } from 'vue';
import { useToastr } from '/resources/js/toastr.js';
import { debounce } from 'lodash';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import $ from 'jquery';


const toastr = useToastr();
const doctors = ref({'data':[]});
const updating = ref(false);
const formValues = ref();
const workingHoursModal = ref(null);
const existingWorkingHours = ref([]);
const descriptionContent = ref('');
const workingHoursForm = reactive({
    id: null,
    day: '',
    start_time: '',
    end_time: '',
});

const getdays = async(doc_id) => {

    try {
        const response = await axios.get(`/api/working-hours-read/${doc_id}`);
        existingWorkingHours.value = response.data;
    } catch (error) {
        console.error('Error fetching existing working hours', error);
    }

};

const openWorkingHoursModal = async (doc_id) => {

    workingHoursForm.id = doc_id;
    workingHoursForm.day = '';
    workingHoursForm.start_time = '';
    workingHoursForm.end_time = '';

    getdays(doc_id);

    $('#workingHoursModal').modal('show');
};




const addWorkingHours = async () => {
    try {
        // Check if start time is before end time
        const startTime = new Date(`01/01/2000 ${workingHoursForm.start_time}`);
        const endTime = new Date(`01/01/2000 ${workingHoursForm.end_time}`);
        if (startTime >= endTime) {
            toastr.error('Start time must be before end time');
            return;
        }

        if (!workingHoursForm.id) {
            console.error('Invalid doctor ID');
            return;
        }

        const requestData = {
            day: workingHoursForm.day,
            start_time: workingHoursForm.start_time,
            end_time: workingHoursForm.end_time,
            doctor_id: workingHoursForm.id,
        };

        const existingWorkingHours = await axios.get(`/api/working-hours/check`, {
            params: {
                doctor_id: workingHoursForm.id,
                day: workingHoursForm.day,
            },
        });

        if (existingWorkingHours.data.exists) {
            toastr.error('Working hours already exist for this day');
            return;
        }

        const response = await axios.post('/api/working-hours', requestData);

        $('#workingHoursModal').modal('hide');
        toastr.success('Working hours added successfully');
    } catch (error) {
        console.error('Error adding working hours:', error);
    }
};





const deleteWorkingDay = async (dayId , doc_id) => {
    try {

        const response = await axios.delete(`/api/working-hours/${dayId}`);

        getdays(doc_id);

        toastr.success('Working day deleted successfully');
    } catch (error) {
        console.error('Error deleting working day:', error);
    }
};






const form = reactive({
  name: '',
  email: '',
  password: '',
  specialization: '',
  contactNumber: '',
  salary: '',
  department_id: '',
  description: '',
});

const getDoctors = (page = 1) => {
    axios.get(`/api/doctors?page=${page}`)
        .then((response) => {
            const doctorsWithDepartments = response.data.data.map(doctor => {
                const department = departments.value.find(dep => dep.id === doctor.department_id);
                return {
                    ...doctor,
                    department_name: department ? department.name : 'Unknown Department',
                };
            });

            doctors.value = { ...response.data, data: doctorsWithDepartments };
        });
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


const openDescription = (doctor) => {

         descriptionContent.value = doctor.description;

        $('#descriptionModal').modal('show');

};




const deleteDoctor = async (doctor) => {
    try {
        const confirmDelete = confirm('Are you sure you want to delete this doctor?');

        if (!confirmDelete) {
            return;
        }

        const response = await axios.delete(`/api/doctors/${doctor.doctor_id}`);

        getDoctors();
        toastr.success('Doctor deleted successfully');


    } catch (error) {
        console.error('Error deleting doctor:', error);
    }
};


const updateDoctor = (doctor) => {
    updating.value = true;

    formValues.value = {
        id: doctor.doctor_id,
        name: doctor.user.name,
        email: doctor.user.email,
        specialization: doctor.specialization,
        contactNumber: doctor.user.contact_number,
        salary: doctor.salary,
        description: doctor.description,

    };



    form.name = formValues.value.name;
    form.email = formValues.value.email;
    form.specialization = formValues.value.specialization;
    form.contactNumber = formValues.value.contactNumber;
    form.salary = formValues.value.salary;
    form.department_id = doctor.department_id;
    form.description = doctor.description;

    $('#createDoctorModal').modal('show');

    $('#createDoctorModal').on('hidden.bs.modal', function () {
        clearForm();
    });

};


const searchQuery = ref(null);

const search = () => {

    axios.get('/api/doctors/search',{

        params: {
            query: searchQuery.value
        }
    })
    .then(response => {

        doctors.value = response.data;
    })
    .catch(error => {

        console.log(error);
    })

};


const saveDoctor = async () => {
    try {

        if (!form.name.trim() || !form.email.trim() || !form.specialization.trim() || !form.contactNumber.trim() || !form.salary.trim()) {
            toastr.error('Please fill in all required fields');
            return;
        }

        const requestData = {
            name: form.name,
            email: form.email,
            specialization: form.specialization,
            contact_number: form.contactNumber,
            salary: form.salary,
            department_id: form.department_id,
            description: form.description,
        };

        if (form.password.trim() !== '') {
            requestData.password = form.password;
        }


          if (updating.value) {

            axios.put('/api/doctors/' + formValues.value.id, requestData)
             .then((response) => {
            const index = doctors.value.data.findIndex(doctor => doctor.id === response.data.id);

            doctors.value.data[index] = response.data;

            $('#createDoctorModal').modal('hide');

            toastr.success('Doctor updated successfully');
        getDoctors();

        }).catch((error) => {

            if (error.response.status === 422 && error.response.data.error === 'Email already exists') {
            toastr.error('Email already exists');
            return;

        } else {

                    setErrors(error.response.data.errors);
                    console.log(error);
                }

        });


        } else {

            const response = await axios.post('/api/doctors', requestData);

            if (response.data.error && response.data.error === 'Email already exists') {

                toastr.error('Email already exists');
                return;
            } else {

            const newDoctor = response.data;
            doctors.value.data.push(newDoctor);

            getDoctors();
            toastr.success('Doctor added successfully');
            clearForm();
            $('#createDoctorModal').modal('hide');
        }
    }


    } catch (error) {
        console.error('Error creating/updating doctor:', error);
    }
};



const clearForm = () => {
    form.name = '';
    form.email = '';
    form.password = '';
    form.specialization = '';
    form.contactNumber = '';
    form.salary = '';
    form.description = '';
};




const emailValid = (email) => {
    const emailRegex = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    return emailRegex.test(email);
};

const phoneValid = (phone) => {
    return /^212\d{9}$/.test(phone);
};

const passwordValid = (password) => {
    return password.length >= 8;
};

const addDoctor = () =>{

    updating.value = false;
    $('#createDoctorModal').modal('show');

};


watch(searchQuery, debounce(() => {

    search();
}, 300));

onMounted(() => {

    getDepartments();
    getDoctors();



});
 const defaultImageUrl = '/storage/photos/No_Image_Available.jpg';
</script>



<template>


    <main class="content-wrap">
        <header class="content-head">
            <h1>Doctors</h1>

        </header>
    <div class="content">



<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="descriptionModalLabel"><b>Description & Qualifications</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ descriptionContent }}</p>
            </div>
        </div>
    </div>
</div>



            <div class="d-flex justify-content-between">

              <button @click="addDoctor" type="button" class="mb-3 btn btn-purple">
                <i class="fa fa-plus-circle mr-1"></i>
                Add Doctor
              </button>


              <div>

                <input type="text" v-model="searchQuery" class="form-control" placeholder="Search..."/>

              </div>
            </div>




            <div class="modal fade" id="createDoctorModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-purple text-white">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                <span v-if="updating">Update Doctor</span>
                                <span v-else>Add New Doctor</span>
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form :initial-values="formValues">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Name</label>
                                        <input v-model="form.name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email">Email</label>
                                        <input v-model="form.email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                        <span v-if="form.email && !emailValid(form.email)" class="text-danger">Invalid email format</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password">Password</label>
                                        <input v-model="form.password" type="password" class="form-control " id="password" aria-describedby="passwordHelp" placeholder="Enter password" required>
                                        <span v-if="form.password && !passwordValid(form.password)" class="text-danger">Password must be 8 characters long</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="specialization">Specialization</label>
                                        <select v-model="form.specialization" class="form-control" id="specialization" required>
                                            <option value="" disabled>Select specialization</option>
                                            <option value="Cardiology">Cardiology</option>
                                            <option value="Dermatology">Dermatology</option>
                                            <option value="Endocrinology">Endocrinology</option>
                                            <option value="Gastroenterology">Gastroenterology</option>
                                            <option value="Hematology">Hematology</option>
                                            <option value="Neurology">Neurology</option>
                                            <option value="Orthopedics">Orthopedics</option>
                                            <option value="Pediatrics">Pediatrics</option>
                                            <option value="Psychiatry">Psychiatry</option>
                                            <option value="Urology">Urology</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_number">Contact Number</label>
                                        <input v-model="form.contactNumber" type="text" class="form-control" id="contact_number" aria-describedby="contactNumberHelp" placeholder="212xxxxxxxx" required pattern="[0-9]{12}">
                                        <span v-if="form.contactNumber && !phoneValid(form.contactNumber)" class="text-danger">Invalid phone number format</span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="salary">Salary</label>
                                        <input v-model="form.salary" type="text" class="form-control" id="salary" aria-describedby="salaryHelp" placeholder="Enter salary" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea v-model="form.description" class="form-control" id="description" rows="4" placeholder="Enter description"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="department_id">Department</label>
                                    <select v-model="form.department_id" class="form-control" id="department_id">
                                        <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button @click="saveDoctor" type="button" class="btn btn-purple">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

          <div class="modal fade" id="workingHoursModal" tabindex="-1" role="dialog" aria-labelledby="workingHoursModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="workingHoursModalLabel">Add Working Hours</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="day">Day</label>
                        <select v-model="workingHoursForm.day" class="form-control" id="day" required>
                            <option value="" disabled>Select a day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input v-model="workingHoursForm.start_time" type="text" class="form-control" id="start_time" placeholder="HH:mm" required pattern="[0-2][0-9]:[0-5][0-9]">
                    </div>
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input v-model="workingHoursForm.end_time" type="text" class="form-control" id="end_time" placeholder="HH:mm" required pattern="[0-2][0-9]:[0-5][0-9]">
                    </div>

                </div>

                <div style="padding-left: 10px;" v-if="existingWorkingHours.length > 0">
                    <p><b>Existing Working Days:</b></p>
                    <div class="row">
                        <div class="col-md-6" v-for="(days, index) in existingWorkingHours" :key="index">
                  <div class="mb-3">
                       {{ days.day }}: {{ days.start_time }} - {{ days.end_time }}
                 <a href="#"  @click.prevent="deleteWorkingDay(days.id , days.doctor_id)" class="ml-3"><i class="fa fa-trash text-danger"></i></a>
            </div>
        </div>
    </div>
</div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click="addWorkingHours" type="button" class="btn btn-purple">Add Working Hours</button>
                </div>
            </div>
        </div>
    </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>specialization</th>
                                <th>contact_number</th>
                                <th>Salary</th>
                                <th>Department</th>
                                <th>Appointments</th>
                                <th colspan="4">Options</th>
                            </tr>
                        </thead>
                        <tbody v-if="doctors.data.length > 0">
                            <tr v-for="(doctor, index) in doctors.data" :key="index">
                                <td><img class="doctor-profile-img" :src="doctor.user.img_path ? doctor.user.img_path : defaultImageUrl" alt="Doctor profile picture"></td>
                                <td>{{ doctor.user.name }}</td>
                                <td>{{ doctor.user.email }}</td>
                                <td>{{ doctor.specialization }}</td>
                                <td>{{ doctor.user.contact_number }}</td>
                                <td>{{ doctor.salary }}</td>
                                <td>{{ doctor.department_name }}</td>
                                <td class="centered-cell">
                                 <span class="appointment-count">{{ doctor.appointments_count }}</span>
                                </td>
                                <td>
                                    <a href="#" @click.prevent="updateDoctor(doctor)">  <i class="fa fa-edit" style="color: #9528b8;"></i> </a>
                                    <a href="#" @click.prevent="deleteDoctor(doctor)">  <i class="fa fa-trash text-danger ml-3"></i> </a>
                                    <a href="#" @click.prevent="openWorkingHoursModal(doctor.doctor_id)"> <i class="fa fa-calendar-alt  ml-3" style="color: black;"></i></a>
                                    <a href="#" @click.prevent="openDescription(doctor)"> <i class="fa fa-eye ml-3" style="color: #9528b8;"></i> </a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <td colspan="9" class="text-center">No result found</td>
                        </tbody>



                    </table>


                </div>
            </div>
 <Bootstrap4Pagination :data="doctors" @pagination-change-page="getDoctors" />


    </div>

    <div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span >Edit Doctor</span>
                        <span >Add New Doctor</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this Doctor ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="" type="button" class="btn btn-primary">Delete Doctor</button>
                </div>
            </div>
        </div>
    </div>

    </main>
</template>
<style scoped>
.doctor-profile-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

p{
    color:black !important;
    font-size: 20px;
}
</style>

