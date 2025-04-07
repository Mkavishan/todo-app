import {defineStore} from 'pinia';
import {ref} from 'vue';
import axios from 'axios';
import {API_BASE_URL} from '../global/constants';
import type {Task} from '../types/task';

export const useTaskStore = defineStore('task', () => {
  const tasks = ref<Task[]>([]);
  const loading = ref<boolean>(false);
  const error = ref<string | null>(null);  // For error handling

  const fetchTasks = async () => {
    loading.value = true;
    error.value = null;  // Reset error before starting a request

    try {
      const response = await axios.get(API_BASE_URL + '/tasks');
      if (response.data) {
        tasks.value = response.data.data;
      }
    } catch (err) {
      // Handle the error and store the error message
      error.value = 'Failed to fetch tasks. Please try again later.';
    } finally {
      loading.value = false;
    }
  };

  const completeTask = async (id: number) => {
    loading.value = true;
    error.value = null;  // Reset error before starting a request

    try {
      await axios.patch(`${API_BASE_URL}/tasks/${id}/complete`);
      await fetchTasks();
    } catch (err) {
      // Handle the error and store the error message
      error.value = `Failed to complete task with ID ${id}. Please try again later.`;
    } finally {
      loading.value = false;
    }
  };

  const addTask = async (title: string, description: string) => {
    loading.value = true;
    error.value = null;  // Reset error before starting a request

    try {
      const response = await axios.post(API_BASE_URL + '/tasks', {
        title,
        description
      });

      if (response.data) {
        const newTask: Task = response.data.data;
        tasks.value = [newTask, ...tasks.value.slice(0, 4)];
      }
    } catch (err) {
      // Handle the error and store the error message
      error.value = 'Failed to add task. Please try again later.';
    } finally {
      loading.value = false;
    }
  };

  return {
    tasks,
    loading,
    error,
    fetchTasks,
    completeTask,
    addTask
  };
});
