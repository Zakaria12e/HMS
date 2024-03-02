import Dashboard from './components/admin/Dashboard.vue';
import Appointments from './components/admin/Appointments.vue';
import Patients from './components/admin/Patients.vue';
import UpdateProfile from './components/admin/UpdateProfile.vue';
import Doctors from './components/admin/Doctors.vue';
import Profile from './components/admin/Profile.vue';
import Invoices from './components/admin/Invoices.vue';

import Patients_doc from './components/doctor/Patients.vue';
import Appointments_doc from './components/doctor/Appointments.vue';
import UpdateDoctorProfile from './components/doctor/UpdateDoctorProfile.vue';
import DoctorDashboard from './components/doctor/DoctorDashboard.vue';

import Home from './components/patient/Home.vue';
import DoctorInfo from './components/patient/DoctorInfo.vue';
import About from './components/patient/About.vue';
import Contact from './components/patient/Contact.vue';
import MyAppointments from './components/patient/MyAppointments.vue';
import Departments from './components/admin/Departments.vue';

export default [


    {
        path: '/admin',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    {

        path: '/admin/appointments',
        name: 'admin.appointments',
        component: Appointments,
    },
    {

        path: '/admin/patients',
        name: 'admin.patients',
        component: Patients,
    },
    {

        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
    },
    {

        path: '/admin/invoices',
        name: 'admin.invoices',
        component: Invoices,
    },
    {

        path: '/admin/doctors',
        name: 'admin.doctors',
        component: Doctors,
    },
    {

        path: '/admin/profile',
        name: 'admin.profile',
        component: Profile,
    },
    {

        path: '/admin/departments',
        name: 'admin.departments',
        component: Departments,
     },



    {
        path: '/doctor/patients',
        name: 'doctor.patients',
        component: Patients_doc,

    },
    {
        path: '/doctor/appointments',
        name: 'doctor.appointments',
        component: Appointments_doc,

    },
    {

        path: '/doctor/profile',
        name: 'doctor.profile',
        component: UpdateDoctorProfile,
    },
    {

        path: '/doctor',
        name: 'doctor.dashboard',
        component: DoctorDashboard,
    },
    {

        path: '/home',
        name: 'patient.home',
        component: Home,
    },
    {

        path: '/home/doctor-info/:id',
        name: 'patient.DoctorInfo',
        component: DoctorInfo,
    },
    {

        path: '/about',
        name: 'patient.about',
        component: About,
    },
    {

        path: '/appointments',
        name: 'patient.appointments',
        component: MyAppointments,
    },
    {

        path: '/contact',
        name: 'patient.contact',
        component: Contact,
    },

]
