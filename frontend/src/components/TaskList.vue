<script setup lang="ts">
import {useTaskStore} from '../stores/taskStore';

const taskStore = useTaskStore();

const completeTask = (id: number) => {
  taskStore.completeTask(id);
};
</script>

<template>
  <div v-if="!taskStore.loading && taskStore.tasks.length === 0">
    <p class="text-center text-gray-500">No tasks available</p>
  </div>
  <div v-for="task in taskStore.tasks" :key="task.id" class="mb-4 p-4 bg-gray-100 rounded-md shadow">
    <h3 class="text-xl font-semibold mb-2">{{ task.title }}</h3>

    <div class="flex justify-between items-end">
      <p class="text-gray-700 max-w-[85%]">{{ task.description }}</p>
      <button
          class="ml-4 px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 whitespace-nowrap"
          v-if="!task.completed"
          @click="completeTask(task.id)"
      >Done</button>
    </div>
  </div>
</template>