import {describe, it, expect, vi} from 'vitest'
import {render, screen, fireEvent, waitFor} from '@testing-library/vue'
import Dashboard from '../../components/Dashboard.vue'
import {createTestingPinia} from '@pinia/testing'
import {useTaskStore} from '../../stores/taskStore'
import {nextTick} from 'vue'

// Mock child components
vi.mock('../../components/AddTask.vue', () => ({
  default: {
    name: 'AddTask',
    template: '<div data-testid="add-task">AddTask Stub</div>',
  },
}))

vi.mock('../../components/TaskList.vue', () => ({
  default: {
    name: 'TaskList',
    template: '<div data-testid="task-list">TaskList Stub</div>',
  },
}))

describe('Dashboard.vue', () => {
  it('shows loading spinner when taskStore.loading is true', () => {
    render(Dashboard, {
      global: {
        plugins: [
          createTestingPinia({
            initialState: {
              task: {loading: true},
            },
          }),
        ],
      },
    })

    expect(screen.queryByText('Loading...')).toBeTruthy()
  })

  it('renders AddTask and TaskList when loading is false', () => {
    render(Dashboard, {
      global: {
        plugins: [
          createTestingPinia({
            initialState: {
              task: {loading: false},
            },
          }),
        ],
      },
    })

    expect(screen.getByTestId('add-task')).toBeTruthy()
    expect(screen.getByTestId('task-list')).toBeTruthy()
  })
})
