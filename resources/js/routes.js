import Dashboard from './components/admin/Dashboard.vue';
import Appointments from './components/admin/Appointments.vue';
import Patients from './components/admin/Patients.vue';
import AdminProfile from './components/admin/AdminProfile.vue';
import Doctors from './components/admin/Doctors.vue';
import Invoices from './components/admin/Invoices.vue';

import Patients_doc from './components/doctor/Patients.vue';
import Appointments_doc from './components/doctor/Appointments.vue';
import UpdateDoctorProfile from './components/doctor/UpdateDoctorProfile.vue';
import DoctorDashboard from './components/doctor/DoctorDashboard.vue';
import DoctorInvoices from './components/doctor/DoctorInvoices.vue';

import Home from './components/patient/Home.vue';
import DoctorInfo from './components/patient/DoctorInfo.vue';
import About from './components/patient/About.vue';
import MedicalReports from './components/patient/MedicalReports.vue';
import MyAppointments from './components/patient/MyAppointments.vue';
import Departments from './components/admin/Departments.vue';

export default [


    {
        path: '/admin',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    {
        path: '/admin/patients',
        name: 'admin.patients',
        component: Patients,

    },
    {

        path: '/admin/appointments',
        name: 'admin.appointments',
        component: Appointments,
    },

    {

        path: '/admin/profile',
        name: 'admin.profile',
        component: AdminProfile,
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

        path: '/doctor/invoices',
        name: 'doctor.invoices',
        component: DoctorInvoices,
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

        path: '/Medical-Reports',
        name: 'patient.MedicalReports',
        component: MedicalReports,
    },

]
