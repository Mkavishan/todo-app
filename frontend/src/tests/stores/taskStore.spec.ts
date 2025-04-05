import {setActivePinia, createPinia} from 'pinia'
import {describe, it, expect, beforeEach, vi} from 'vitest'
import axios from 'axios'
import {useTaskStore} from '../../stores/taskStore'
import {API_BASE_URL} from '../../global/constants';

vi.mock('axios')
const mockedAxios = axios as jest.Mocked<typeof axios>

describe('taskStore', () => {
  let taskStore: ReturnType<typeof useTaskStore>;

  beforeEach(() => {
    setActivePinia(createPinia());
    taskStore = useTaskStore();
  });

  it('fetches tasks', async () => {
    mockedAxios.get.mockResolvedValue({
      data: {
        data: [
          {id: 1, title: 'Sample', description: 'Desc', completed: false}
        ]
      }
    })

    const store = useTaskStore()
    await store.fetchTasks()

    expect(store.tasks.length).toBe(1)
    expect(store.tasks[0].title).toBe('Sample')
  })

  it('adds a task', async () => {
    mockedAxios.post.mockResolvedValue({
      data: {
        data: {id: 2, title: 'New Task', description: 'Desc', completed: false}
      }
    })

    const store = useTaskStore()
    await store.addTask('New Task', 'Desc')

    expect(store.tasks[0].title).toBe('New Task')
  })

  it('calls the correct API and refetches tasks on successful completeTask', async () => {
    const taskId = 1;
    const mockTasks = [{id: taskId, title: 'Done task', completed: true}];

    // Mock PATCH request
    mockedAxios.patch.mockResolvedValueOnce({status: 200});

    // Mock GET request after PATCH
    mockedAxios.get.mockResolvedValueOnce({
      data: {data: mockTasks}
    });

    await taskStore.completeTask(taskId);

    expect(mockedAxios.patch).toHaveBeenCalledWith(`${API_BASE_URL}/tasks/${taskId}/complete`);
    expect(mockedAxios.get).toHaveBeenCalledWith(`${API_BASE_URL}/tasks`);
    expect(taskStore.tasks).toEqual(mockTasks);
    expect(taskStore.loading).toBe(false);
  });

  it('handles error in completeTask properly', async () => {
    const taskId = 123;
    const mockErrorMessage = 'Internal Server Error';

    mockedAxios.patch.mockRejectedValueOnce(new Error(mockErrorMessage));

    await taskStore.completeTask(taskId);

    expect(taskStore.error).toBe(`Failed to complete task with ID ${taskId}. Please try again later.`);
    expect(taskStore.loading).toBe(false);
  });
})
