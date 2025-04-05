<script setup lang="ts">
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import {useTaskStore} from '../stores/taskStore';

const taskStore = useTaskStore();
const title = ref<string>('');
const description = ref<string>('');
const router = useRouter();

const addTask = async () => {
  await taskStore.addTask(title.value, description.value);
};
</script>

<template>
  <div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-4">Add a Task</h2>
    <form id="task-form">
      <div class="mb-4">
        <input type="text" id="title" v-model="title" class="w-full p-2 border border-gray-300 rounded-md"
               placeholder="Title" required>
      </div>
      <div class="mb-4">
        <textarea id="description" v-model="description" class="w-full p-2 border border-gray-300 rounded-md"
                  placeholder="Description"></textarea>
      </div>
      <button @click.prevent="addTask" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Add
      </button>
    </form>
  </div>
</template>