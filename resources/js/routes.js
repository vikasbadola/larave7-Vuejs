import Task from './components/TaskComponent.vue';
import Dashboard from './components/DashboardComponent.vue';
export const routes = [
    {
        path:'/dashboard',
        component:Dashboard
    },
    { 
        path:'/tasks',
        component:Task
    },
];