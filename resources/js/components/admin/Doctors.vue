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


const form = reactive({
  name: '',
  email: '',
  password: '',
  specialization: '',
  contactNumber: '',
  salary: '',
  monday: false,
  tuesday: false,
  wednesday: false,
  thursday: false,
  friday: false,
  department_id: null,
});

const getDoctors = (page = 1) => {
    axios.get(`/api/doctors?page=${page}`)
        .then((response) => {
            doctors.value = response.data;
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
        name: doctor.name,
        email: doctor.email,
        specialization: doctor.specialization,
        contactNumber: doctor.contact_number,
        salary: doctor.salary,
        monday: doctor.monday,
        tuesday: doctor.tuesday,
        wednesday: doctor.wednesday,
        thursday: doctor.thursday,
        friday: doctor.friday,
    };

    ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'].forEach(day => {
        form[day] = formValues.value[day];
    });

    form.name = formValues.value.name;
    form.email = formValues.value.email;
    form.specialization = formValues.value.specialization;
    form.contactNumber = formValues.value.contactNumber;
    form.salary = formValues.value.salary;

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

        const requestData = {
            name: form.name,
            email: form.email,
            specialization: form.specialization,
            contact_number: form.contactNumber,
            salary: form.salary,
            monday: form.monday,
            tuesday: form.tuesday,
            wednesday: form.wednesday,
            thursday: form.thursday,
            friday: form.friday,
            department_id: form.department_id,

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



        }).catch((error) => {
            setErrors(error.response.data.errors);
            console.log(error);
        });
        toastr.success('Doctor updated successfully');
        getDoctors();

        } else {

            const response = await axios.post('/api/doctors', requestData);

            const newDoctor = response.data;
            doctors.value.data.push(newDoctor);

            getDoctors();
            toastr.success('Doctor added successfully');
        }

        clearForm();
        $('#createDoctorModal').modal('hide');
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
    form.monday = false;
    form.tuesday = false;
    form.wednesday = false;
    form.thursday = false;
    form.friday = false;
};




const emailValid = (email) => {
    const emailRegex = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
    return emailRegex.test(email);
};

const phoneValid = (phone) => {
    return phone.length === 10;
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
    getDoctors();
    getDepartments();
});
</script>



<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Doctors</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Doctors</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">



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
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">

                          <h5 class="modal-title" id="staticBackdropLabel">
                            <span v-if="updating">Update Doctor</span>
                            <span v-else>Add New Doctor</span>
                        </h5>


                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>


                      <form :initial-values="formValues">
                      <div class="modal-body" style="display: flex; flex-wrap: wrap; gap: 20px;">

                              <div class="form-group" style="flex: 1;">
                                  <label for="name">Name</label>
                                  <input v-model="form.name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter name" required>

                              </div>
                              <div class="form-group">
                                <label for="email">Email</label>
                                <input v-model="form.email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                                <span v-if="form.email && !emailValid(form.email)" class="text-danger">Invalid email format</span>
                            </div>
                              <div class="form-group">
                                <label for="email">Password</label>
                                <input v-model="form.password" type="password" class="form-control " id="password" aria-describedby="nameHelp" placeholder="Enter password" required>
                                <span v-if="form.password && !passwordValid(form.password)" class="text-danger">Password must be 8 characters long</span>
                            </div>

                              <div class="form-group">
                                  <label for="specialization">Specialization</label>
                                  <input v-model="form.specialization" type="text" class="form-control" id="specialization" aria-describedby="specializationHelp" placeholder="Enter specialization" required>
                              </div>

                              <div class="form-group">
                                  <label for="contact_number">Contact Number</label>
                                  <input v-model="form.contactNumber" type="text" class="form-control" id="contact_number" aria-describedby="contactNumberHelp" placeholder="Enter contact number" required pattern="[0-9]{10}">
                                  <span v-if="form.contactNumber && !phoneValid(form.contactNumber)" class="text-danger">Phone number must be 10 digits</span>
                              </div>

                              <div class="form-group">
                                  <label for="salary">Salary</label>
                                  <input v-model="form.salary" type="text" class="form-control" id="salary" aria-describedby="salaryHelp" placeholder="Enter salary" required>
                              </div>

                            <div class="form-group" >
                                <label>Working Days</label>
                                <div class="form-check">
                                    <input v-model="form.monday" type="checkbox" class="form-check-input" :checked="Boolean(form.monday)" id="monday">
                                    <label v-if="form.monday == 1" class="form-check-label" :style="{ color: 'green' }" for="monday">Monday</label>
                                    <label v-else class="form-check-label" for="monday">Monday</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="form.tuesday" type="checkbox" class="form-check-input" :checked="Boolean(form.tuesday)" id="tuesday">
                                    <label v-if="form.tuesday == 1" class="form-check-label" :style="{ color: 'green' }" for="tuesday">Tuesday</label>
                                    <label v-else class="form-check-label" for="tuesday">Tuesday</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="form.wednesday" type="checkbox" class="form-check-input" :checked="Boolean(form.wednesday)" id="wednesday">
                                    <label v-if="form.wednesday == 1" class="form-check-label" :style="{ color: 'green' }" for="wednesday">Wednesday</label>
                                    <label v-else class="form-check-label" for="wednesday">Wednesday</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="form.thursday" type="checkbox" class="form-check-input" :checked="Boolean(form.thursday)" id="thursday">
                                    <label v-if="form.thursday == 1" class="form-check-label" :style="{ color: 'green' }" for="thursday">Thursday</label>
                                    <label v-else class="form-check-label" for="thursday">Thursday</label>
                                </div>
                                <div class="form-check">
                                    <input v-model="form.friday" type="checkbox"  class="form-check-input" :checked="Boolean(form.friday)" id="friday">
                                    <label v-if="form.friday == 1" class="form-check-label" :style="{ color: 'green' }" for="friday">Friday</label>
                                    <label v-else class="form-check-label" for="friday">Friday</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="department_id">Department</label>
                                <select v-model="form.department_id" class="form-control" id="department_id" required>
                
                                  <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                                </select>
                              </div>

                        </div>

                          </form>

                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                          <button @click="saveDoctor" type="button" class="btn btn-purple">Save</button>
                      </div>
                  </div>
              </div>
          </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <th style="width: 10px">id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>specialization</th>
                                <th>contact_number</th>
                                <th>Salary</th>
                                <th>Appointments</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody v-if="doctors.data.length > 0">
                            <tr v-for="(doctor, index) in doctors.data" :key="index">
                                <td>{{ doctor.doctor_id }}</td>
                                <td>{{ doctor.name }}</td>
                                <td>{{ doctor.email }}</td>
                                <td>{{ doctor.specialization }}</td>
                                <td>{{ doctor.contact_number }}</td>
                                <td>{{ doctor.salary }}</td>
                                <td class="centered-cell">
                                 <span class="appointment-count">{{ doctor.appointment_count }}</span>
                                </td>
                                <td>
                                    <a href="#" @click.prevent="updateDoctor(doctor)">  <i class="fa fa-edit" style="color: #9528b8;"></i> </a>
                                    <a href="#" @click.prevent="deleteDoctor(doctor)">  <i class="fa fa-trash text-danger ml-3"></i> </a>

                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <td colspan="8" class="text-center">No result found</td>
                        </tbody>



                    </table>


                </div>
            </div>
 <Bootstrap4Pagination :data="doctors" @pagination-change-page="getDoctors" />

        </div>
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
</template>
