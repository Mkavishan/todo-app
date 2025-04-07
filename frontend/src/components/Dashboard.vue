<script setup lang="ts">
import {onMounted} from 'vue';
import AddTask from './AddTask.vue'
import TaskList from './TaskList.vue'
import {useTaskStore} from '../stores/taskStore';

const taskStore = useTaskStore();

onMounted(() => {
  // Fetch Tasks.
  taskStore.fetchTasks();
});

</script>

<template>
  <!-- Display loading state -->
  <div v-if="taskStore.loading" class="fixed inset-0 bg-black-100 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
      <span class="text-xl font-semibold">Loading...</span>
    </div>
  </div>

  <!-- Display error message if available -->
  <div v-if="taskStore.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
       role="alert">
    <p class="font-semibold">{{ taskStore.error }}</p>
  </div>

  <div v-if="!taskStore.loading" class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Left Side: Form -->
    <AddTask></AddTask>

    <!-- Right Side: Task List -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <div id="task-list">
        <TaskList></TaskList>
      </div>
    </div>
  </div>
</template>